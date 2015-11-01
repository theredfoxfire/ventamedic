<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SubmitJsonListener
{
	/**
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		$request = $event->getRequest();
		$hasBeenSubmited = in_array($request->getMethod(), array('POST', 'PUT'), true);
		$isJson = ('application/json' === $request->headers->get('Content-Type'));
		if (JSON_ERROR_NONE !== json_last_error()) {
			$event->setResponse(new JsonResponse(array('error' => 'Invalid or malformed JSON'), 400));
		}
		$request->request->add($data ?: array());
	}
}
