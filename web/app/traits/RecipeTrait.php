<?php
/**
 * Recipe Trait
 * Author: rameshbabu
 * Package: recipe-api-test
 * Description: Recipe trait for implementing shared functionality
 */

namespace App\Traits;

trait RecipeTrait
{
    public function validateNewRecipe($data, $Validator)
    {
        $validator = new $Validator($data);
        $rules = [
            'required' => [['name'], ['prepTime'], ['difficulty'], ['vegetarian']],
            'integer' => [['prepTime'], ['difficulty']],
            'boolean' => [['vegetarian']],
            'lengthMin' => [['name', 2]],
            'lengthMax' => [['name', 256]],
            'min' => [['difficulty', 1]],
            'max' => [['difficulty', 3]],
        ];
        $validator->rules($rules);

        if ($validator->validate()) {
            return ['valid' => true, 'errors' => []];
        }

        return ['valid' => false, 'errors' => $validator->errors()];
    }

    public function prepareRecipeData($data, $recipe)
    {
        $recipe->name = $data['name'];
        $recipe->prepTime = $data['prepTime'];
        $recipe->difficulty = $data['difficulty'];
        $recipe->vegetarian = $data['vegetarian'];
        return $recipe;
    }

    public function validateRecipeRate($data, $Validator)
    {
        $validator = new $Validator($data);
        $rules = [
            'required' => [['rate'], ['name'], ['email']],
            'integer' => 'rate',
            'min' => [['rate', 1]],
            'max' => [['rate', 5]],
            'email' => 'email'
        ];
        $validator->rules($rules);

        if ($validator->validate()) {
            return ['valid' => true, 'errors' => []];
        }

        return ['valid' => false, 'errors' => $validator->errors()];
    }
}
