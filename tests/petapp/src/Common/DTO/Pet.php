<?php

namespace Common\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\Discriminator(
 *  field="petType",
 *   map = {
 *      "Cat": "Common\DTO\Cat","Dog": "Common\DTO\Dog"
 *   }
 * )
 */
class Pet{
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
     * @Assert\NotBlank()
     * @Assert\Choice(choices={ "Cat","Dog" })
     *
     */
    protected $petType;

    /**
     * @var \Common\DTO\Category
     *
     * @Serializer\Type("Common\DTO\Category")
     *
     */
    protected $category;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $name;

    /**
     * @var string[]
     *
     * @Serializer\Type("array<string>")
     *
     */
    protected $photoUrls;

    /**
     * @var \Common\DTO\TagCollection
     *
     * @Serializer\Type("Common\DTO\TagCollection")
     *
     */
    protected $tags;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     */
    protected $status;

}