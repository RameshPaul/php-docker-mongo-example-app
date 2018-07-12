<?php
/**
 * Config class
 * Author: rameshbabu
 * Package: recipe-api-test
 */

namespace App\Config;

class Config
{
    private static $configFile = 'config.ini.php';

    public function __construct()
    {

    }

    /**
     * Get config function
     *
     * @return Array
     */
    public static function getDBConfig()
    {
        $config = parse_ini_file(__DIR__ . '/../../' . self::$configFile);
        return $config;
    }
}

?>