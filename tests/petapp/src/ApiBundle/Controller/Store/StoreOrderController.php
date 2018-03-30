<?php

namespace PetStore\ApiBundle\Controller\Store;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class StoreOrderController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function postPlaceOrderAction(\Common\DTO\Order $body)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Store');
            $handler->placeOrder($body);
        }
        catch(\Exception $e)
        {

        }
    }

    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function getGetOrderByIdAction(integer $orderId)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Store');
            $handler->getOrderById($orderId);
        }
        catch(\Exception $e)
        {

        }
    }

    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function deleteDeleteOrderAction(integer $orderId)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Store');
            $handler->deleteOrder($orderId);
        }
        catch(\Exception $e)
        {

        }
    }

}