<?php
/**
 * Test Ajax Module
 *
 * @link      http://github.com/samsonasik/TestAjax for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Samsonasik (http://samsonasik.wordpress.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TestAjax\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use TestAjax\Model\TestEntity;

class SkeletonController extends AbstractActionController
{
    //the indexAction has link only, no code need in here...
    public function indexAction()
    {
        return array();
    }
    
    // get a form
    protected function getForm()
    {
        $builder    = new AnnotationBuilder();
        $entity     = new TestEntity();
        $form       = $builder->createForm($entity);
        
        return $form;
    }
    
    //save to db ...
    protected function savetodb($data)
    {
        //common code save to save to db ....
    }
    
   //it call in dialog if request is XmlHttpRequest
    public function showformAction()
    {
        $viewmodel = new ViewModel();
        $form       = $this->getForm();

        $request = $this->getRequest();
        
        $is_xmlhttprequest = 1;
        if ($request->isXmlHttpRequest()){
            //disable layout for ajax, because it's a modal dialog
            $viewmodel->setTerminal(true);
        } else {
            $is_xmlhttprequest = 0;
            //if NOT using Ajax
            if ($request->isPost()){
                $form->setData($request->getPost());
                if ($form->isValid()){
                    //save to db ;)
                    $this->savetodb($form->getData());
                }
            }
        }
        
        $viewmodel->setVariables(array(
                    'form' => $form,
                    'is_xmlhttprequest' => $is_xmlhttprequest //need for check this form is in modal dialog or not
        ));
        
        return $viewmodel;
    }
    
   //this is call if request is xmlHttpRequest
    public function validatepostajaxAction()
    {
        $form    = $this->getForm();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $messages = array();
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ( ! $form->isValid()) {
                $errors = $form->getMessages();
                foreach($errors as $key=>$row)
                {
                    if (!empty($row) && $key != 'submit') {
                        foreach($row as $keyer => $rower)
                        {
                            //we need save errors per-element 
                            //that will be consumed by Javascript
                            $messages[$key][] = $rower;    
                        }
                    }
                }
            }
            
            if (!empty($messages)){        
                $response->setContent(\Zend\Json\Json::encode($messages));
            } else {
                //save to db ;)
                $this->savetodb($form->getData());
                $response->setContent(\Zend\Json\Json::encode(array('success'=>1)));
            }
        }
        
        return $response;
    }
}