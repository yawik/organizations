<?php
/**
 * YAWIK
 *
 * @filesource
 * @license    MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */

/** */
namespace Organizations\Mail;

use Auth\Entity\UserInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * This Factory creates and configures the HTMLTemplateMail send to an invited person.
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @since  0.19
 */
class EmployeeInvitationFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    /**
     * Dynamic options for each invokation.
     *
     * @var array
     */
    protected $options;

    /**
     * Sets creation options.
     *
     * @param array $options
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setCreationOptions(array $options)
    {
        if (!isset($options['user']) || !$options['user'] instanceof UserInterface) {
            throw new \InvalidArgumentException('An user interface is required!');
        }

        if (!isset($options['token']) || !is_string($options['token'])) {
            $options['token'] = false;
        }

        if (!isset($options['template']) || !is_string($options['template'])) {
            $options['template'] = 'organizations/mail/invite-employee';
        }

        $this->options = $options;
    }


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Core\Mail\MailService */
        /* @var $owner \Auth\Entity\UserInterface */
        /* @var $user \Auth\Entity\UserInterface */
        $services = $serviceLocator->getServiceLocator();
        $auth     = $services->get('AuthenticationService');
        $router   = $services->get('Router');

        // we assume here, that the logged in user is the inviter.
        $owner   = $auth->getUser();
        $org     = $owner->getOrganization()->getOrganization();
        $orgName = $org->getOrganizationName()->getName();
        $user    = $this->options['user'];

        $url = $router->assemble(
            array('action' => 'accept'),
            array(
                                     'name'  => 'lang/organizations/invite',
                                     'query' => array(
                                         'token'        => $this->options['token'],
                                         'organization' => $org->getId()
                                     )
                                 )
        );

        $variables = array(
            'inviter'        => $owner->getInfo()->getDisplayName(),
            'organization'   => $orgName,
            'token'          => $this->options['token'],
            'user'           => $user->getInfo()->getDisplayName(/*emailifEmpty*/ false),
            'hasAssociation' => false,
            'url'            => $url,
        );

        if ($user->getOrganization()->hasAssociation()) {
            $variables['hasAssociation']      = true;
            $variables['isOwner']             = $user->getOrganization()->isOwner();
            $variables['currentOrganization'] =
                $user->getOrganization()->getOrganization()->getOrganizationName()->getName();
        }

        $mail = $serviceLocator->get('htmltemplate');
        $mail->setTemplate($this->options['template'])
             ->setVariables($variables)
             ->setSubject(
                 sprintf(
                     /* @translate */ 'Invitation to join the team of %s',
                     $orgName
                 )
             )
             ->addTo($user->getEmail());

        return $mail;
    }
}
