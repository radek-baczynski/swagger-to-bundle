<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class TagCollection{
    /**
     * @var \Common\DTO\Tag
     *
     * @Serializer\Type("Common\DTO\Tag")
     *
     */
    protected $data;
}