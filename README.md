# “Time Tracker” Developer Exercise
   
## Usage

1. Create the .env file with your environment parameters

2. Build the images `docker compose build --no-cache`

3. Run the containers `docker compose up -d --wait`

To stop and remove the containers `docker compose down --remove-orphans`

## ENV example

APP_ENV=dev

APP_SECRET=60e87637c840babebb479f08c3159fe3

MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0

DATABASE_URL="postgresql://app:!ChangeMe!@database:5432/time-tracker?serverVersion=16&charset=utf8"

POSTGRES_PASSWORD=!ChangeMe!

POSTGRES_USER=app

POSTGRES_DB=app

POSTGRES_VERSION=16

POSTGRES_CHARSET=utf8

DB_HOST_PORT=5432

APP_TIMEZONE=Europe/Madrid

APP_THEME=base

CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'

## API endpoints

### Get all the tasks
- GET /api/task/all
   
### Start a new task 
- POST /api/task/start
    - Params in the body as JSON:
        - name: Name of the task
    ```json
    { "name": "Task 1" }
    ```

### End a task
- POST /api/task/end
    - Params in the body as JSON:
        - name: Name of the task
    ```json
    { "name": "Task 1" }
    ```
    
## Console commands

### List tasks
- `./bin/console app:task:list` or from the host `docker exec -it time-tracker-backend-php-1 sh -c "./bin/console app:task:list"`

### Start a new task
- `./bin/console app:task:start` or from the host `docker exec -it time-tracker-backend-php-1 sh -c "./bin/console app:task:start"`
    - Params:
        - name: Name of the task
### End the task
- `./bin/console app:task:end` or from the host `docker exec -it time-tracker-backend-php-1 sh -c "./bin/console app:task:end"`
    - Params:
        - name: Name of the task
    
## Tests

Run `./vendor/bin/phpunit` to execute the tests