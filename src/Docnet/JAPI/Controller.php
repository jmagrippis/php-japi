<?php
/**
 * Copyright 2015 Docnet
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Docnet\JAPI;

use Docnet\JAPI\Http\Request;

/**
 * Base Controller
 *
 * There's some stuff in here which feels like it should be part of a "Response"
 * object but, we'll leave it here for now!
 *
 * @author Tom Walder <tom@docnet.nu>
 * @abstract
 */
abstract class Controller
{

    /**
     * @var Request
     */
    protected $obj_request;

    /**
     * Response data
     *
     * @var null|object|array
     */
    protected $obj_response = null;

    /**
     * Controller constructor.
     * @param Request $obj_request
     */
    public function __construct(Request $obj_request)
    {
        $this->obj_request = $obj_request;
    }

    /**
     * Default, empty pre dispatch
     *
     * Usually overridden for authentication
     */
    public function preDispatch()
    {
    }

    /**
     * Default, empty post dispatch
     *
     * Available for override - perhaps for UOW DB writes?
     */
    public function postDispatch()
    {
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        return $this->obj_request;
    }

    /**
     * Set the response object
     *
     * @param $obj_response
     */
    protected function setResponse($obj_response)
    {
        $this->obj_response = $obj_response;
    }

    /**
     * Get the response data
     *
     * @return object|array
     */
    public function getResponse()
    {
        return $this->obj_response;
    }

    /**
     * Main dispatch method
     *
     * @return mixed
     */
    abstract public function dispatch();

}