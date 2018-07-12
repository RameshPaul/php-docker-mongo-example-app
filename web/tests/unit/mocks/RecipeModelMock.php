<?php
namespace Tests\Unit\Mocks;

require_once __DIR__ . '/ModelMock.php';

use App\Interfaces\DBDriverInterface;

class RecipeModelMock extends ModelMock implements DBDriverInterface
{
    public $collection = 'recipe';
    public $_id;
    public $name;
    public $prepTime;
    public $difficulty;
    public $vegetarian;
    public $ratings;
    public $active;
    public $createdAt;
    public $updatedAt;

    public function save()
    {
        //save to json file
        $data = $this->getFields();

        if (isset($data['_id']) && !empty($data['_id'])) {
            $id = $data['_id'];
            unset($data['_id']);
            return $this->updateItem($this->collection, '_id', $id, $data);
        }

        $data['_id'] = rand();
        return $this->setItem($this->collection, $data);
    }

    public function get($where, $select, $sort, $skip, $limit)
    {
        //get from json file
        return $this->getAll($this->collection, $where);
    }

    public function getById($id, $select = [])
    {
        //get from json file by loop
        return $this->getItem($this->collection, "_id", $id);
    }

    public function count($where)
    {
        //get from json file
        return $this->getCount($this->collection, $where);
    }

    public function delete()
    {
        //delete from json file
        return $this->removeItem($this->collection, $this->getFields());
    }

    public function push()
    {
        //push to json file
        $data = $this->getFields();

        if (isset($data['_id']) && !empty($data['_id'])) {
            $id = $data['_id'];
            unset($data['_id']);
            return $this->updateItem($this->collection, '_id', $id, $data);
        }

        return $data;
    }

    private function getFields()
    {
        $fields = array_filter(get_object_vars($this), function ($v) {
            return !is_null($v);
        });

        unset($fields['collection']);
        unset($fields['db']);
        unset($fields['dbName']);
        unset($fields['config']);

        return $fields;
    }

}
