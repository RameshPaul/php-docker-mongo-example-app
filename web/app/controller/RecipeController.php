<?php
/**
 * Recipe controller class
 * Author: rameshbabu
 * Package: recipe-api-test
 */

namespace App\Controller;

use App\Controller\BaseController;
use App\Models\RecipeModel;
use App\Traits\RecipeTrait;
use Valitron\Validator;

class RecipeController extends BaseController
{
    use RecipeTrait;

    private $RecipeModel;

    /**
     * Recipe controller constructor
     * @param null $RecipeModel
     * @param null $Validator
     */
    public function __construct($RecipeModel = null, $Validator = null)
    {
        parent::__construct();
        $this->RecipeModel = $RecipeModel ? $RecipeModel : new RecipeModel();
        $this->Validator = $Validator ? $Validator : new Validator();
    }

    /**
     * Get recipes function
     * @param Array
     * @return Array
     */
    public function getAll($data = [])
    {
        $where = isset($data['where']) ? $data['where'] : [];
        $select = isset($data['select']) ? $data['select'] : [];
        $sort = isset($data['sort']) ? $data['sort'] : 'createdAt desc';
        $skip = isset($data['skip']) ? $data['skip'] : 0;
        $limit = isset($data['limit']) ? $data['limit'] : 10;

        $recipes = $this->RecipeModel->get($where, $select, $sort, $skip, $limit);
        $count = $this->RecipeModel->count($where);
        $response = [
            'recipes' => $recipes,
            'total' => $count
        ];

        return ['status' => 200, 'message' => 'Recipe fetched successfully', 'data' => $response];
    }

    /**
     * Create recipe function
     * @param Array
     * @return Array
     */
    public function create($data = [])
    {
        $validate = $this->validateNewRecipe($data, $this->Validator);

        if (!$validate['valid']) {
            return ['status' => 400, 'message' => 'Invalid recipe details', 'data' => $validate['errors']];
        }

        $date = date('Y-m-d H:i:s');
        $recipe = $this->prepareRecipeData($data, $this->RecipeModel);
        $recipe->ratings = [];
        $recipe->active = 1;
        $recipe->createdAt = $date;
        $recipe->updatedAt = $date;
        $newRecipe = $recipe->save();

        return ['status' => 200, 'message' => 'Recipe created successfully', 'data' => $newRecipe];
    }

    /**
     * Get recipe by id function
     * @param String
     * @return Array
     */
    public function getById($id)
    {
        if (empty($id)) {
            return ['status' => 400, 'message' => 'Invalid recipe id', 'data' => []];
        }

        $recipe = $this->RecipeModel->getById($id);

        return ['status' => 200, 'message' => 'Recipe fetched successfully', 'data' => $recipe];
    }

    /**
     * Update recipe by id function
     * @param String
     * @param Array
     * @return Array
     */
    public function update($id, $data = [])
    {
        if (empty($id)) {
            return ['status' => 400, 'message' => 'Invalid recipe id', 'data' => []];
        }

        $validate = $this->validateNewRecipe($data, $this->Validator);

        if (!$validate['valid']) {
            return ['status' => 400, 'message' => 'Invalid recipe details', 'data' => $validate['errors']];
        }

        unset($data['ratings']);
        $date = date('Y-m-d H:i:s');
        $recipe = $this->prepareRecipeData($data, $this->RecipeModel);
        $recipe->_id = $id;
        $recipe->updatedAt = $date;
        $updatedRecipe = $recipe->save();

        return ['status' => 200, 'message' => 'Recipe updated successfully', 'data' => $updatedRecipe];
    }

    /**
     * Delete recipe by id function
     * @param String
     * @return Array
     */
    public function deleteById($id)
    {
        if (empty($id)) {
            return ['status' => 400, 'message' => 'Invalid recipe id', 'data' => []];
        }

        $recipe = $this->RecipeModel;
        $recipe->_id = $id;
        $recipe->delete();

        return ['status' => 200, 'message' => 'Recipe deleted successfully', 'data' => []];
    }

    /**
     * Rate recipe by id function
     * @param String
     * @param Array
     * @return Array
     */
    public function rateRecipe($id, $data = [])
    {
        if (empty($id)) {
            return ['status' => 400, 'message' => 'Invalid recipe id', 'data' => []];
        }

        $validate = $this->validateRecipeRate($data, $this->Validator);

        if (!$validate['valid']) {
            return ['status' => 400, 'message' => 'Invalid recipe rate data', 'data' => $validate['errors']];
        }

        $recipe = $this->RecipeModel;
        $recipe->_id = $id;
        $recipe->ratings = [$data];
        $updatedRecipe = $recipe->push();

        return ['status' => 200, 'message' => 'Recipe rated successfully', 'data' => $updatedRecipe];
    }

}