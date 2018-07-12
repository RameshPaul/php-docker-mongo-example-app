<?php
/**
 * Base controller class
 * Author: rameshbabu
 * Package: recipe-api-test
 */

namespace App\Controller;

class BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        set_exception_handler([$this, 'exception_handler']);
    }

    /**
     * Common Response function
     */
    public function response($status, $statusMessage, $data)
    {
        header("HTTP/1.1 " . $status);

        $response['status'] = $status;
        $response['message'] = $statusMessage;
        $response['data'] = $data;

        $jsonResponse = json_encode($response);
        echo $jsonResponse;
    }

    /**
     * Global exception handler to return json response on exceptions
     * @param Object
     */
    public function exception_handler($exception)
    {
        $this->response(500, $exception->getMessage(), $exception->getTrace());
    }
}