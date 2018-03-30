<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class User{
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
    protected $username;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $email;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $password;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $phone;

    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     *
     */
    protected $userStatus;

}