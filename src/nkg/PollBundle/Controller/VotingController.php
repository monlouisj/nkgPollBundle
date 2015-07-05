<?php

namespace nkg\PollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use nkg\PollBundle\Entity\Poll;
use nkg\PollBundle\Entity\PollRepository;
use nkg\PollBundle\Entity\Opinion;
use nkg\PollBundle\Entity\OpinionRepository;

class VotingController extends Controller
{

  //lister les sondages actifs
  /**
   * @Route("/front/" ,name="_frontlist")
   */
  public function listPollAction()
  {
      $nkgPoll = $this->get('nkg.poll');
      $pollz = $nkgPoll->getActivePolls();

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

      $nkgOpinion = $this->get('nkg.opinion');
      $opinionz = $nkgOpinion->getVotableOpinions($poll_id);

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

      $nkgPoll = $this->get('nkg.poll');
      $opinionz = $nkgPoll->getResultOf($poll_id);

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
      $opinion_id = $request->get('opinion_id');
      $nkgOpinion = $this->get('nkg.opinion');
      $nkgPoll = $this->get('nkg.poll');

      $poll_id = $nkgPoll->getPollIdFromOpinion($opinion_id);

      //si l'utilisateur a déjà voté
      if($session->get("did_vote") === $poll_id){
        return $this->render('nkgPollBundle:front:front.fail.html.twig');
      }else{
        $nkgOpinion->addVote($opinion_id);
        //enregistrer vote en session
        $session->set("did_vote", $poll_id);
      }

      return $this->render('nkgPollBundle:front:front.voted.html.twig',
      array("poll_id"=> $poll_id )
      );
  }
}
