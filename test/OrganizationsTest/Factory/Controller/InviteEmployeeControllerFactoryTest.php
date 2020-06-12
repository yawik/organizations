<?php

/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright https://yawik.org/COPYRIGHT.php
 */

namespace OrganizationsTest\Factory\Controller;

use PHPUnit\Framework\TestCase;

use Interop\Container\ContainerInterface;
use Organizations\Controller\InviteEmployeeController;
use Organizations\Factory\Controller\InviteEmployeeControllerFactory;
use Organizations\Repository\Organization as OrganizationRepository;

/**
 * Class InviteEmployeeControllerFactoryTest
 *
 * @author Anthonius Munthi <me@itstoni.com>
 * @covers \Organizations\Factory\Controller\InviteEmployeeControllerFactory
 * @package OrganizationsTest\Factory\Controller
 * @since 0.30
 */
class InviteEmployeeControllerFactoryTest extends TestCase
{
    public function testInvokation()
    {
        $orgRepo = $this->createMock(OrganizationRepository::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                ['Core/RepositoryService',$container],
                ['Organizations/Organization',$orgRepo]
            ])
        ;
        $factory = new InviteEmployeeControllerFactory();

        $this->assertInstanceOf(
            InviteEmployeeController::class,
            $factory($container, 'some-name')
        );
    }
}
