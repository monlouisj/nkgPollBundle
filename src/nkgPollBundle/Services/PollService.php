<?php
namespace nkgPollBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityManager;

use nkgPollBundle\Entity\Poll;
use nkgPollBundle\Entity\PollRepository;
use nkgPollBundle\Entity\Opinion;
use nkgPollBundle\Entity\OpinionRepository;

class PollService
{
  protected $em;

  public function __construct(EntityManager $em) {
      $this->em = $em;
  }

  //trouver 1 sondage
  public function getPoll($poll_id)
  {
    $poll = $this->em
    ->getRepository('nkgPollBundle:Poll')
    ->find($poll_id);

    return $poll;
  }

  //ajouter une opinion à un sondage
  public function addOpinion($poll_id,$opinion)
  {
    $poll = $this->em
    ->getRepository('nkgPollBundle:Poll')
    ->find($poll_id)
    ->addOpinion($opinion);

    $opinion->setPoll($poll);

    $this->em->persist($poll);
    $this->em->persist($opinion);

    $this->em->flush();
  }

  //lister tous les sondages
  public function getAllPolls()
  {
    $pollz = $this->em
    ->getRepository('nkgPollBundle:Poll')
    ->findAll();

    return $pollz;
  }

  //lister les sondages actifs
  public function getActivePolls()
  {
    $pollz = $this->em
    ->getRepository('nkgPollBundle:Poll')
    ->findBy(array("active" =>1));

    return $pollz;
  }

  //récupérer l'id d'un sondage depuis une opinion
  public function getPollIdFromOpinion($opinion_id)
  {
    $opinion = $this->em
    ->getRepository('nkgPollBundle:Opinion')
    ->find($opinion_id);

    return $opinion->getPoll()->getId();
  }

  //afficher les résultats d'un sondage
  public function getResultOf($poll_id)
  {
    $opinionz = $this->em
    ->getRepository('nkgPollBundle:Poll')
    ->find($poll_id)
    ->getOpinions();

    $opinionz = $this->aggregate($opinionz);

    return $opinionz;
  }

  //sauvegarder 1 sondage
  public function savePoll($poll)
  {
    $poll->setModifiedat(new \DateTime('now'));
    //enregistrer les modifs
    $this->em->persist($poll);
    $this->em->flush();

    return $poll;
  }

  //supprimer 1 sondage
  public function deletePoll($poll_id)
  {
    $poll = $this->em
    ->getRepository('nkgPollBundle:Poll')
    ->find($poll_id);

    $opinions = $poll->getOpinions();

    foreach ($opinions as $opinion) {
      $this->em->remove($opinion);
    }

    $this->em->remove($poll);
    $this->em->flush();

    return $poll;
  }

  //calculer les totaux et pourcentages des votes
  private function aggregate($opinionz){
    $count = count($opinionz);

    if($count == 0) return $opinionz;

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
