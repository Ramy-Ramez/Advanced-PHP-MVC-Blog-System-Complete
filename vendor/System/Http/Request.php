<?php
namespace System\Http;
class Request {
    /**
     * Url
     *
     * @var string
     */
    private $url;
    /**
     * Base Url
     *
     * @var string
     */
    private $baseUrl;//comes from prepareUrl() function here

    /**
     * Uploaded Files Container
     * @var array
     */
    private $files = [];

    /**
     * Prepare url
     *
     * @return void
     */
    public function prepareUrl() {//This function is called from Application.php
        //pre($_SERVER);//pre() function is in helpers.php
        //echo $this->server('SCRIPT_NAME') . '<br>';
        //We want to hide index.php from the link
        $script = dirname($this->server('SCRIPT_NAME'));
        //echo $script . '<br>';
        //We want to remove /blog from the link
        $requestUri = $this->server('REQUEST_URI');
        //echo $requestUri . '<br>';
        //var_dump(strpos($requestUri, '?'));//The offset of the '?' in $requestUri
        if (strpos($requestUri, '?') !== false) {//If there is a '?' in the URI (Example: blog/go/to/sleep/6?id=4)
            //pre(explode('?', $requestUri));//Returns TWO arrays
            list($requestUri, $queryString) = explode('?', $requestUri);
            //echo '<br>' . $requestUri . '<br>' . $queryString . '<br>';
        }
        //Refer to https://regex101.com/
        //Using the '/  /' delimiters gives an error here because $script (/blog) already starts with a '/', so there are two solutions: use '#   #' delimiters instead, or escape the '/' in '/blog' using a \
        //The two following regular expressions are CORRECT:
        //$requestUri = preg_replace('#^' . $script . '#', '', $requestUri);//We wanna remove 'blog' from the URI ('blog' is $script)
        //$requestUri = preg_replace('/^\\' . $script . '/', '', $requestUri);//We wanna remove 'blog' from the URI ('blog' is $script)
        //echo $requestUri . '<br>';
        //$this->url = preg_replace('#^' . $script . '#', '', $requestUri);//We wanna remove 'blog' from the URI ('blog' is $script)
        $this->url = rtrim(preg_replace('#^' . $script . '#', '', $requestUri), '/');//We wanna remove 'blog' from the URI ('blog' is $script) //We use rtrim() function because in index.php (in App folder), routes are added without '/' in the end of the route (If we wouldn't trim the '/', we would be redirected to 404 page).
        //die($this->url);
        //echo $this->url . '<br>';
        //pre($_SERVER);
        if (!$this->url) {//If the url is empty
            $this->url = '/';
        }
        $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script . '/';// http . :// . localhost . /blog . /
        //echo $this->baseUrl . '<br>';
    }
    /**
     * Get Value from _GET by the given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return array_get($_GET, $key, $default);//array_get() function is in helpers.php
    }
    /**
     * Get Value from _POST by the given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = null) {
        return array_get($_POST, $key, $default);//array_get() function is in helpers.php
    }

    /**
     * Get the uploaded file object for the given input
     *
     * @param string $input
     * @return \System\Http\UploadedFile
     */
    public function file($input) {
        if (isset($this->files[$input])) {
            return $this->files[$input];
        }
        $uploadedFile = new UploadedFile($input);
        $this->files[$input] = $uploadedFile;
        return $this->files[$input];
    }

    /**
     * Get Value from _SERVER by the given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function server($key, $default = null) {
        return array_get($_SERVER, $key, $default);//array_get() function is in helpers.php
    }
    /**
     * Get Current Request Method
     *
     * @return string
     */
    public function method() {
        return $this->server('REQUEST_METHOD');
    }
    /**
     * Get full url of the script
     *
     * @return string
     */
    public function baseUrl() {//comes from the private property $baseUrl which in turn takes its value from prepareUrl() function
        return $this->baseUrl;
    }
    /**
     * Get Only relative url (clean url)
     *
     * @return string
     */
    public function url() {
        //echo $this->url . '<br>';
        return $this->url;
    }
}