<?php

namespace Common\DTO\Query;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class FindPetsByStatusQuery{
    /**
     * @var string[]
     *
     * @Serializer\Type("array<string>")
     *
     */
    protected $status;

}