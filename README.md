# TaskManagerApp

A system to manage users and their tasks.



\# Task Manager Application With PHP Symfony along with Docker Project



\## Overview

This project is a Symfony application for user task management within a Dockerized environment. It uses `docker-compose` to run all the necessary services, such as the web server PHP-FPM, and a database (e.g., MySQL or PostgreSQL). This setup ensures a consistent and isolated development environment for all developers, regardless of their operating system.



\## Prerequisites

\-   \[\*\*Docker\*\*](https://www.docker.com/get-started) (engine and CLI)

\-   \[\*\*Docker Compose\*\*](https://docs.docker.com/compose/install/)



\## Getting Started



\### 1. Create the repository in GitHub and provide public access.



\### 2. Clone the repository

First, clone this repository to your local machine using the following command:

```sh

git clone https://github.com/urmilaminnikanti7/TaskManagerApp.git

cd TaskManagerApp



\### 3. Setup Docker Containers with required versions and packages and Symphony CLI and composer

.

├── docker-compose.yml

├── nginx

│   └── default.conf

├── php

│   └── Dockerfile

└── public

&nbsp;   └── index.php



Run docker-compose up -d --build  \[ for building docker containers ]



Run docker-compose exec -it app\_container bash \[ for entering to application container ]



Run symphony new . --webapp --no-git \[ for setting up symfony application ]



Set up .env database URL 



DATABASE\_URL="mysql://symfony:symfony@db:3306/symfony?serverVersion=8.3"



Run php bin/console cache:clear



Run php bin/console doctrine:database:create



Run docker-compose exec -it db\_container bash \[ for entering to database container ]



Check DB and related migration creation.



\### 4. Run php bin/console make:entity User 



Entity and Repository will create. Follow the setup instructions for creating fields.



\### 5. Run php bin/console make:entity Task



Entity and Repository will create. Follow the setup instructions for creating fields.



\### 6. Run php bin/console make:migration 



Run php bin/console doctrine:migrations:migrate



Now migrations are created and tables are ready to serve.



\### 7. Create controller action to serve pages into frontend.



php bin/console make:controller HomeController 



It will create: src/Controller/HomeController.php

&nbsp;               templates/home/index.html.twig



Create UserController \& Taskcontroller for API creation



php bin/console make:controller Api/UserController



\[It will create src/Controller/Api/UserController.php

 	       templates/api/user/index.html.twig]





php bin/console make:controller Api/TaskController



\[It will create src/Controller/Api/TaskController.php

&nbsp;	       templates/api/task/index.html.twig]





If required we can enable phpTestunit reports as well.



\### 8. Accessing API's



For creating user:

curl -X POST  http://localhost:8000/api/users -H "Content-Type: application/json"   -d '{"name":"test","email":"test@example.com"}'



For getting user by using userid:

curl -X GET http://localhost:8000/api/users/{userid}



For listing all users:

curl -X GET http://localhost:8000/api/users



For updating users:

curl -X PATCH http://localhost:8000/api/users/{userid} -H "Content-Type: application/json"   -d '{"name":"test","email":"test@example.com"}'





For deleting users:

curl -X DELETE http://localhost:8000/api/users/{userid}



For list all tasks for a user:

curl -X GET http://localhost:8000/api/tasks/user/{userid}



For listing tasks:

curl -X GET  http://localhost:8000/api/tasks



For getting task by using taskid:

curl -X GET http://localhost:8000/api/tasks/{taskid}



For creating task:

curl -X POST  http://localhost:8000/api/tasks   -H "Content-Type: application/json"   -d '{"userId":1, "title":"Learn Symfony", "description":"Build API", "status":"todo"}'



For updating status:

curl -X PATCH  http://localhost:8000/api/tasks/{taskid}   -H "Content-Type: application/json"   -d '{"status":"todo"}'



For deleting task:

curl -X DELETE  http://localhost:8000/api/tasks/{taskid}





\### 9. Console command to generate number of tasks(count) per user as per status

Run php bin/console app:tasks:report --user="test@example.com"



























