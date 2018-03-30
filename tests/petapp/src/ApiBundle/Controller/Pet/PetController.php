<?php

namespace PetStore\ApiBundle\Controller\Pet;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PetController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function postAddPetAction(\Common\DTO\Pet $body)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->addPet($body);
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
    public function putUpdatePetAction(\Common\DTO\Pet $body)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->updatePet($body);
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
    public function getGetPetByIdAction(integer $petId)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->getPetById($petId);
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
    public function postUpdatePetWithFormAction(integer $petId)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->updatePetWithForm($petId);
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
    public function deleteDeletePetAction(integer $petId)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->deletePet($petId);
        }
        catch(\Exception $e)
        {

        }
    }

}