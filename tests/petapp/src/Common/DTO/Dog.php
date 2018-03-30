<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Dog extends \Common\DTO\Pet
{
    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     *
     */
    protected $packSize;

}