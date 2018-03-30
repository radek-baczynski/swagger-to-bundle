<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Category{
    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     *
     */
    protected $id;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $name;

}