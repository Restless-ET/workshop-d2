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

  public function createBrandAction(Request $request)
  {
    $carId = $request->query->get('carId');
    $em = $this->get('doctrine.orm.default_entity_manager');

    $brand = new Brand('Honda '.time());
    $car = $em->find('Doctrine\WorkshopBundle\Entity\Car', $carId);
    $brand->addVehicle($car);

    $em->persist($brand);
    $em->flush();

    return new Response('</body>');
  }

  public function showBrandAction(Request $request)
  {
    $id = $request->query->get('id');
    $em = $this->get('doctrine.orm.default_entity_manager');
    $brand = $em->getReference('Doctrine\WorkshopBundle\Entity\Brand', $id);

    // $vehicles instanceof \Doctrine\ORM\PersistentCollection
    $vehicles = $brand->getVehicles();

    echo count($brand->getVehicles()).'<br />';
    $output = $brand->getName().'<br />Vehicles:<br />';
    foreach ($vehicles as $v)
    {
      $output .= $v->getOffer().';<br />';
    }

    return new Response($output.'</body>');
  }

  public function searchAction(Request $request)
  {
    $em = $this->get('doctrine.orm.default_entity_manager');
    $repo = $em->getRepository('Doctrine\WorkshopBundle\Entity\Vehicle');
    //$vehicles = $repo->findBy($request->query->all(), array('price' => 'DESC'), 20, 0);
    //$vehicles = $repo->findBy($request->query->all(), array('price' => 'DESC'), 20, 0);

    $dql = "SELECT v FROM Doctrine\WorkshopBundle\Entity\Vehicle v ORDER BY v.price DESC";
    $vehicles = $em->createQuery($dql)->setFirstResult(0)->setMaxResults(20)->getResult();

    foreach ($vehicles as $vehicle)
    {
      echo $vehicle->getOffer().' for '.$vehicle->getPrice().'<br />';
    }

    return new Response('</body>');
  }

  public function createAction()
  {
    $em = $this->get('doctrine.orm.default_entity_manager');

    $brand = new Brand('Honda '.time());
    //$em->persist($brand); // Not needed due to the cascade behaviour on ManyToOne relation defined on Vehicle class

    for ($i = 1; $i <= 100; $i++)
    {
      $car = new Car(4); // the car age
      $car->setOffer('Honda Civic RC'.$i);
      $car->setPrice(rand(1000, 5000) * ($i / 2));
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

    $brand = $vehicle->getBrand(); // get_class($brand)
    // $brand instanceof \Doctrine\ORM\Proxy\Proxy
    $output .= '<br />Brand: '.$brand->getName();

    // Added </body> to show the web debug toolbar
    return new Response(
      $output.'</body>'
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
