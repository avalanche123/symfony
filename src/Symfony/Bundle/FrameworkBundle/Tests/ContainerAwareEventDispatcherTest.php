<?php

namespace Symfony\Bundle\FrameworkBundle\Tests;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\DependencyInjection\Scope;

class ContainerAwareEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAListenerService()
    {
        $event = new Event();

        $service = $this->getMock('Symfony\Bundle\FrameworkBundle\Tests\Service');

        $service
            ->expects($this->once())
            ->method('onEvent')
            ->with($event)
        ;

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', 'service.listener');

        $dispatcher->dispatch('onEvent', $event);
    }

    public function testPreventDuplicateListenerService()
    {
        $event = new Event();

        $service = $this->getMock('Symfony\Bundle\FrameworkBundle\Tests\Service');

        $service
            ->expects($this->once())
            ->method('onEvent')
            ->with($event)
        ;

        $container = new Container();
        $container->set('service.listener', $service);

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', 'service.listener', 5);
        $dispatcher->addListenerService('onEvent', 'service.listener', 10);

        $dispatcher->dispatch('onEvent', $event);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTriggerAListenerServiceOutOfScope()
    {
        $service = $this->getMock('Symfony\Bundle\FrameworkBundle\Tests\Service');

        $scope = new Scope('scope');

        $container = new Container();
        $container->addScope($scope);
        $container->enterScope('scope');
        $container->set('service.listener', $service, 'scope');

        $dispatcher = new ContainerAwareEventDispatcher($container);
        $dispatcher->addListenerService('onEvent', 'service.listener');

        $container->leaveScope('scope');
        $dispatcher->dispatch('onEvent');        
    }
}

class Service
{
    function onEvent(Event $e)
    {        
    }
}