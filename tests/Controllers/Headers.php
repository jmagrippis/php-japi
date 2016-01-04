<?php

class Headers extends \Docnet\JAPI\Controller
{
    public function dispatch(){
        $this->setResponse($this->getRequest()->getHeaders());
    }
}