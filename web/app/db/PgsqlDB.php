<?php
/**
 * PostgreSQL Driver class
 * Author: rameshbabu
 * Package: recipe-api-test
 * Description: Implements DBDriverInterface to provide ORM like functionality
 */

namespace App\DB;

use App\Interfaces\DBDriverInterface;

class PgsqlDB implements DBDriverInterface
{
    public $db;

    /**
     * Construction
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        try {
            $db = new \PDO('pgsql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['dbname'] . ';user=' . $config['user'] . ';password=' . $config['password']);
            $this->db = $db;
        } catch (\PDOException $e) {
            throw new \Exception("Could not connect to database");
        }
    }
    /**
     * Create record function
     */
    public function save()
    {

    }

    /**
     * Push an item to nested array in a record
     */
    public function push()
    {

    }

    /**
     * Delete record
     */
    public function delete()
    {

    }

    /**
     * Find record with filters
     */
    public function get($where = [], $select = [], $sort = [], $skip = 0, $limit = 10)
    {

    }

    /**
     * Find record by Id
     */
    public function getById($id, $select = [])
    {

    }

    /**
     * Count records in a table
     */
    public function count($where = [])
    {

    }

    /**
     * Expose native method for complex query operations
     */
    public function raw()
    {
        return [
            'db' => $this->db,
        ];
    }

}

?>