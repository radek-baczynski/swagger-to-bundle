<?php

namespace PetStore\ApiBundle\Controller\Pet;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PetUploadImageController extends Controller
{
    /**
	 * @param \Common\DTO\Pet $body
	 *
	 * @ParamConverter("body", converter="api_param_converter")
	 */
    public function postUploadFileAction(integer $petId)
    {
        try
        {
            $handler = $this->get('api.handler_locator')->getHandler('Pet');
            $handler->uploadFile($petId);
        }
        catch(\Exception $e)
        {

        }
    }

}