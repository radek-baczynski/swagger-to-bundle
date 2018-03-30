<?php

namespace Common\DTO\Query;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class FindPetsByTagsQuery{
    /**
     * @var string[]
     *
     * @Serializer\Type("array<string>")
     *
     */
    protected $tags;

}