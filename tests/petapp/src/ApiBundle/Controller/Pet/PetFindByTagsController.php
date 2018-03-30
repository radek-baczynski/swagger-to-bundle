<?php

namespace PetStore\ApiBundle\Controller\Pet;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PetFindByTagsController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function getFindPetsByTagsAction(\Common\DTO\Query\FindPetsByTagsQuery $query)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->findPetsByTags($query);
        }
        catch(\Exception $e)
        {

        }
    }

}