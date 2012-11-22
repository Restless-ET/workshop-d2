<?php

namespace Doctrine\WorkshopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Truck extends Vehicle
{
  /** @ORM\Column(type="integer") **/
  protected $size;

  public function getSize()
  {
    return $this->size;
  }

  public function setSize($size)
  {
    $this->size = $size;
  }
}