<?php
namespace Nkg\PollBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityManager;

use Nkg\PollBundle\Entity\Poll;
use Nkg\PollBundle\Entity\PollRepository;
use Nkg\PollBundle\Entity\Opinion;
use Nkg\PollBundle\Entity\OpinionRepository;

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
    ->getRepository('NkgPollBundle:Poll')
    ->find($poll_id);

    return $poll;
  }

  //ajouter une opinion à un sondage
  public function addOpinion($poll_id,$opinion)
  {
    $poll = $this->em
    ->getRepository('NkgPollBundle:Poll')
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
    ->getRepository('NkgPollBundle:Poll')
    ->findAll();

    return $pollz;
  }

  //lister les sondages actifs par date
  public function getActivePolls()
  {
    $pollz = $this->em
    ->createQuery('SELECT p
      FROM NkgPollBundle:Poll p
      WHERE p.active = 1
      AND CURRENT_TIMESTAMP() BETWEEN p.startdate AND p.enddate
      ORDER BY p.enddate DESC');

    try {
        $res = $pollz->getResult();
        return $res;
    } catch (\Doctrine\ORM\NoResultException $e) {
        return array();
    }
  }

  //récupérer l'id d'un sondage depuis une opinion
  public function getPollIdFromOpinion($opinion_id)
  {
    $opinion = $this->em
    ->getRepository('NkgPollBundle:Opinion')
    ->find($opinion_id);

    return $opinion->getPoll()->getId();
  }

  //afficher les résultats d'un sondage
  public function getResultOf($poll_id)
  {
    $opinionz = $this->em
    ->createQuery('SELECT o FROM NkgPollBundle:Opinion o
      WHERE o.poll =:poll_id
       ORDER BY o.votes DESC')
    ->setParameter('poll_id', $poll_id);

    try {
        $res = $opinionz->getResult();
        $array = $this->aggregate($res);
        return $array;
    } catch (\Doctrine\ORM\NoResultException $e) {
        return array();
    }
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
    ->getRepository('NkgPollBundle:Poll')
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
