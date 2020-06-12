<?php
/**
 * YAWIK
 *
 * @filesource
 * @copyright https://yawik.org/COPYRIGHT.php
 * @license       MIT
 */

namespace OrganizationsTest\Factory\Controller;

use PHPUnit\Framework\TestCase;

use Organizations\Factory\Controller\IndexControllerFactory;
use CoreTest\Bootstrap;
use Laminas\Mvc\Controller\ControllerManager;

class IndexControllerFactoryTest extends TestCase
{
    /**
     * @var IndexControllerFactory
     */
    private $testedObj;

    protected function setUp(): void
    {
        $this->testedObj = new IndexControllerFactory();
    }

    public function testInvokation()
    {
        $sm = clone Bootstrap::getServiceManager();
        $sm->setAllowOverride(true);

        $organizationRepositoryMock = $this
            ->getMockBuilder('Organizations\Repository\Organization')
            ->disableOriginalConstructor()
            ->getMock();

        $repositoriesMock = $this
            ->getMockBuilder('Core\Repository\RepositoryService')
            ->disableOriginalConstructor()
            ->getMock();

        $repositoriesMock
            ->expects($this->once())
            ->method('get')
            ->with('Organizations/Organization')
            ->willReturn($organizationRepositoryMock);

        $sm->setService('repositories', $repositoriesMock);

        $result = $this->testedObj->__invoke($sm, 'irrelevant');

        $this->assertInstanceOf('Organizations\Controller\IndexController', $result);
    }
}
