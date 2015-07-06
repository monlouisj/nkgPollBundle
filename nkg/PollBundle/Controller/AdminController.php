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

use nkg\PollBundle\Form\Type\PollType;
use nkg\PollBundle\Form\Type\OpinionType;

class AdminController extends Controller
{
    //lister les sondages
    /**
     * @Route("/polladmin/poll/list/" ,name="_adminlist")
     */
    public function listPollAction()
    {
        $nkgPoll = $this->get('nkg.poll');
        $pollz = $nkgPoll->getAllPolls();

        return $this->render('nkgPollBundle:admin:admin.poll.list.html.twig',
        array("pollz"=> $pollz)
        );
    }

    //editer un sondage
    /**
     * @Route("/polladmin/poll/edit/{poll_id}",name="_editpoll")
     */
    public function editPollAction(Request $request)
    {
        $poll_id = $request->get('poll_id');

        //récupérer l'entité poll
        $nkgPoll = $this->get('nkg.poll');
        $poll = $nkgPoll->getPoll($poll_id);

        //formulaire d'édition du poll
        $form = $this->createForm(new PollType(), $poll);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $nkgPoll->savePoll($poll);
          return new RedirectResponse($this->generateUrl('_adminlist'));
        }

        return $this->render('nkgPollBundle:admin:admin.poll.edit.html.twig',
        array('form' => $form->createView(), "isnew"=>false)
        );
    }

    //creer un nouveau sondage
    /**
     * @Route("/polladmin/poll/create/",name="_createpoll")
     */
    public function createPollAction(Request $request)
    {
      $nkgPoll = $this->get('nkg.poll');

      $poll = new Poll();
      $form = $this->createForm(new PollType(), $poll);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        //enregistrer les modifs
        $poll->setCreatedat(new \DateTime('now'));
        $nkgPoll->savePoll($poll);

        $poll_id = $poll->getId();
        //aller à la création d'opinion
        return new RedirectResponse($this->generateUrl('_listopinion', array("poll_id" =>$poll_id)));
      }

      return $this->render('nkgPollBundle:admin:admin.poll.edit.html.twig',
      array('form' => $form->createView(), "isnew"=>true)
      );
    }

    //supprimer un sondage
    /**
     * @Route("/polladmin/poll/delete/{poll_id}",name="_deletepoll")
     */
    public function deletePollAction(Request $request)
    {
        $poll_id = $request->get('poll_id');
        $nkgPoll = $this->get('nkg.poll');

        //supprimer le sondage
        $nkgPoll->deletePoll($poll_id);

        return new RedirectResponse($this->generateUrl('_adminlist'));
    }

    //lister les opinions d'un sondage
    /**
     * @Route("/polladmin/opinion/list/{poll_id}",name="_listopinion")
     */
    public function listOpinionAction(Request $request)
    {
        $poll_id = $request->get('poll_id');
        $session = $request->getSession();
        $session->set("poll_id", $poll_id);

        $nkgOpinion = $this->get('nkg.opinion');

        $opinionz = $nkgOpinion->getOpinions($poll_id);

        return $this->render("nkgPollBundle:admin:admin.opinion.list.html.twig",
        array(
          "opinionz" => $opinionz,
          "poll_id" => $poll_id
          )
        );
    }

    //editer un item d'un sondage
    /**
     * @Route("/polladmin/opinion/edit/{opinion_id}",name="_editopinion")
     */
    public function editOpinionAction(Request $request)
    {
      $opinion_id = $request->get('opinion_id');

      $nkgOpinion = $this->get('nkg.opinion');

      $opinion = $nkgOpinion->getOpinion($opinion_id);

      $form = $this->createForm(new OpinionType(), $opinion);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $nkgOpinion->saveOpinion($opinion);

        $poll_id = $opinion->getPoll()->getId();

        return new RedirectResponse($this->generateUrl('_listopinion', array("poll_id" =>$poll_id)));
      }

      return $this->render("nkgPollBundle:admin:admin.opinion.edit.html.twig",
      array(
        'form' => $form->createView(),
        'isnew' => false
        )
      );
    }

    //creer un nouvel item dans un sondage
    /**
     * @Route("/polladmin/opinion/create/{poll_id}",name="_createopinion")
     */
    public function createOpinionAction(Request $request)
    {
      $poll_id = $request->get('poll_id');
      $nkgPoll = $this->get('nkg.poll');

      $opinion = new Opinion();
      $form = $this->createForm(new OpinionType(), $opinion);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $nkgPoll->addOpinion($poll_id,$opinion);
        return new RedirectResponse($this->generateUrl('_listopinion', array("poll_id" =>$poll_id)));
      }

      return $this->render('nkgPollBundle:admin:admin.opinion.edit.html.twig', array(
          'form' => $form->createView(),
          'isnew'=>true
      ));
    }

    //supprimer une opinion
    /**
     * @Route("/polladmin/opinion/delete/{opinion_id}",name="_deleteopinion")
     */
    public function deleteOpinionAction(Request $request)
    {
      $opinion_id = $request->get('opinion_id');
      $nkgOpinion = $this->get('nkg.opinion');

      //supprimer l'opinion
      $nkgOpinion->deleteOpinion($opinion_id);

      $session = $request->getSession();
      $poll_id = $session->get('poll_id');

      return new RedirectResponse($this->generateUrl('_listopinion', array("poll_id" =>$poll_id)));
    }

}
