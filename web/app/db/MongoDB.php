<?php
/**
 * MongoDb Driver class
 * Author: rameshbabu
 * Package: recipe-api-test
 * Description: Implements DBDriverInterface to provide ORM like functionality
 */

namespace App\DB;

use App\Interfaces\DBDriverInterface;

class MongoDB implements DBDriverInterface
{
    public $collection;
    private $db;
    private $dbName;
    private $config;

    /**
     * Constructor
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        $this->dbName = $config['dbname'];
        $this->config = $config;
        try {
            $db = new \MongoDB\Driver\Manager('mongodb://' . $config['host'] . ':' . $config['port'] . '/' . $config['dbname']);
            $this->db = $db;
        } catch (\MongoConnectionException $e) {
            throw new \Exception("Could not connect to database");
        }
    }

    /**
     * Create document function
     */
    public function save()
    {
        $fields = $this->getFields();

        if (!isset($fields['_id'])) {
            $fields['_id'] = new \MongoDB\BSON\ObjectId();
            return $this->create($this->collection, $fields);
        }

        $where = ['_id' => $fields['_id']];
        unset($fields['_id']);

        return $this->update($this->collection, $where, $fields);
    }

    /**
     * Push an item to nested array in a document
     */
    public function push()
    {
        $fields = $this->getFields();

        $where = ['_id' => $fields['_id']];
        unset($fields['_id']);

        return $this->update($this->collection, $where, $fields, '$push');
    }

    /**
     * Delete document
     */
    public function delete()
    {
        return $this->remove($this->collection, $this->getFields());
    }

    /**
     * Find documents with filters
     */
    public function get($where = [], $select = [], $sort = [], $skip = 0, $limit = 10)
    {
        $translate = $this->translateGetQueries(get_defined_vars());
        return $this->find($this->collection, $where, $select, $translate['sort'], $skip, $limit);
    }

    /**
     * Find document by Id
     */
    public function getById($id, $select = [])
    {
        $where = ['_id' => new \MongoDB\BSON\ObjectId($id)];
        $res = $this->find($this->collection, $where, $select);

        if (count($res)) {
            return $res[0];
        }

        return [];
    }

    /**
     * Count documents in a collection
     */
    public function count($where = [])
    {
        return $this->findCount($this->collection, $where);
    }

    /**
     * Expose native method for complex query operations
     */
    public function raw()
    {
        return [
            'db' => $this->db,
            'dbName' => $this->dbName
        ];
    }

    /**
     * Get properties of a model class
     */
    private function getFields()
    {
        $fields = array_filter(get_object_vars($this), function ($v) {
            return !is_null($v);
        });

        if (isset($fields['_id']) && !empty($fields['_id'])) {
            $fields['_id'] = new \MongoDB\BSON\ObjectId($fields['_id']);
        }

        if (isset($fields['createdAt'])) {
            $fields['createdAt'] = new \MongoDB\BSON\UTCDateTime(strtotime($fields['createdAt']));
        }

        if (isset($fields['updatedAt'])) {
            $fields['updatedAt'] = new \MongoDB\BSON\UTCDateTime(strtotime($fields['updatedAt']));
        }

        unset($fields['collection']);
        unset($fields['db']);
        unset($fields['dbName']);
        unset($fields['config']);

        return $fields;
    }

    /**
     * Translate query filters to mongo
     */
    private function translateGetQueries($queryData)
    {
        $sortBy = [];
        $srt = explode(' ', $queryData['sort']);

        if (count($srt) == 2) {
            $sortBy[$srt[0]] = strtolower($srt[1]) === 'desc' ? -1 : 1;
        }

        $queryData['sort'] = $sortBy;
        return $queryData;
    }

    /**
     * Create record
     */
    private function create($table, $record = [])
    {
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->insert($record);
        //$db = new \MongoDB\Driver\Manager('mongodb://' . $this->config['host'] . ':' . $this->config['port'] . '/' . $this->config['dbname']);
        //print_r($db); exit;
        $this->db->executeBulkWrite($this->dbName . '.' . $table, $bulk);
        return $record;
    }

    /**
     * Update records
     */
    private function update($table, $where, $record, $operator = '$set')
    {
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->update($where, [$operator => $record]);

        return $this->db->executeBulkWrite($this->dbName . '.' . $table, $bulk);
    }

    /**
     * Delete records
     */
    private function remove($table, $where)
    {
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->delete($where);

        return $this->db->executeBulkWrite($this->dbName . '.' . $table, $bulk);
    }

    /**
     * Find records
     */
    private function find($table, $where = [], $select = [], $sort = [], $skip = 0, $limit = 10)
    {
        $query = new \MongoDB\Driver\Query($where, ['projection' => $select, 'sort' => $sort, 'skip' => $skip, 'limit' => $limit]);

        $result = $this->db->executeQuery($this->dbName . '.' . $table, $query);
        return $result->toArray();
    }

    /**
     * Find count
     */
    private function findCount($table, $where = [])
    {
        $query = new \MongoDB\Driver\Command(["count" => $table, "query" => $where]);

        $result = $this->db->executeCommand($this->dbName, $query);
        return $result->toArray()[0]->n;
    }

}

?>