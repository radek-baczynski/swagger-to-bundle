<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 17/08/17
 * Time: 21:08
 */

namespace Apigen\ApiSupportBundle\Event;


use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class JsonContentListener
{
	public function onKernelRequest(GetResponseEvent $event)
	{
		if (!$event->isMasterRequest())
		{
			return;
		}

		if ($event->getRequest()->getContentType() == 'json')
		{
			$request = $event->getRequest();
			$data    = json_decode($request->getContent(), true);
			$request->request->replace(is_array($data) ? $data : []);
		}
	}
}