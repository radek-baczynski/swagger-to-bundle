<?php

namespace PetStore\ApiBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserLogoutController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function getLogoutUserAction()
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('User');
            $handler->logoutUser();
        }
        catch(\Exception $e)
        {

        }
    }

}