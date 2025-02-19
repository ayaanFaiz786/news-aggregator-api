# News Aggregator API

A RESTful API built using Laravel for a News Aggregator service that pulls articles from multiple sources and allows users to interact with the data. This API supports user authentication, article management, user preferences, data aggregation, and more.

# Setup Instructions
The project is Dockerized for easy setup and development environment.

### 1. Install Docker and Docker Compose:
Ensure you have Docker and Docker Compose installed. If not, follow the instructions in the official Docker documentation:
* [Install Docker](https://www.docker.com/get-started/)
* [Install Docker Compose](https://docs.docker.com/compose/install/)



### 2. Clone the repository:

Run the below commands to clone repository and install dependencies
```bash
git clone https://github.com/ayaanFaiz786/news-aggregator-api.git

cd news-aggregator-api

cp .env.example .env

composer install
```

### 3. Build and run the Docker containers:

```bash
  ./vendor/bin/sail build --no-cache
  ./vendor/bin/sail up
```

This will:

* Build the app and database containers.
* Start the containers for Laravel, MySQL, and other services.
* Make the Laravel application accessible at http://localhost:81


### 4. Run database migrations:
```bash
./vendor/bin/sail artisan migrate
```

### 5. Access the application:
Visit below link to access news aggregator api endpoints and documentation

http://localhost:81/api/documentation

To access database visit http://localhost:8080/
and enter credentials to access the local database
* Username: root
* Password: admin

### Stopping Docker Containers:
To stop the running container
```bash
./vendor/bin/sail stop
```
## Cron 
To fetch news articles from mutiple news apis set up a cron or manually run the below command.
```bash
vendor/bin/sail artisan news-aggregator:fetch-articles
```

### Test Cases
To run tests run the following command
```bash
vendor/bin/sail artisan test
```