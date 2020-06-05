<?php
/**
 * YAWIK
 *
 * @filesource
 * @copyright https://yawik.org/COPYRIGHT.php
 * @license   MIT
 * @author    Mathias Weitz <weitz@cross-solution.de>
 */

namespace Organizations\Form;

use Laminas\Form\Fieldset;

/**
 * Class OrganizationsFieldset
 * @package Organizations\Form
 */
class OrganizationsFieldset extends Fieldset
{
    /**
     *
     */
    public function init()
    {
        $this->setName('organization');
        $this->add(array('type' => 'Organizations/ContactFieldset'));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }

    /**
     * @param object $object
     * @return $this|Fieldset|\Laminas\Form\FieldsetInterface
     */
    public function setObject($object)
    {
        parent::setObject($object);
        return $this;
    }
}
