<?php
namespace Tests\Unit\Mocks;

class ModelMock
{
    protected function setItem($modelFileName, $data)
    {
        $items = json_decode(file_get_contents(__DIR__ . "/$modelFileName.json"), true);
        $items[] = $data;
        file_put_contents(__DIR__ . "/$modelFileName.json", json_encode($items));
        return $data;
    }

    protected function updateItem($modelFileName, $key, $arg, $data)
    {
        $result = [];
        $items = json_decode(file_get_contents(__DIR__ . "/$modelFileName.json"), true);

        foreach ($items as &$item) {
            if ($item[$key] == $arg) {
                foreach ($data as $k => $val) {
                    $item[$k] = $val;
                }
                $result = $item;
            }
        }

        file_put_contents(__DIR__ . "/$modelFileName.json", json_encode($items));

        return $result;
    }

    protected function getItem($modelFileName, $key, $arg)
    {
        $result = [];
        $items = json_decode(file_get_contents(__DIR__ . "/$modelFileName.json"), true);

        foreach ($items as $item) {
            if ($item[$key] == $arg) {
                $result = $item;
            }
        }

        return $result;
    }

    protected function getCount($modelFileName, $where)
    {
        $result = [];
        $items = json_decode(file_get_contents(__DIR__ . "/$modelFileName.json"), true);

        return count($items);
    }

    protected function getAll($modelFileName, $where)
    {
        $result = [];
        $items = json_decode(file_get_contents(__DIR__ . "/$modelFileName.json"), true);

        return count($items);
    }

    protected function removeItem($modelFileName, $arg)
    {
        $items = json_decode(file_get_contents(__DIR__ . "/$modelFileName.json"), true);

        foreach ($items as $key => $value) {
            if ($value == $arg) {
                unset($items[$key]);
            }
        }

        file_put_contents(__DIR__ . "/$modelFileName.json", json_encode($items));
        $result = json_decode(file_get_contents(__DIR__ . "/$modelFileName.json"), true);
        return $result;
    }
}
