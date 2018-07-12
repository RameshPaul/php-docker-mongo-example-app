# Recipe-HelloFresh
Recipe-HelloFresh is a simple application to creation recipes.

## Step 1: Clone repository
- Download or clone the repository.

## Step 2: Run the application

#### Run Manually

Run the application with deployment script.
All these commands runs the application in docker container.

```sh
$ cd rameshpaul-api-test
$ sh bin/deploy.sh start
```
##### Supported commands
1.Start the application
```sh
$ sh bin/deploy.sh start
```
2.Stop the application
```sh
$ sh bin/deploy.sh stop
```
3.Run the application tests
```sh
$ sh bin/deploy.sh run-tests
```

## API Documentation
API document is generated with [postman](https://www.getpostman.com/), please [visit this link](https://documenter.getpostman.com/view/2887504/RW8ApUJ4)

**Note:** Since I have `http://localhost` running with apache, I configured `8080` port in application container that's why in [postman](https://www.getpostman.com/) docs url will be `http://localhost:8080`

## Run Tests

To run unit & integration tests.

#### With Docker
````
$ docker-compose run php /server/http/web/vendor/bin/phpunit --configuration /server/http/web/phpunit.xml
````

