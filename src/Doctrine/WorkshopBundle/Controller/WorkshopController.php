<?php

namespace Doctrine\WorkshopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class WorkshopController extends Controller
{
    public function indexAction()
    {
        return $this->render('DoctrineWorkshopBundle:Workshop:index.html.twig', array());
    }
}


