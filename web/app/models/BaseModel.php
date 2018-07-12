<?php
/**
 * Base Model class
 * Author: rameshbabu
 * Package: recipe-api-test
 * Description: Extends DBDriver class to provide CURD operations
 */

namespace App\Models;

use App\Config\Config;
use App\DB\MongoDB;

class BaseModel extends MongoDB
{
    /**
     * Constructor
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(Config::getDBConfig());
    }
}