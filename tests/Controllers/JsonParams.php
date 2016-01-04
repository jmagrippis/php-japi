<?php

class JsonParams extends \Docnet\JAPI\Controller
{
    public function dispatch()
    {
        $obj_request = $this->getRequest();

        $this->setResponse([
            'json_param' => $obj_request->getParam('json_param', 'default_value', true),
            'missing_param' => $obj_request->getParam('missing_param', 'default_value', true)
        ]);
    }
}