<?php
// api/src/Subscriber/HuwelijkAddPartnerSubscriber.php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Templating\EngineInterface;
use App\Entity\Sjabloon;

final class SjabloonRenderSubscriber implements EventSubscriberInterface
{
	private $params;
	private $entityManager;
	private $serializer;
	private $templating;
	
	public function __construct(ParameterBagInterface $params, EntityManagerInterface $entityManager, SerializerInterface $serializer, \Twig_Environment $templating)
	{
		$this->params = $params;
		$this->entityManager= $entityManager;
		$this->serializer= $serializer;
		$this->templating= $templating;
	}
	
	public static function getSubscribedEvents()
	{
		return [
				KernelEvents::VIEW => ['sjabloon', EventPriorities::PRE_VALIDATE]
		];
	}
	
	public function sjabloon(GetResponseForControllerResultEvent $event)
	{
		$sjabloon = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();
				
		// Lats make sure that some one posts correctly
		if (!$sjabloon instanceof Sjabloon|| Request::METHOD_GET !== $method || $event->getRequest()->get('_route') != 'api_sjabloons_render_item') {
			return;
		}
				
		$titel = $this->templating->createTemplate($sjabloon->getTitel());
		$inhoud = $this->templating->createTemplate($sjabloon->getInhoud());
		
		// Then we need to render the templates		
		$sjabloon->setVariabelen([]);
		
		$sjabloon->setTitel($titel->render($sjabloon->getVariabelen()));
		$sjabloon->setInhoud($inhoud->render($sjabloon->getVariabelen()));
		
		//var_dump($sjabloon->getTitel());
		
		$json = $this->serializer->serialize(
				$sjabloon,
				'jsonld',['enable_max_depth' => true,'groups' => 'sjabloon:weergeven']
				);
		
		$response = new Response(
			$json,
			Response::HTTP_OK,
			['content-type' => 'application/json+ld']
			);
		
		$event->setResponse($response);
		
		return;
	}
	
}