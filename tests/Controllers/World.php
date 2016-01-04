<?php

namespace Hello;


class World extends \Docnet\JAPI\Controller
{
    public function dispatch()
    {
        $obj_request = $this->getRequest();

        $this->setResponse([
            'input1' => $obj_request->getQuery('input1'),
            'input2' => $obj_request->getPost('input2'),
            'input3' => $obj_request->getParam('input3'),
            'input4' => $obj_request->getParam('input4')
        ]);
    }
}