<?php

namespace PetStore\ApiBundle\Controller\Store;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class StoreInventoryController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function getGetInventoryAction()
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Store');
            $handler->getInventory();
        }
        catch(\Exception $e)
        {

        }
    }

}