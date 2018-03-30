<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Order{
    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     *
     */
    protected $id;

    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     *
     */
    protected $petId;

    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     *
     */
    protected $quantity;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $shipDate;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $status;

    /**
     * @var boolean
     *
     * @Serializer\Type("boolean")
     *
     */
    protected $complete;

}