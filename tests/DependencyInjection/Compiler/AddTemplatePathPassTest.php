<?php

namespace Knp\Bundle\MenuBundle\Tests\DependencyInjection\Compiler;

use Knp\Bundle\MenuBundle\DependencyInjection\Compiler\AddTemplatePathPass;
use PHPUnit\Framework\TestCase;

class AddTemplatePathPassTest extends TestCase
{
    public function testProcessWithoutProviderDefinition()
    {
        $containerBuilder = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')->getMock();
        $containerBuilder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(false));
        $containerBuilder->expects($this->never())
            ->method('getDefinition');

        $templatePathPass = new AddTemplatePathPass();

        $templatePathPass->process($containerBuilder);
    }

    public function testProcess()
    {
        $definitionMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()
            ->getMock();
        $definitionMock->expects($this->once())
            ->method('addMethodCall')
            ->with($this->equalTo('addPath'), $this->isType('array'));

        $containerBuilderMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')->getMock();
        $containerBuilderMock->expects($this->once())
            ->method('hasDefinition')
            ->will($this->returnValue(true));
        $containerBuilderMock->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('twig.loader.filesystem'))
            ->will($this->returnValue($definitionMock));

        $templatePathPass = new AddTemplatePathPass();
        $templatePathPass->process($containerBuilderMock);
    }
}
