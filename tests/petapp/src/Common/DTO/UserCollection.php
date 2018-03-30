<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class UserCollection{
    /**
     * @var \Common\DTO\User
     *
     * @Serializer\Type("Common\DTO\User")
     *
     */
    protected $data;
}