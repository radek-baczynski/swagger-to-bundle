<?php

namespace PetStore\ApiBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function postCreateUserAction(\Common\DTO\User $body)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('User');
            $handler->createUser($body);
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
    public function getGetUserByNameAction(string $username)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('User');
            $handler->getUserByName($username);
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
    public function putUpdateUserAction(string $username, \Common\DTO\User $body)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('User');
            $handler->updateUser($username, $body);
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
    public function deleteDeleteUserAction(string $username)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('User');
            $handler->deleteUser($username);
        }
        catch(\Exception $e)
        {

        }
    }

}