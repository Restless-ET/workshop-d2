<?php

namespace Doctrine\WorkshopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("car")
 */
class Car extends Vehicle
{
  /** @ORM\Column(type="string") **/
  protected $color;

  public function getColor()
  {
    return $this->color;
  }

  public function setColor($color)
  {
    $this->color = $color;
  }
}