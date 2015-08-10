<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'controllers'=>array(
        'invokables'=>array(
            'Api\Controller\Api'=>'Api\Controller\ApiController',
        ),
    ),
    'router'=>array(
        'routes'=>array(
            'api'=>array(
                'type'=>'segment',
                'options'=>array(
                    'route'=>'/api[/:action][/:id]',
                    'constraints'=>array(
                        'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'=>'[0-9]+',
                    ),
                    'defaults'=>array(
                        'controller'=>'Api\Controller\Api',
                        'action'=>'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager'=>array(
//        'template_path_stack'=>array(
//            'api'=>__DIR__.'/../view',
//        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
