<?php
/**
 * Authentication class
 * Author: rameshbabu
 * Package: recipe-api-test
 */

namespace App\Auth;

class Auth
{
    private $authUser;
    private $authPass;

    /**
     * Constructor
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->authUser = $config['authUser'];
        $this->authPass = $config['authPassword'];
    }

    public function authorize()
    {
        $this->basicAuth();
    }

    /**
     * Basic authentication function
     */
    private function basicAuth()
    {
        $AUTH_USER = $this->authUser;
        $AUTH_PASS = $this->authPass;
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW'] != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            echo "Unauthorized Access";
            exit;
        }
    }
}
