<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Cat extends \Common\DTO\Pet
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $huntingSkill;

}