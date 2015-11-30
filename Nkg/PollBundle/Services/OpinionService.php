<?php
namespace Nkg\PollBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityManager;

use Nkg\PollBundle\Entity\Poll;
use Nkg\PollBundle\Entity\PollRepository;
use Nkg\PollBundle\Entity\Opinion;
use Nkg\PollBundle\Entity\OpinionRepository;

class OpinionService
{
  protected $em;

  public function __construct(EntityManager $em) {
      $this->em = $em;
  }

  //supprimer une opinion
  public function deleteOpinion($opinion_id)
  {
    $opinion = $this->em
    ->getRepository('NkgPollBundle:Opinion')
    ->find($opinion_id);
    $this->em->remove($opinion);
    $this->em->flush();
  }

  //lister les opinions d'un sondage actif
  public function getVotableOpinions($poll_id)
  {
    $opinionz = $this->em
    ->getRepository('NkgPollBundle:Poll')
    ->findOneBy(array(
      "id" => (int)$poll_id,
      "active" => 1
      )
    )->getOpinions();

    return $opinionz;
  }

  //trouver 1 Opinion
  public function getOpinion($opinion_id)
  {
    $opinion = $this->em
    ->getRepository('NkgPollBundle:Opinion')
    ->find($opinion_id);

    return $opinion;
  }

  //lister les items d'un sondage
  public function getOpinions($poll_id)
  {
    $opinionz = $this->em
    ->getRepository('NkgPollBundle:Poll')
    ->find($poll_id)
    ->getOpinions();

    return $opinionz;
  }

  //voter pour une opinion
  public function addVote($opinion_id)
  {
    $opinion = $this->em
    ->getRepository('NkgPollBundle:Opinion')
    ->find($opinion_id);

    //ajouter un vote
    $opinion->addVote();
    //marquer la date du dernier vote
    $opinion->setLastvoteat( new \DateTime('now'));

    // $this->em->persist($opinion);
    // $this->em->flush();
    $this->saveOpinion($opinion);
    return $opinion;
  }

  //sauvegarder 1 opinion
  public function saveOpinion($opinion)
  {
    //enregistrer les modifs
    $this->em->persist($opinion);
    $this->em->flush();
    $this->em->clear();

    return $opinion;
  }

}
