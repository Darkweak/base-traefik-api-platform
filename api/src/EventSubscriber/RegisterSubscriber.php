<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterSubscriber implements EventSubscriberInterface
{
	private $encoder;
	private $manager;

	public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
		$this->manager = $manager;
	}
	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::VIEW => ['registerUserSubscriber', EventPriorities::POST_WRITE],
		];
	}
	public function registerUserSubscriber(GetResponseForControllerResultEvent $event)
	{
		$user = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();
		if (!($user instanceof User) || Request::METHOD_POST !== $method){
			return;
		}
		$user
			->setPassword($this->encoder->encodePassword($user, $user->getPassword()))
			->setRoles(['ROLE_USER']);
		$this->manager->persist($user);
		$this->manager->flush();
	}
}
