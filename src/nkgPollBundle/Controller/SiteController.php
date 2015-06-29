<?php

namespace nkgPollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use nkgPollBundle\Entity\Poll;
use nkgPollBundle\Entity\Opinion;

class SiteController extends Controller
{
    //lister les sondages
    /**
     * @Route("/site")
     * @Template()
     */
    public function listPollAction()
    {
        return array('name' => $name);
    }

    public function voteAction()
    {
        return array('name' => $name);
    }
}
