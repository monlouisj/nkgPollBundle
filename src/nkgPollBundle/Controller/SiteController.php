<?php

namespace nkgPollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use nkgPollBundle\Entity\Poll;
use nkgPollBundle\Entity\PollRepository;
use nkgPollBundle\Entity\Opinion;
use nkgPollBundle\Entity\OpinionRepository;

class SiteController extends Controller
{

  /**
   * @Route("/front/" ,name="_frontlist")
   */
  public function listPollAction()
  {
      $pollz = $this->getDoctrine()
      ->getRepository('nkgPollBundle:Poll')
      ->findBy(array("active" =>1));

      return $this->render('nkgPollBundle:front:front.poll.list.html.twig',
      array("pollz"=> $pollz)
      );
  }

  //lister les items d'un sondage actif
  /**
   * @Route("/front/poll/{poll_id}" ,name="_frontlistopinion")
   */
  public function listOpinionAction(Request $request)
  {
      $poll_id = $request->get('poll_id');

      $opinionz = $this->getDoctrine()
      ->getRepository('nkgPollBundle:Poll')
      ->findOneBy(array(
        "id" => (int)$poll_id,
        "active" => 1
        )
      )->getOpinions();

      return $this->render('nkgPollBundle:front:front.opinion.list.html.twig',
      array("opinionz"=> $opinionz)
      );
  }

  //afficher les résultats d'un sondage
  /**
   * @Route("/front/result/{poll_id}" ,name="_frontresult")
   */
  public function resultAction(Request $request)
  {
      $poll_id = $request->get('poll_id');

      $opinionz = $this->getDoctrine()
      ->getRepository('nkgPollBundle:Poll')
      ->find($poll_id)
      ->getOpinions();

      $opinionz = $this->aggregate($opinionz);

      return $this->render('nkgPollBundle:front:front.result.html.twig',
      array("opinionz"=> $opinionz)
      );
  }

  //voter pour une opinion
  /**
   * @Route("/front/vote/{opinion_id}" ,name="_frontopinionvote")
   */
  public function votePollAction(Request $request)
  {
      $session = $request->getSession();
      //si l'utilisateur a déjà voté
      if($session->get("a_vote")){
        return $this->render('nkgPollBundle:front:front.fail.html.twig');
      }

      $opinion_id = $request->get('opinion_id');

      $opinion = $this->getDoctrine()
      ->getRepository('nkgPollBundle:Opinion')
      ->find($opinion_id);

      //ajouter un vote
      $opinion->addVote();
      //marquer la date du dernier vote
      $opinion->setLastvoteat( new \DateTime('now'));

      $em = $this->getDoctrine()->getManager();
      $em->persist($opinion);
      $em->flush();

      //enregistrer vote en session
      $session->set("a_vote", true);

      return $this->render('nkgPollBundle:front:front.voted.html.twig',
      array("poll_id"=> $opinion->getPoll()->getId())
      );
  }

  //calculer les totaux et pourcentages des votes
  private function aggregate($opinionz){
    $count = count($opinionz);
    $total = 0;

    foreach ($opinionz as $opinion) {
      $int_Votes = intval($opinion->getVotes());
      $votes = $int_Votes? $int_Votes : 0;
      $total += $votes;
    }

    foreach ($opinionz as $i => $opinion) {
      $opinionz[$i]->pct = $opinion->getVotes() / $total * 100;
    }
    return $opinionz;
  }
}
