<?php

namespace Common\DTO\Query;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class LoginUserQuery{
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
    protected $password;

}