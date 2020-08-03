## Requirements
- Docker daemon 
- docker-compose

## Notes
- I didn't provide a CONFIG FILE because with this solution I preferred to put ENV variables in the container definition in the docker-compose.yml file (that can be easily overridden by the container orchestrator env configuration)
- There was no specification about using php FastCGI or php-fpm so, I preferred the fpm version for its advantages over the FastCGI version: php itself will handle the process management and the process children instead of the web server 

## Project structure
- app folder: here you can find the whole application that will be mounted in the PHP container
- docker folder: here you can find all the container specific initialization files and volumes

## How to start the project
Pull docker images and initialize mysql container with database, schema and data. \
You can find SQL files to create tables and seed data in **./docker/mysql/data/** folder
``` bash
docker-compose build
```
Run the containers.
``` bash
docker-compose up
```

You can consume the application at http://localhost:8080 , here you will find a page with some useful link you can use to test some of th scenarios (pagination, parameters validation)


