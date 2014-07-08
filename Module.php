<?php
/**
 * YAWIK
 * Applications Module Bootstrap
 *
 * @copyright (c) 2013-2014 Cross Solution (http://cross-solution.de)
 * @license   GPLv3
 */

namespace Organizations;

use Zend\Mvc\MvcEvent;


/**
 * Bootstrap class of the applications module
 */
class Module 
{
    /**
     * Loads module specific configuration.
     * 
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
        /**
     * Loads module specific autoloader configuration.
     * 
     * @return array
     */
    public function getAutoloaderConfig()
    {
        
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}