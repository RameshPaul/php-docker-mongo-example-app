#!/usr/bin/env bash
# deployment script

COMMAND=$1

#exit the script if the version is not defined
if [ -z "$COMMAND" ]
  then
    echo "No command supplied! eg: ./bin/deploy.sh start"
    exit 1
fi

echo $COMMAND

case "$COMMAND" in
  "start")
      docker-compose up -d
      docker-compose run composer install
      docker-compose run composer dump-autoload
      ;;
  "stop")
      docker-compose down
      ;;
  "run-tests")
      docker-compose run php /server/http/web/vendor/bin/phpunit --configuration /server/http/web/phpunit.xml
      ;;
  *)
      echo "Invalid command. eg: ./bin/deploy.sh start, ./bin/deploy.sh stop, ./bin/deploy.sh run-tests"
      ;;
esac

echo "done!"
exit 0

