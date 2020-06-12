<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright https://yawik.org/COPYRIGHT.php
 */

namespace OrganizationsTest\Paginator;

use PHPUnit\Framework\TestCase;

use Core\Repository\RepositoryService;
use Doctrine\ODM\MongoDB\Query\Builder;
use Interop\Container\ContainerInterface;
use Organizations\Paginator\ListJobPaginatorFactory;
use Organizations\Repository\Filter\ListJobQuery;
use Organizations\Repository\Organization;
use Laminas\Filter\FilterPluginManager;
use Laminas\Paginator\Paginator;

/**
 * Class ListJobPaginatorFactoryTest
 *
 * @author Anthonius Munthi <me@itstoni.com>
 * @package OrganizationsTest\Paginator
 * @since 0.30
 */
class ListJobPaginatorFactoryTest extends TestCase
{
    public function testInvokation()
    {
        $container      = $this->createMock(ContainerInterface::class);
        $filterManager  = $this->createMock(FilterPluginManager::class);
        $filter         = $this->createMock(ListJobQuery::class);
        $repositories   = $this->createMock(RepositoryService::class);
        $repo           = $this->createMock(Organization::class);
        $builder        = $this->createMock(Builder::class);

        $container->expects($this->any())
            ->method('get')
            ->willReturnMap([
                ['FilterManager',$filterManager],
                ['repositories',$repositories]
            ])
        ;

        $repositories->expects($this->once())
            ->method('get')
            ->with('Jobs/Job')
            ->willReturn($repo)
        ;
        $repo->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($builder)
        ;
        $filterManager->expects($this->once())
            ->method('get')
            ->with('Organizations/ListJobQuery')
            ->willReturn($filter)
        ;

        $target = new ListJobPaginatorFactory();
        $service = $target($container, 'some-name', array());

        $this->assertInstanceOf(Paginator::class, $service);
    }
}
