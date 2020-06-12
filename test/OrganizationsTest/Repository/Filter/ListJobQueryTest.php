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

use Doctrine\ODM\MongoDB\Query\Builder;
use Interop\Container\ContainerInterface;
use Jobs\Entity\Status;
use Organizations\Entity\Organization;
use Organizations\Repository\Filter\ListJobQuery;

/**
 * Class ListJobQueryTest
 *
 * @author Anthonius Munthi <me@itstoni.com>
 * @package OrganizationsTest\Repository\Filter
 * @covers \Organizations\Repository\Filter\ListJobQuery
 * @since 0.30
 */
class ListJobQueryTest extends TestCase
{
    public function testCreateQuery()
    {
        $builder = $this->createMock(Builder::class);
        $target = new ListJobQuery();
        $organization = new Organization();

        $builder->expects($this->exactly(3))
            ->method('field')
            ->withConsecutive(
                ['organization'],
                ['status.name'],
                ['isDraft']
            )
            ->will($this->returnSelf())
        ;

        $builder->expects($this->once())
            ->method('in')
            ->with([Status::ACTIVE,Status::PUBLISH])
            ->willReturn($builder)
        ;
        $builder->expects($this->exactly(2))
            ->method('equals')
            ->withConsecutive(
                [$organization->getId()],
                [false]
            )
            ->will($this->returnSelf())
        ;


        $params=['organization_id' =>$organization->getId()];
        $target->createQuery($params, $builder);
    }
}
