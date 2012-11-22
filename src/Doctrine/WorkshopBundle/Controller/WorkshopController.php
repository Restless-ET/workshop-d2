<?php

namespace Doctrine\WorkshopBundle\Controller;

use Doctrine\WorkshopBundle\Entity\Brand;

use Doctrine\WorkshopBundle\Entity\Car;
use Doctrine\WorkshopBundle\Entity\Truck;

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
    $em = $this->get('doctrine.orm.default_entity_manager');

    $brand = new Brand('Honda '.time());
    $em->persist($brand);

    for ($i = 1; $i <= 100; $i++)
    {
      $car = new Car(4); // the car age
      $car->setOffer('Honda Civic RC'.$i);
      $car->setPrice(rand(1000, 10000) * ($i / 2));
      //$car->setAge(3);
      $car->setColor('black');
      $car->setBrand($brand);

      $em->persist($car); // ID available from here on PostgreSQL
    }
    $em->flush(); // ID available from here on MySQL

    // Added </body> to show the web debug toolbar
    return new Response('<a href="/show?id='.$car->getId().'">Created '.$car->getId().'</a></body>');
  }

  // GET /workshop/show?id=[id]
  public function showAction(Request $request)
  {
    $id = $request->query->get('id');
    $entityManager = $this->get('doctrine.orm.default_entity_manager');

    $vehicle = $entityManager->find('Doctrine\WorkshopBundle\Entity\Vehicle', $id);

    $output = $vehicle->getOffer().' for '.$vehicle->getPrice();
    $output .= '<br />Age: '.$vehicle->getAge()." years old!";
    $output .= '<br />Brand: '.$vehicle->getBrand()->getName();

    // Added </body> to show the web debug toolbar
    return new Response(
      $output."</body>\n"
    );
  }

  public function identityMapAction()
  {
    $em = $this->get('doctrine.orm.default_entity_manager');

    $class = 'Doctrine\WorkshopBundle\Entity\Vehicle';
    $objA = $em->find($class, 1);
    $em->clear(); // After this the mapper no longer as internal references to the first object
    //$em->detach($objA); // For clearing specific objects
    $objB = $em->find($class, 1);

    $html = ($objA === $objB) ? 'Same' : 'Not same';
    return new Response(
      $html.'</body>'
    );
  }

  public function updateAction(Request $request)
  {
    $id = $request->query->get('id');
    $em = $this->get('doctrine.orm.default_entity_manager');

    $class = 'Doctrine\WorkshopBundle\Entity\Vehicle';
    $vehicle = $em->find($class, $id);
    $vehicle->setPrice(2 * $vehicle->getPrice());

    //$em->persist($vehicle); // No need for this on updates since doctrine is already aware of the object existance
    $em->flush();

    return new Response(
        $vehicle->getOffer().' for '.$vehicle->getPrice().' ; '.$vehicle->getAge()." years old!</body>\n"
    );

    /*
    return $this->redirect($this->generateUrl('DoctrineWorkshopBundle::show', array(
      'id' => $vehicle->getId()
    )));
    */
  }

  public function deleteAction(Request $request)
  {
    $id = $request->query->get('id');
    $em = $this->get('doctrine.orm.default_entity_manager');

    //$vehicle = $em->find('Doctrine\WorkshopBundle\Entity\Vehicle', $id);
    $vehicle = $em->getReference('Doctrine\WorkshopBundle\Entity\Car', $id);

    $em->remove($vehicle);
    $em->flush();

    // Added </body> to show the web debug toolbar
    return new Response('</body>');
  }
}
