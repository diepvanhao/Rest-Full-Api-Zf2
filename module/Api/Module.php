<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Api;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
 use Api\Model\Post;
 use Api\Model\PostTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface {
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader'=>array(
                __DIR__.'/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader'=>array(
                'namespaces'=>array(
                    __NAMESPACE__=>__DIR__.'/src/'.__NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__.'/config/module.config.php';
    }
    
public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Api\Model\PostTable' =>  function($sm) {
                     $tableGateway = $sm->get('Zend\Db\Adapter\Adapter');
                     $table = new PostTable($tableGateway);
                     return $table;
                 },
//                 'ApiTableGateway' => function ($sm) {
//                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                     $resultSetPrototype = new ResultSet();
//                     $resultSetPrototype->setArrayObjectPrototype(new Api());
//                     return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
//                 },
             ),
         );
     }

}
