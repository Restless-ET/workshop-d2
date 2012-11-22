<?php

namespace Doctrine\WorkshopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("vehicle")
 */
class Vehicle
{
  /** @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue **/
  protected $id;
  /** @ORM\Column(type="string") **/
  protected $offer;
  /** @ORM\Column(type="integer") **/
  protected $price;
  /** @ORM\Column(type="integer") **/
  protected $age;

  public function getId()
  {
    return $this->id;
  }

  public function getOffer()
  {
    return $this->offer;
  }

  public function setOffer($offer)
  {
    $this->offer = $offer;
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function setPrice($price)
  {
    $this->price = $price;
  }

  public function getAge()
  {
    return $this->age;
  }

  public function setAge($age)
  {
    $this->age = $age;
  }
}
