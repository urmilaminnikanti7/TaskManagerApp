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



Run php bin/console make:entity User 



Entity and Repository will create. Follow the setup instructions for creating fields.



Run php bin/console make:entity Task



Entity and Repository will create. Follow the setup instructions for creating fields.



Run php bin/console make:migration 



Run php bin/console doctrine:migrations:migrate



Now migrations are created and tables are ready to serve.



Create controller action to serve pages into frontend.



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











