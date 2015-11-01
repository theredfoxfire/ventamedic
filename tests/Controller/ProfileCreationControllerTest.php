<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;

class ProfileCreationControllerTest extends \PHPUnit_Framework_TestCase
{
	private $app;
	private $em;
	
	protected function setUp()
	{
		$this->app = new \AppKernel('test', true);
		$this->app->boot();
		
		$this->em = $this->app->getContainer()->get('doctrine.orm.entity_manager');
		$this->em->beginTransaction();
	}
	
	public function testItCreatesProfiles()
	{
		$headers = array(
			'CONTENT_TYPE' => 'application/json',
			'PHP_AUTH_USER' => 'ventamedic',
			'PHP_AUTH_PW' => 'VentaMedic',
		);
		$body = json_encode(array('name' => 'Venta Medic'));
		$request = Request::create('/api/v1/profiles', 'POST', array(), array(), array(), $headers, $body);
		
		$response = $this->app->handle($request);
		
		$this->assertSame(201, $response->getStatusCode(), $response->getContent());
	}
	
	public function testItFailsIfNameIsMissing()
	{
		$headers = array(
			'CONTENT_TYPE' => 'aplliaction/json',
			'PHP_AUTH_USER' => 'ventamedic',
			'PHP_AUTH_PW' => 'VentaMedic',
		);
		$body = json_encode(array('no-name' => ''));
		$request = Request::create('/api/v1/profiles', 'POST', array(), array(), array(), $headers, $body);
		
		$response = $this->app->handle($request);
		
		$this->assertSame(422, $response->getStatusCode(), $response->getContent());
	}
	
	public function testItFailsIfNameAlreadyExists()
	{
		$headers = array(
			'CONTENT_TYPE' => 'application/json',
			'PHP_AUTH_USER' => 'ventamedic',
			'PHP_AUTH_PW' => 'VentaMedic',
		);
		$body = json_encode(array('name' => 'Leonardo Dannish'));
		$request = Request::create('/api/v1/profiles', 'POST', array(), array(), array(), $headers, $body);
		
		$this->app->handle($request);
		$response = $this->app->handle($request);
		
		$this->assertSame(422, $response->getStatusCode(), $response->getContent());
	}
	
	protected function tearDown()
	{
		$this->em->rollback();
		$this->em->close();
	}
}
