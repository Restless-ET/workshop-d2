<?php

namespace Doctrine\WorkshopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("brand")
 */
class Brand
{
  /** @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue **/
  protected $id;

  /** @ORM\Column(type="string") **/
  protected $name;

  /** @ORM\Column(type="datetime") **/
  protected $created_at;

  /** @ORM\OneToMany(targetEntity="Vehicle", mappedBy="brand") **/
  protected $vehicles;

  public function __construct($name)
  {
    $this->name = $name;
    $this->vehicles = new ArrayCollection();
    $this->created_at = new \DateTime();
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }
}
