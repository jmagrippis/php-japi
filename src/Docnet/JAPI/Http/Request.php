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
     * $_GET and any overrides
     *
     * @var array
     */
    protected $arr_get;

    /**
     * $_POST and any overrides
     *
     * @var array
     */
    protected $arr_post;

    /**
     * Create a Request object containing all auth request params
     *
     * @todo Review constructor with team. No longer sufficient representation.
     * @todo Add METHOD When needed.
     *
     * @param array $arr_get
     * @param array $arr_post
     */
    public function __construct(array $arr_get = null, array $arr_post = null)
    {
        $this->arr_get = is_array($arr_get) ? array_merge($_GET, $arr_get) : $_GET;
        $this->arr_post = is_array($arr_post) ? array_merge($_POST, $arr_post) : $_POST;
    }

    /**
     * Determine if current request uses GET method
     *
     * @return bool
     */
    public function isPost()
    {
        return 'POST' === $this->getMethod();
    }

    /**
     * Determine if current request uses GET method
     *
     * @return bool
     */
    public function isGet()
    {
        return 'GET' === $this->getMethod();
    }

    /**
     * Get the HTTP request headers     *
     * getallheaders() available for CGI (in addition to Apache) from PHP 5.4     *
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
     * @param mixed $mix_default
     * @param bool $bol_check_json_body
     * @return mixed
     */
    public function getParam($str_key, $mix_default = null, $bol_check_json_body = false)
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
        if ($bol_check_json_body && isset($this->getJson()->$str_key)) {
            if (null !== $this->getJson()->$str_key) {
                return $this->getJson()->$str_key;
            }
        }
        return $mix_default;
    }

    /**
     * Get a Query/GET input parameter
     *
     * @param string $str_key
     * @param mixed $mix_default
     * @return mixed
     */
    public function getQuery($str_key, $mix_default = null)
    {
        return (isset($_GET[$str_key]) ? $_GET[$str_key] : $mix_default);
    }

    /**
     * Get a POST parameter
     *
     * @param string $str_key
     * @param mixed $mix_default
     * @return mixed
     */
    public function getPost($str_key, $mix_default = null)
    {
        return (isset($_POST[$str_key]) ? $_POST[$str_key] : $mix_default);
    }

    /**
     * Get request method
     *
     * @return string
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}