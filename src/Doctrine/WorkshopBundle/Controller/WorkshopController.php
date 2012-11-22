<?php

namespace Doctrine\WorkshopBundle\Controller;

use Doctrine\WorkshopBundle\Entity\Vehicle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class WorkshopController extends Controller
{
  public function indexAction()
  {
    return $this->render('DoctrineWorkshopBundle:Workshop:index.html.twig', array());
  }

  public function createAction()
  {
    $entityManager = $this->get('doctrine.orm.default_entity_manager');

    $car = new Vehicle();
    $car->setOffer('Honda Civic');
    $car->setPrice(20000);

    $entityManager->persist($car); // ID available from here on PostgreSQL
    $entityManager->flush(); // ID available from here on MySQL

    // Added </body> to show the web debug toolbar
    return new Response('Created: '.$car->getId().'</body>');
  }

  // GET /workshop/show?id=[id]
  public function showAction(Request $request)
  {
    $id = $request->query->get('id');
    $entityManager = $this->get('doctrine.orm.default_entity_manager');

    $vehicle = $entityManager->find('Doctrine\WorkshopBundle\Entity\Vehicle', $id);

    // Added </body> to show the web debug toolbar
    return new Response($vehicle->getOffer().' for '.$vehicle->getPrice()."</body>\n");
  }
}
