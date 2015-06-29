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

use nkgPollBundle\Form\Type\PollType;
use nkgPollBundle\Form\Type\OpinionType;

class AdminController extends Controller
{
    //lister les sondages
    /**
     * @Route("/admin/poll/list/" ,name="_adminlist")
     */
    public function listPollAction()
    {
        $pollz = $this->getDoctrine()
        ->getRepository('nkgPollBundle:Poll')
        ->findAll();
        return $this->render('nkgPollBundle:admin:admin.poll.list.html.twig',
        array("pollz"=> $pollz)
        );
    }

    //editer un sondage
    /**
     * @Route("/admin/poll/edit/{poll_id}",name="_editpoll")
     */
    public function editPollAction(Request $request)
    {
        $poll_id = $request->get('poll_id');

        //récupérer l'entité poll
        $poll = $this->getDoctrine()
        ->getRepository('nkgPollBundle:Poll')
        ->find($poll_id);

        //formulaire d'édition du poll
        $form = $this->createForm(new PollType(), $poll);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $poll->setModifiedat(new \DateTime('now'));
          //enregistrer les modifs
          $em->persist($poll);
          $em->flush();
          return new RedirectResponse($this->generateUrl('_adminlist'));
        }

        return $this->render('nkgPollBundle:admin:admin.poll.edit.html.twig',
        array('form' => $form->createView(), "isnew"=>false)
        );
    }

    //creer un nouveau sondage
    /**
     * @Route("/admin/poll/create/",name="_createpoll")
     */
    public function createPollAction(Request $request)
    {
      $poll = new Poll();
      $form = $this->createForm(new PollType(), $poll);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $poll->setCreatedat(new \DateTime('now'));
        $poll->setModifiedat(new \DateTime('now'));

        //enregistrer les modifs
        $em->persist($poll);
        $em->flush();
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
     * @Route("/admin/poll/delete/{poll_id}",name="_deletepoll")
     */
    public function deletePollAction(Request $request)
    {
        $poll_id = $request->get('poll_id');

        $poll = $this->getDoctrine()
        ->getRepository('nkgPollBundle:Poll')
        ->find($poll_id);

        //supprimer le sondage
        $em = $this->getDoctrine()->getManager();
        $em->remove($poll);
        $em->flush();

        return new RedirectResponse($this->generateUrl('_adminlist'));
    }

    //lister les opinions d'un sondage
    /**
     * @Route("/admin/opinion/list/{poll_id}",name="_listopinion")
     */
    public function listOpinionAction(Request $request)
    {
        $poll_id = $request->get('poll_id');
        $session = $request->getSession();
        $session->set("poll_id", $poll_id);

        $opinionz = $this->getDoctrine()
        ->getRepository('nkgPollBundle:Poll')
        ->find($poll_id)
        ->getOpinions();

        return $this->render("nkgPollBundle:admin:admin.opinion.list.html.twig",
        array(
          "opinionz" => $opinionz,
          "poll_id" => $poll_id
          )
        );
    }

    //editer un item d'un sondage
    /**
     * @Route("/admin/opinion/edit/{opinion_id}",name="_editopinion")
     */
    public function editOpinionAction(Request $request)
    {
      $opinion_id = $request->get('opinion_id');

      $opinion = $this->getDoctrine()
      ->getRepository('nkgPollBundle:Opinion')
      ->find($opinion_id);

      $form = $this->createForm(new OpinionType(), $opinion);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($opinion);
        $em->flush();
        $session = $request->getSession();
        $poll_id = $session->get('poll_id');

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
     * @Route("/admin/opinion/create/{poll_id}",name="_createopinion")
     */
    public function createOpinionAction(Request $request)
    {
      $poll_id = $request->get('poll_id');

      $opinion = new Opinion();
      $form = $this->createForm(new OpinionType(), $opinion);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();

        $poll = $this->getDoctrine()
        ->getRepository('nkgPollBundle:Poll')
        ->find($poll_id)
        ->addOpinion($opinion);
        $opinion->setPoll($poll);

        $em->persist($poll);
        $em->persist($opinion);

        $em->flush();

        return new RedirectResponse($this->generateUrl('_listopinion', array("poll_id" =>$poll_id)));
      }

      return $this->render('nkgPollBundle:admin:admin.opinion.edit.html.twig', array(
          'form' => $form->createView(),
          'isnew'=>true
      ));
    }

    //supprimer un sondage
    /**
     * @Route("/admin/opinion/delete/{opinion_id}",name="_deleteopinion")
     */
    public function deleteOpinionAction(Request $request)
    {
      $opinion_id = $request->get('opinion_id');
      $opinion = $this->getDoctrine()
      ->getRepository('nkgPollBundle:Opinion')
      ->find($opinion_id);

      //supprimer l'opinion
      $em = $this->getDoctrine()->getManager();
      $em->remove($opinion);
      $em->flush();

      $session = $request->getSession();
      $poll_id = $session->get('poll_id');

      return new RedirectResponse($this->generateUrl('_listopinion', array("poll_id" =>$poll_id)));
    }

}
