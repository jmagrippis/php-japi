<?php

namespace Docnet\JAPI\Http;


class Request
{
    /**
     * Request body
     * @var string
     */
    protected $str_body = null;

    /**
     * Request body decoded as json
     * @var string
     */
    protected $str_json = null;

    /**
     * Was there an HTTP POST?
     *
     * Realistically, we're probably not going to use PUT, DELETE (for now)
     *
     * @return bool
     */
    public final function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    /**
     * Get the HTTP request headers
     *
     * getallheaders() available for CGI (in addition to Apache) from PHP 5.4
     *
     * Fall back to manual processing of $_SERVER if needed
     *
     * @todo Test on Google App Engine
     *
     * @return array
     */
    public function getHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $arr_headers = [];
        foreach ($_SERVER as $str_key => $str_value) {
            if (strpos($str_key, 'HTTP_') === 0) {
                $arr_headers[str_replace(' ', '-',
                    ucwords(strtolower(str_replace('_', ' ', substr($str_key, 5)))))] = $str_value;
            }
        }
        return $arr_headers;
    }

    /**
     * Get the request body
     *
     * @return string
     */
    public function getBody()
    {
        if ($this->str_body === null) {
            // We store this as prior to php5.6 this can only be read once
            $this->str_body = file_get_contents('php://input');
        }
        return $this->str_body;
    }

    /**
     * Get the request body as a JSON object
     *
     * @return mixed
     */
    public function getJson()
    {
        if ($this->str_json === null) {
            $this->str_json = json_decode($this->getBody());
        }
        return $this->str_json;
    }

    /**
     * Get a request parameter. Check GET then POST data, then optionally any json body data.
     *
     * @param string $str_key
     * @param mixed $str_default
     * @param bool $check_json_body
     * @return mixed
     */
    public function getParam($str_key, $str_default = null, $check_json_body = false)
    {
        $str_query = $this->getQuery($str_key);
        if (null !== $str_query) {
            return $str_query;
        }
        $str_post = $this->getPost($str_key);
        if (null !== $str_post) {
            return $str_post;
        }
        // Optionally check Json in Body
        if ($check_json_body && isset($this->getJson()->$str_key)) {
            if (null !== $this->getJson()->$str_key) {
                return $this->getJson()->$str_key;
            }
        }
        return $str_default;
    }

    /**
     * Get a Query/GET input parameter
     *
     * @param string $str_key
     * @param mixed $str_default
     * @return mixed
     */
    public function getQuery($str_key, $str_default = null)
    {
        return (isset($_GET[$str_key]) ? $_GET[$str_key] : $str_default);
    }

    /**
     * Get a POST parameter
     *
     * @param string $str_key
     * @param mixed $str_default
     * @return mixed
     */
    public function getPost($str_key, $str_default = null)
    {
        return (isset($_POST[$str_key]) ? $_POST[$str_key] : $str_default);
    }
}