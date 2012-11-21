<?php

namespace Doctrine\WorkshopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $reflection = new \ReflectionClass('Doctrine\WorkshopBundle\Controller\WorkshopController');
        $routeNames = array();

        foreach ($reflection->getMethods() as $method) {
            if (substr($method->getName(), -6) == "Action") {
                $routeNames[] = strtolower(str_replace("Action", "", $method->getName()));
            }
        }

        return $this->render('DoctrineWorkshopBundle:Default:index.html.twig', array(
            'routeNames' => $routeNames,
        ));
    }

    public function migrateAction()
    {
        $entityManager = $this->get('doctrine.orm.default_entity_manager');
        $isSqlite      = $entityManager->getConnection()->getDatabasePlatform()->getName() === 'sqlite';
        $schemaTool    = new \Doctrine\ORM\Tools\SchemaTool($entityManager);

        if ($isSqlite) {
            @unlink($this->container->getParameter('kernel.root_dir') . '/workshop.db');
            $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());
        } else {
            $schemaTool->updateSchema($entityManager->getMetadataFactory()->getAllMetadata());
        }

        return $this->redirect($this->generateUrl('homepage'));
    }
}


