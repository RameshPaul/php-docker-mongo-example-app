<?php
/**
 * DB Driver interface
 * Author: rameshbabu
 * Package: recipe-api-test
 * Description: This interface provides basic ORM like functionality
 */

namespace App\Interfaces;

Interface DBDriverInterface
{
    /**
     * create record
     */
    public function save();

    /**
     * get records
     */
    public function get($where, $select, $sort, $skip, $limit);

    /**
     * get single record
     */
    public function getById($id, $select);

    /**
     * get count
     */
    public function count($where);

    /**
     * delete records
     */
    public function delete();

}
