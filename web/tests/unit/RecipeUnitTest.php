<?php
namespace Tests\Unit;

require_once __DIR__ . '/mocks/RecipeModelMock.php';

use App\Config\Config;
use App\Controller\RecipeController;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Mocks\RecipeModelMock;
use Valitron\Validator;

class RecipeUnitTest extends TestCase
{

    private $recipeModel;
    private $validator;
    private $recipeController;

    public function setUp()
    {
        $this->recipeModel = new RecipeModelMock();
        $this->validator = new Validator();
        $this->recipeController = new RecipeController($this->recipeModel, $this->validator);
    }

    /**
     * @dataProvider createRecipeDataProvider
     */
    public function testCreateRecipe($data, $expected)
    {
        //$this->expectOutputString('');
        //print_r($this->apiUrl);
        //flush();
        $res = $this->recipeController->create($data);
        $this->assertEquals($expected['status'], $res['status']);
        $this->assertEquals($expected['message'], $res['message']);

        if ($expected['status'] == 200) {
            $this->assertSame(array_diff(array_keys($res['data']), array_keys($expected['data'])), array_diff(array_keys($expected['data']), array_keys($res['data'])));
        }
    }

    public function createRecipeDataProvider()
    {
        $data = [
            [
                [
                    'name' => 'indian noodles',
                    'prepTime' => 12,
                    'difficulty' => 2,
                    'vegetarian' => true

                ],
                [
                    'status' => 200,
                    'message' => 'Recipe created successfully',
                    'data' => [
                        '_id' => '',
                        'name' => 'indian noodles',
                        'prepTime' => 12,
                        'difficulty' => 2,
                        'vegetarian' => true,
                        'ratings' => [],
                        'active' => 1,
                        'createdAt' => '',
                        'updatedAt' => '',
                    ]
                ]
            ],
            [
                [
                    'name' => '',
                    'prepTime' => 12,
                    'difficulty' => 2,
                    'vegetarian' => 'true'

                ],
                [
                    'status' => 400,
                    'message' => 'Invalid recipe details',
                    'data' => ['name' => [], 'vegetarian' => []]
                ]
            ]

        ];

        return $data;
    }

    /**
     * @dataProvider listRecipeDataProvider
     */
    public function testListRecipe($data, $expected)
    {
        //$this->expectOutputString('');
        //print_r($this->apiUrl);
        //flush();
        $res = $this->recipeController->getAll($data);
        $this->assertEquals($expected['status'], $res['status']);
        $this->assertEquals($expected['message'], $res['message']);
    }

    public function listRecipeDataProvider()
    {
        return [
            [
                [],
                [
                    'status' => 200,
                    'message' => 'Recipe fetched successfully'
                ]
            ]
        ];
    }

    public function createAnotherRecipe()
    {
        $data = [
            'name' => 'chicken fried rice',
            'prepTime' => 12,
            'difficulty' => 2,
            'vegetarian' => false

        ];

        $res = $this->recipeController->create($data);
        $this->expectOutputString('');
        print_r($res['data']);
        flush();
        return $res['data'];
    }

    /**
     * @dataProvider getRecipeByIdDataProvider
     */
    public function testGetRecipeById($id, $expected)
    {
        //$this->expectOutputString('');
        //print_r($this->apiUrl);
        //flush();
        $res = $this->recipeController->getById($id);
        $this->assertEquals($expected['status'], $res['status']);
        $this->assertEquals($expected['message'], $res['message']);
    }

    public function getRecipeByIdDataProvider()
    {
        $recipe = $this->createAnotherRecipe();
        return [
            [
                $recipe['_id'],
                [
                    'status' => 200,
                    'message' => 'Recipe fetched successfully'
                ]
            ],
            [
                '',
                [
                    'status' => 400,
                    'message' => 'Invalid recipe id'
                ]
            ],
        ];
    }

    /**
     * @dataProvider editRecipeDataProvider
     */
    public function testEditRecipe($id, $data, $expected)
    {
        //$this->expectOutputString('');
        //print_r($this->apiUrl);
        //flush();
        $res = $this->recipeController->update($id, $data);
        $this->assertEquals($expected['status'], $res['status']);
        $this->assertEquals($expected['message'], $res['message']);
    }

    public function editRecipeDataProvider()
    {
        $recipe = $this->createAnotherRecipe();
        return [
            [
                $recipe['_id'],
                [
                    'name' => 'edit chicken noodles',
                    'prepTime' => 20,
                    'difficulty' => 3,
                    'vegetarian' => true
                ],
                [
                    'status' => 200,
                    'message' => 'Recipe updated successfully'
                ]
            ],
            [
                '',
                [
                    'name' => 'edit chicken noodles',
                    'prepTime' => 20,
                    'difficulty' => 3,
                    'vegetarian' => true
                ],
                [
                    'status' => 400,
                    'message' => 'Invalid recipe id'
                ]
            ],
            [
                $recipe['_id'],
                [
                    'prepTime' => 20,
                    'difficulty' => 3,
                    'vegetarian' => true
                ],
                [
                    'status' => 400,
                    'message' => 'Invalid recipe details'
                ]
            ],
        ];
    }

    /**
     * @dataProvider rateRecipeDataProvider
     */
    public function testRateRecipe($id, $data, $expected)
    {
        //$this->expectOutputString('');
        //print_r($this->apiUrl);
        //flush();
        $res = $this->recipeController->rateRecipe($id, $data);
        $this->assertEquals($expected['status'], $res['status']);
        $this->assertEquals($expected['message'], $res['message']);
    }

    public function rateRecipeDataProvider()
    {
        $recipe = $this->createAnotherRecipe();
        return [
            [
                $recipe['_id'],
                [
                    'name' => 'edit chicken noodles',
                    'rate' => 3,
                    'email' => 'ramesh@babu.com'
                ],
                [
                    'status' => 200,
                    'message' => 'Recipe rated successfully'
                ]
            ],
            [
                '',
                [
                    'name' => 'edit chicken noodles',
                    'rate' => 3,
                    'email' => 'ramesh@babu.com'
                ],
                [
                    'status' => 400,
                    'message' => 'Invalid recipe id'
                ]
            ],
            [
                $recipe['_id'],
                [
                    'rate' => 3,
                    'email' => 'ramesh@babu.com'
                ],
                [
                    'status' => 400,
                    'message' => 'Invalid recipe rate data'
                ]
            ],
        ];
    }

    /**
     * @dataProvider deleteRecipeByIdDataProvider
     */
    public function testDeleteRecipeById($id, $expected)
    {
        //$this->expectOutputString('');
        //print_r($this->apiUrl);
        //flush();
        $res = $this->recipeController->deleteById($id);
        $this->assertEquals($expected['status'], $res['status']);
        $this->assertEquals($expected['message'], $res['message']);
    }

    public function deleteRecipeByIdDataProvider()
    {
        $recipe = $this->createAnotherRecipe();
        return [
            [
                $recipe['_id'],
                [
                    'status' => 200,
                    'message' => 'Recipe deleted successfully'
                ]
            ],
            [
                '',
                [
                    'status' => 400,
                    'message' => 'Invalid recipe id'
                ]
            ],
        ];
    }

}