<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfileCreationController extends Controller
{
	/**
	 * @Route("/api/v1/profiles")
	 * @Method({"POST"})
	 */
	public function createProfileAction(Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');
		
		$name = json_decode($request->getContent(), true);
		$name = (empty($name['name']) ? null : $name['name']);
		if (null === $name) {
			return new JsonResponse(array('error' => 'The "name" parameter is missing from the request\'s body _'.$name), 422);
		}
		
		if (null !== $em->getRepository('AppBundle:Profile')->findOneByName($name)) {
			return new JsonResponse(array('error' => 'The name "'.$name.'" is already taken'), 422);
		}
		$createdProfile = new Profile($name);
		$em->persist($createdProfile);
		$em->flush();
		
		return new JsonResponse($createdProfile->toArray(), 201);
	}
}
