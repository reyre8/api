<?php

namespace Reyre8\EnsembleChallenge\Libs;
use FastRoute;

class RequestHandler {

    const POST_VERB = 'POST';

    const NOT_FOUND_CODE = 404;
    const NOT_ALLOWED_CODE = 405;
    const BAD_REQUEST_CODE = 400;
    const SUCCESS_CODE = 200;

    protected $responseHeaders = array(
        'Content-Type: application/json'
    );

    protected $requestHeaders = array(
        'Content-Type' => 'application/json'
    );

    private $dispatcher;
    private $body;
    private $verb;
    private $uri;
    private $args;

    public function __construct(FastRoute\Dispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public static function init(FastRoute\Dispatcher $dispatcher) {
        $request = new RequestHandler($dispatcher);
        try {

            // Get routing information
            $routeInfo = $request->getRouteInfo();

            // route to handler
            $request->route($routeInfo);

        } catch (\Exception $e) {
            $request->respondFailure($e->getMessage(), self::BAD_REQUEST_CODE);
        }
    }

    private function getRouteInfo() {

        // Get URI and verb
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];

        // Strip query string
        if (false !== $pos = strpos($this->uri, '?')) {
            $this->uri = substr($this->uri, 0, $pos);
        }

        // Decode URI
        $this->uri = rawurldecode($this->uri);
        return $this->dispatcher->dispatch($this->verb, $this->uri);
    }

    private function route(array $routeInfo) {
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                $this->respondFailure('Route not found', self::NOT_FOUND_CODE);
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $this->respondFailure('Not allowed', self::NOT_ALLOWED_CODE);
                break;
            case FastRoute\Dispatcher::FOUND:

                // Enforce content type validation for input data
                if($this->verb == self::POST_VERB) {
                    if(!strstr($_SERVER["CONTENT_TYPE"], $this->requestHeaders['Content-Type'])) {
                        $headers = implode(',', $this->requestHeaders);
                        throw new \Exception("Only content type {$this->requestHeaders['Content-Type']} is accepted for verb {$this->verb}");
                    }
                }

                $this->body = json_decode(file_get_contents('php://input'), true);
                $this->args = $routeInfo[2];
                $handler = $routeInfo[1];
                list($class, $method) = explode("/", $handler, 2);
                call_user_func_array(array(new $class, $method), array($this));
                break;
        }
    }

    public function respond($statusCode, $data) {
        http_response_code($statusCode);
        $this->generateResponseHeaders();
        print json_encode($data);
        exit;
    }

    private function generateResponseHeaders() {
        foreach($this->responseHeaders AS $header) {
            header($header);
        }
    }

    // Response methods
    public function respondSuccess($data) {
        $this->respond(self::SUCCESS_CODE, $data);
    }

    public function respondFailure($data, $statusCode = self::BAD_REQUEST_CODE) {
        $this->respond($statusCode, $data);
    }

    // Getters, properties to be set from within the class
    public function getBody() {
        return $this->body;
    }

    public function getVerb() {
        return $this->verb;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getArgs() {
        return $this->args;
    }

    public function getResponseHeaders() {
        return $this->responseHeaders;
    }

    public function setResponseHeaders(array $responseHeaders) {
        $this->responseHeaders = $responseHeaders;
    }

    public function getRequestHeaders() {
        return $this->requestHeaders;
    }

    public function setRequestHeaders(array $requestHeaders) {
        $this->requestHeaders = $requestHeaders;
    }
}