## Requirements
- Docker daemon 
- docker-compose

## Notes
- This project is made to run on docker, if you wish to  run the application in custom apache server:
    - copy the content of the `app` folder in the virtual host root folder
    - set the environment variables listed in the `# application vars` section of the .env file
    - initialize the database with the sql files in the `./docker/mysql/data` folder.
    
- I didn't provide a CONFIG FILE because with this solution I preferred to put ENV variables in the container definition in the docker-compose.yml file (that can be easily overridden by the container orchestrator env configuration) 
in order to override default env variables modify the `.env` file in the root folder
- There was no specification about using php `FastCGI` or `php-fpm` so, I preferred the fpm version for its advantages over the FastCGI version: php itself will handle the process management and the process children instead of the web server
- I enabled `RewriteEngine` (and created .htaccess file) so that I could use a routing class to handle the application routes and set the application ready for pretty urls.  

## Project structure
- `app` folder: here you can find the whole application that will be mounted in the PHP container
- `docker` folder: here you can find all the container specific initialization files and volumes

## How to start the project
Build custom docker images

``` bash
docker-compose build
```
Pull docker images, initialize `mysql container` with database, schema and data and run the containers. \
You can find `SQL files` to create tables and seed data in `./docker/mysql/data/` folder
``` bash
docker-compose up
```

You can consume the application at http://localhost:8080 , here you will find a page with some useful link you can use to test some of th scenarios (pagination, parameters validation)


