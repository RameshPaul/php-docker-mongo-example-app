<?php
namespace Tests\Api;

use App\Config\Config;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class RecipeApiTest extends TestCase
{
    private $recipeId;
    private $apiUrl;
    private $client;
    private $auth;

    public function setUp()
    {
        $config = Config::getDBConfig();
        $this->apiUrl = $config['apiUrl'];
        $this->client = new Client();
        $this->auth = [$config['authUser'], $config['authPassword']];
    }

    public function testAuthentication()
    {
        //$this->expectOutputString('');
        //print_r($this->apiUrl);
        //flush();
        $response = $this->client->request('GET', $this->apiUrl, ['auth' => $this->auth]);
        $this->assertEquals(200, $response->getStatusCode(), 'Test authentication 200');
    }

    public function testAddRecipe()
    {
        $data = ['auth' => $this->auth];
        $data['body'] = ('{"name":"Chicken tikka biryani","prepTime":60,"difficulty":3,"vegetarian":false}');

        $response = $this->client->request('POST', $this->apiUrl . '/recipes', $data);
        $this->assertEquals(200, $response->getStatusCode(), 'Add Recipe - check 200 status code');
        $resData = (array)json_decode($response->getBody());
        $this->assertEquals(200, $resData['status'], 'Add Recipe - check status response is 200');
        $this->assertNotEmpty($resData['data']->_id, 'Add Recipe - check recipe id is not empty');
        $this->recipeId = $resData['data']->_id;
        return $resData['data']->_id;
    }

    public function testListRecipe()
    {
        $response = $this->client->request('GET', $this->apiUrl . '/recipes', ['auth' => $this->auth]);
        $this->assertEquals(200, $response->getStatusCode(), 'List Recipe');
    }

    /**
     * @depends testAddRecipe
     */
    public function testEditRecipe($recipeId)
    {
        $data = ['auth' => $this->auth];
        $data['body'] = ('{"name":"Edit Chicken tikka biryani","prepTime":60,"difficulty":3,"vegetarian":false}');
        $response = $this->client->request('PUT', $this->apiUrl . '/recipes/' . $recipeId->{'$oid'}, $data);
        $this->assertEquals(200, $response->getStatusCode(), 'Edit Recipe - check 200 status code');
        $resData = (array)json_decode($response->getBody());
        $this->assertEquals(200, $resData['status'], 'Edit Recipe - check status response is 200');
    }

    /**
     * @depends testAddRecipe
     */
    public function testDeleteRecipe($recipeId)
    {
        if (empty($recipeId)) {
            return;
        }

        $data = ['auth' => $this->auth];
        $response = $this->client->request('DELETE', $this->apiUrl . '/recipes/' . $recipeId->{'$oid'}, $data);
        $this->assertEquals(200, $response->getStatusCode(), 'Delete Recipe - check 200 status code');
        $resData = (array)json_decode($response->getBody());
        $this->assertEquals(200, $resData['status'], 'Delete Recipe - check status response is 200');
    }
}