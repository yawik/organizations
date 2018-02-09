<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */

namespace OrganizationsTest\Repository\Filter;

use Auth\AuthenticationService;
use Auth\Entity\User;
use Doctrine\MongoDB\Query\Builder as QueryBuilder;
use Doctrine\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\Cursor;
use Jobs\Entity\StatusInterface;
use Jobs\Repository\Job as JobRepository;
use Organizations\Entity\Organization;
use Organizations\Repository\Filter\PaginationQuery;
use Zend\Stdlib\Parameters;

/**
 * Class PaginationQueryTest
 *
 * @author Anthonius Munthi <me@itstoni.com>
 * @since 0.30
 * @package OrganizationsTest\Repository\Filter
 * @covers \Organizations\Repository\Filter\PaginationQuery
 */
class PaginationQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaginationQuery
     */
    protected $target;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $auth;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $jobRepository;

    public function setUp()
    {
        $this->auth = $this->createMock(AuthenticationService::class);
        $this->jobRepository = $this->createMock(JobRepository::class);

        $user = $this->createMock(User::class);
        $this->auth->expects($this->any())
            ->method('getUser')
            ->willReturn($user);

        $user->expects($this->any())
            ->method('getId')
            ->willReturn('some-id')
        ;


        $this->target = new PaginationQuery($this->auth,$this->jobRepository);
    }

    public function testCreateQuery()
    {
        $builder = $this->createMock(QueryBuilder::class);
        $target = $this->target;

        $builder->expects($this->any())
            ->method('field')
            ->willReturnMap([
                ['permissions.view',$builder]
            ])
        ;

        $builder->expects($this->once())
            ->method('text')
            ->with('some text')
            ->willReturn($builder)
        ;

        $builder->expects($this->once())
            ->method('sort')
            ->with(['dateCreated.date'=>-1])
        ;

        $params = [
            'q' => 'some text'
        ];
        $target->createQuery($params,$builder);
    }

    public function testCreateQueryForProfile()
    {
        $builder = $this->createMock(QueryBuilder::class);
        $target = $this->target;
        $jobRepository = $this->jobRepository;
        $query = $this->createMock(Query::class);

        $builder->expects($this->any())
            ->method('field')
            ->willReturnMap([
                ['permissions.view',$builder],
                ['profileSetting',$builder],
                ['id',$builder],
                ['organization',$builder],
                ['status.name',$builder],
                ['isDraft',$builder],
                ['profileSetting',$builder]
            ])
        ;

        $builder->expects($this->exactly(2))
            ->method('notIn')
            ->willReturnMap([
                [ [StatusInterface::EXPIRED, StatusInterface::INACTIVE], $builder],
                [ ['some-id'],$builder ]
            ])
        ;

        $builder->expects($this->any())
            ->method('equals')
            ->with($this->anything())
            ->willReturn($builder)
        ;

        $organization = new Organization();
        $organization->setId('some-id');
        $organization->setProfileSetting(Organization::PROFILE_ACTIVE_JOBS);

        $builder->expects($this->any())
            ->method('getQuery')
            ->willReturn($query)
        ;

        $results = $this->createMock(Cursor::class);

        $query->expects($this->any())
            ->method('execute')
            ->willReturn($results)
        ;
        $results->expects($this->any())
            ->method('toArray')
            ->willReturn([$organization])
        ;

        $results->expects($this->once())
            ->method('count')
            ->willReturn(0)
        ;

        $jobRepository->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($builder)
        ;
        $params = new Parameters([
            'type' => 'profile'
        ]);
        $target->createQuery($params,$builder);
    }
}
