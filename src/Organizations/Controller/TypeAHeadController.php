<?php
/**
 * YAWIK
 *
 * @filesource
 * @copyright (c) 2013 - 2016 Cross Solution (http://cross-solution.de)
 * @license   MIT
 */

namespace Organizations\Controller;

use Core\Controller\AbstractCoreController;
use Organizations\Repository;
use Zend\View\Model\JsonModel;

class TypeAHeadController extends AbstractCoreController
{
    private $organizationRepository;

    public function __construct(Repository\Organization $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function indexAction()
    {
        $data = array();
        $query = $this->params()->fromQuery('q', null);
        /** @var int $userId */
        $user = $this->auth()->getUser();

        if ($query) {
            $result = $this->organizationRepository->getTypeAheadResults($query, $user);

            foreach ($result as $id => $item) {
                $data[] = array(
                    'id' => $id,
                    'name' => $item['organizationName']['name'],
                    'city' => $item['contact']['city'],
                    'street' => $item['contact']['street'],
                    'houseNumber' => $item['contact']['houseNumber']
                );
            }
        }

        return new JsonModel($data);
    }
}
