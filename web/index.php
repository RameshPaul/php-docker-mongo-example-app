<?php
use FastRoute\RouteCollector;
use App\Auth\Auth;
use App\Config\Config;

require __DIR__ . '/vendor/autoload.php';
$auth = new Auth(Config::getDBConfig());

//Define routes
$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/recipes', ['App\Controller\RecipeController', 'getAll']);
    $r->addRoute('POST', '/recipes', ['App\Controller\RecipeController', 'create', 'Auth:Secure']);
    $r->addRoute('GET', '/recipes/{id}', ['App\Controller\RecipeController', 'getById']);
    $r->addRoute('PUT', '/recipes/{id}', ['App\Controller\RecipeController', 'update', 'Auth:Secure']);
    $r->addRoute('DELETE', '/recipes/{id}', ['App\Controller\RecipeController', 'deleteById', 'Auth:Secure']);
    $r->addRoute('POST', '/recipes/{id}/rate', ['App\Controller\RecipeController', 'rateRecipe']);
});

$route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'])['path']);

//Catch routes
switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        //Do auth check if specified
        if (in_array('Auth:Secure', $controller)) {
            $auth->authorize();
            unset($controller[2]);
        }

        //Bundle input/request data into an array
        $rawData = file_get_contents("php://input");

        if (!empty($rawData)) {
            $rawData = (array)json_decode($rawData);
        } else {
            $rawData = $_REQUEST;
        }

        $parameters['data'] = $rawData;

        //Call controller & associated methods with params
        $cls = new $controller[0]();
        $response = $cls->{$controller[1]}(...array_values($parameters));

        header("HTTP/1.1 " . $response['status']);

        $jsonResponse = json_encode($response);

        echo $jsonResponse;

        break;
}
