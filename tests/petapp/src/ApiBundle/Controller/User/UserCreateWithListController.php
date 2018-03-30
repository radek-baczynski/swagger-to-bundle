<?php

namespace PetStore\ApiBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserCreateWithListController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function postCreateUsersWithListInputAction(\Common\DTO\UserCollection $body)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('User');
            $handler->createUsersWithListInput($body);
        }
        catch(\Exception $e)
        {

        }
    }

}