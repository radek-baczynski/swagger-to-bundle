<?php

namespace PetStore\ApiBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserLoginController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function getLoginUserAction(\Common\DTO\Query\LoginUserQuery $query)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('User');
            $handler->loginUser($query);
        }
        catch(\Exception $e)
        {

        }
    }

}