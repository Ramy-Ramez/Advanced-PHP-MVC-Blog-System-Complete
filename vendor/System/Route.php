<?php
namespace System;
class Route {
    //A class that receives and handles URL that comes from request (handles the URL that the user typed and compares it to the regular expression) and then defines which controller and method to call.
    //$action is controller@method (If there is no method specified, it is considered as 'index' method by default)

    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;
    /**
     * Routes Container
     *
     * @var array
     */
    private $routes = [];
    /**
     * Not Found Url
     *
     * @var string
     */
    private $notFound;
    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app) {//Type Casting
            $this->app = $app;
    }
    /**
     * Add New Route
     *
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @return void
     */
    public function add($url, $action, $requestMethod = 'GET') {//This function is called from index.php (which is inside App Folder)
        //$action = Controller@Method
        $route = [
            'url'     => $url,
            'pattern' => $this->generatePattern($url),
            'action'  => $this->getAction($action),
            'method'  => strtoupper($requestMethod)
        ];
        $this->routes[] = $route;
        //pre($this->routes);
    }
    /**
     * Set Not Found Url
     *
     * @param string $url
     * @return void
     */
    public function notFound($url) {//This function is called from index.php (in App Folder)
        $this->notFound = $url;
    }
    /**
     * Get Proper Route
     *
     * @return array
     */
    public function getProperRoute() {//This function is called from Application.php in run() function
        /*echo $this->app->request->url() . '<br>';
        die;*/
        foreach ($this->routes as $route) {
            //pre($this->routes);
            if ($this->isMatching($route['pattern']) AND $this->isMatchingRequestMethod($route['method'])) {
                //echo $route['pattern'] . ' From getProperRoute() function in Route.php<br>';
                $arguments = $this->getArgumentsFrom($route['pattern']);
                //pre($arguments);
                //action = controller@method
                //echo $route['action'] . '<br>';
                list($controller, $method) = explode('@', $route['action']);
                return [$controller, $method, $arguments];//Return an array which contains: $controller, $method, $arguments
            }
        }
        //return $this->app->url->redirectTo('/404');//If someone wrote anything in the URL (like: http://localhost/blog/blabla) which is not added in our routes in index.php(in App Folder), redirect him to the 404 Page
        return $this->app->url->redirectTo($this->notFound);//If someone wrote anything in the URL (like: http://localhost/blog/blabla) which is not added in our routes in index.php(in App Folder), redirect him to the 404 Page
        //Because in index.php (in App folder), we have that code line:    $app->route->notFound('/404');
    }
    /**
     * Determine if the given pattern matches the current request url
     *
     * @param string $pattern
     * @return bool
     */
    private function isMatching($pattern) {
        //echo $this->app->request->url() . '<br>';
        //var_dump(preg_match($pattern, $this->app->request->url()));
        return preg_match($pattern, $this->app->request->url());//Compare the URL entered by user to the right regular expression
    }

    /**
     * Determine if the current request method equals
     * the given route method
     *
     * @param string $routeMethod
     * @return bool
     */
    private function isMatchingRequestMethod($routeMethod) {
        return $routeMethod == $this->app->request->method();
    }

    /**
     * Get Arguments from the current request url based on the given pattern
     *
     * @param string $pattern
     * @return array
     */
    private function getArgumentsFrom($pattern) {
        preg_match($pattern, $this->app->request->url(), $matches);
        //From PHP Manual: $matches[0] will contain the text that matched the full pattern, $matches[1] will have the text that matched the first captured parenthesized subpattern (the regular expression with parentheses()), and so on.
        //pre($matches);
        array_shift($matches);//Because we want to remove the $matches[0] which is the complete $match to the regular expression and we want the arguments only (the parenthesized subpatterns). The arguments will be the Controller name ($matches[1]) and Method name ($matches[2])
        //pre($matches);
        return $matches;
    }
    /**
     * Generate a regex pattern for the given url
     *
     * @param string $url
     * @return string
     */
    private function generatePattern($url) {
        //Start
        $pattern = '#^';//We avoided using the delimiters '/  /' and used '#  #' or '~   ~' instead because it's a link and links contains many of / which can cause errors
        // :text ([a-zA-Z0-9-]+)
        // :id (\d+)
        //Any regular expression inside capturing group () will return in $matches array of preg_match() function
        $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], $url);//The parenthesized subpatterns() will return in $matches
        //echo $pattern . '<br>';
        //End
        $pattern .= '$#';//Concatenation
        //echo $pattern . '<br>';
        return $pattern;
    }
    /**
     * Get The Proper Action
     *
     * @param string $action
     * @return string
     */
    private function getAction($action) {//action = controller@method
        $action = str_replace('/', '\\', $action);//Because namespaces contains back slashes \ ONLY
        //echo strpos($action, '@') !== false ? $action : $action . '@index';//The $action = Controller@Method  so  if the action doesn't @method, put @index by default.
        //echo '<br>';
        return strpos($action, '@') !== false ? $action : $action . '@index';//The $action = Controller@Method  so  if the action doesn't @method, put @index by default.
    }
}