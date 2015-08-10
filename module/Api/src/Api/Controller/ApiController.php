<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Api\Model\Post;
use Api\Form\ApiForm;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractActionController {

    protected $postTable;

    public function indexAction() {
        
        //return new ViewModel();
        //var_dump($this->params());die();
        return new JsonModel(array(
            'data' =>array('error'=>false,'value'=>$this->getPostTable()->fetchAll()),
        ));
    }
    public function getpostAction(){
        $request = $this->getRequest();
       
        $params=$this->params()->fromPost();
        
        if ($request->isPost()) {
                        
             $result= $this->getPostTable()->getTagPost($params);

               
                return new JsonModel(array(
                     'data' =>array('error'=>false,'value'=>$result),
                ));
            
        }else{
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Can not get post by tag.'),
                ));
        }
        
    
    }
    public function countpostAction(){
        $request = $this->getRequest();
       
        $params=$this->params()->fromPost();
        
        if ($request->isPost()) {
                        
             $result= $this->getPostTable()->getCountPost($params);

               
                return new JsonModel(array(
                     'data' =>array('error'=>false,'value'=>$result[0]['total']),
                ));
            
        }else{
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Can not get post by tag.'),
                ));
        }
    }
    public function addAction() {

        $request = $this->getRequest();
       
        $params=$this->params()->fromPost();
        
        if ($request->isPost()) {
                        
             $result= $this->getPostTable()->savePost($params);

               
                return new JsonModel(array(
                     'data' =>array('error'=>false,'value'=>$result),
                ));
            
        }else{
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Can not create.'),
                ));
        }
        
    }

    public function editAction() {
        $request = $this->getRequest();
        $params=$this->params()->fromPost();
        $postId = (int) $this->params()->fromRoute('id', 0);
        if (!$postId) {
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Not found.'),
                ));
        }

        // Get the Api with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $post = $this->getPostTable()->getPost($postId);
            
        } catch (\Exception $ex) {
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Not found.'),
                ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
                $params['id']=$postId;
               $result= $this->getPostTable()->savePost($params);

                return new JsonModel(array(
                     'data' =>array('error'=>false,'value'=>$result),
                ));
            
        }else{
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Wrong route.'),
                ));
        }

       
    }

    public function deleteAction() {

        $postId = (int) $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
               
        if (!$postId) {
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Not found.'),
                ));
        }
        try {
            $post = $this->getPostTable()->getPost($postId);
        } catch (\Exception $ex) {
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'Not found.'),
                ));
        }
        $request = $this->getRequest();
        if ($request->isPost()) {   
            //save log before delete
               $this->getPostTable()->saveLog($post);
               $result= $this->getPostTable()->deletePost($postId);
            // Redirect to list of apis
            return new JsonModel(array(
                     'data' =>array('error'=>false,'value'=>$result),
                ));
        }else{
            return new JsonModel(array(
                     'data' =>array('error'=>true,'value'=>'wrong route.'),
                ));
        }

        
    }

    public function getPostTable() {
        if (!$this->postTable) {
            $sm = $this->getServiceLocator();
            $this->postTable = $sm->get('Api\Model\PostTable');
        }
        return $this->postTable;
    }

}
