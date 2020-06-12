<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright https://yawik.org/COPYRIGHT.php
 */

namespace OrganizationsTest\Repository\Filter;

use PHPUnit\Framework\TestCase;

use Auth\AuthenticationService;
use Interop\Container\ContainerInterface;
use Organizations\Repository\Filter\PaginationQuery;
use Organizations\Repository\Filter\PaginationQueryFactory;
use Jobs\Repository\Job as JobRepository;

/**
 * Class PaginationQueryFactoryTest
 *
 * @author Anthonius Munthi <me@itstoni.com>
 * @since 0.30
 * @covers  \Organizations\Repository\Filter\PaginationQueryFactory
 * @package OrganizationsTest\Repository\Filter
 */
class PaginationQueryFactoryTest extends TestCase
{
    public function testInvokation()
    {
        $jobRepository = $this->createMock(JobRepository::class);
        $auth = $this->createMock(AuthenticationService::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->any())
            ->method('get')
            ->willReturnMap([
                ['Core/RepositoryService',$container],
                ['Jobs/Job',$jobRepository],
                ['AuthenticationService',$auth]
            ])
        ;

        $factory = new PaginationQueryFactory($auth, $jobRepository);
        $this->assertInstanceOf(
            PaginationQuery::class,
            $factory($container, 'some-name')
        );
    }
}
