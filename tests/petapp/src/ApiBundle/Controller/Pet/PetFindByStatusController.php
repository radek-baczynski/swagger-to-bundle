<?php

namespace PetStore\ApiBundle\Controller\Pet;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PetFindByStatusController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function getFindPetsByStatusAction(\Common\DTO\Query\FindPetsByStatusQuery $query)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->findPetsByStatus($query);
        }
        catch(\Exception $e)
        {

        }
    }

}