<?php
/**
 * Recipe Model class
 * Author: rameshbabu
 * Package: recipe-api-test
 */
namespace App\Models;

use App\Models\BaseModel;

class RecipeModel extends BaseModel
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

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

}