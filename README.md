# TaskManagerApp

A system to manage users and their tasks.



\# Task Manager Application With PHP Symfony along with Docker Project



\## Overview

This project is a Symfony application for user task management within a Dockerized environment. It uses `docker-compose` to run all the necessary services, such as the web server PHP-FPM, and a database (e.g., MySQL or PostgreSQL). This setup ensures a consistent and isolated development environment for all developers, regardless of their operating system.



\## Prerequisites



PHP >= 8.4



Composer



Symfony CLI (optional but recommended)



MySQL



Docker 



\## Installation



\### 1. Create the repository in GitHub and provide public access.



\### 2. Clone the repository

First, clone this repository to your local machine using the following command:



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

   └── index.php



Run docker-compose up -d --build  \[ for building docker containers ]



Run docker-compose exec -it app\_container bash \[ for entering to application container ]



Run symphony new . --webapp --no-git \[ for setting up symfony application ]



Set up .env database URL 



DATABASE\_URL="mysql://symfony:symfony@db:3306/symfony?serverVersion=8.3"



Run php bin/console cache:clear



Run php bin/console doctrine:database:create



Run docker-compose exec -it db\_container bash \[ for entering to database container ]



Check DB and related migration creation.



\### 4. Entity Creation



User Entity:



Run php bin/console make:entity User



Fields: id, name, email, roles, password



Relations: OneToMany with Task



Task Entity:



Run php bin/console make:entity Task



Fields: id, title, description, status (todo, in-progress, done)



Relations: ManyToOne with User





\### 5. Run php bin/console make:migration 



Run php bin/console doctrine:migrations:migrate



Insert Admin User:



INSERT INTO user (name, email, roles, password) VALUES ('admin', 'admin@example.com', '\["ROLE\_ADMIN"]', '$2y$13$5EdZcAG/dQbeLULcL7/aJ.ujNuwNYyur8oTstyfemhH7KZd6ocqxK');



Generate hased password using:



Run php bin/console security:hash-password admin



Now migrations are created and tables are ready to serve.



\### 6. Create controller action to serve pages into frontend.



php bin/console make:controller HomeController 



It will create: src/Controller/HomeController.php

               templates/home/index.html.twig



Create UserController \& Taskcontroller for API creation



php bin/console make:controller Api/UserController



\[It will create src/Controller/Api/UserController.php

 	       templates/api/user/index.html.twig]





php bin/console make:controller Api/TaskController



\[It will create src/Controller/Api/TaskController.php

	       templates/api/task/index.html.twig]





If required we can enable phpTestunit reports as well.



\### 9. Accessing API's



For creating user:

curl -X POST http://localhost:8000/api/users \\

-H "Content-Type: application/json" \\

-d '{

 "name": "test",

 "email": "test@example.com",

 "password": "admin",

 "roles": \["ROLE\_ADMIN"]

}'



For getting user by using userid:

curl -X GET http://localhost:8000/api/users/{userid}



For listing all users:

curl -X GET http://localhost:8000/api/users



For updating users:

curl -X PATCH http://localhost:8000/api/users/6 \\

-H "Content-Type: application/json" \\

-d '{

 "name": "test",

 "email": "test@example.com",

 "password": "admin",

 "roles": \["ROLE_ADMIN","ROLE_USER"]

}'


For deleting users:

curl -X DELETE http://localhost:8000/api/users/{userid}



For list all tasks for a user:

curl -X GET http://localhost:8000/api/tasks/users/{userid}



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





\### 10. Console command to generate number of tasks(count) per user as per status

To get all users

Run php bin/console app:tasks:report

To get specific user

Run php bin/console app:tasks:report --user=admin@example.com



\### 10. Administration Interface



To go to dashboard http://localhost:8080/admin



To go to User dashboard http://localhost:8080/admin/users



To go to Task dashboard http://localhost:8080/admin/tasks


\### 11. Security \& Admin Login


1.Configure Security in config/packages/security.yaml:



security:

   password\_hashers:

       App\\Entity\\User:

           algorithm: auto



   providers:

       app\_user\_provider:

           entity:

               class: App\\Entity\\User

               property: email



   firewalls:

       main:

           lazy: true

           provider: app\_user\_provider

           custom\_authenticator: App\\Security\\LoginFormAuthenticator

           logout:

               path: app\_logout

               target: app\_login



   access\_control:

       - { path: ^/admin, roles: ROLE\_ADMIN }

2.Create LoginFormAuthenticator.php and SecurityController.php for login/logout routes.



3.Twig login template: templates/security/login.html.twig 



4.CRUD for Users \& Tasks



Admin Dashboard: templates/admin/dashboard.html.twig



Links to Users and Tasks



Users Management:



List: admin/users/list.html.twig



Create/Edit Form: admin/users/form.html.twig



Actions: Create, Edit, Delete



Tasks Management:



List: admin/tasks/list.html.twig



Create/Edit Form: admin/tasks/form.html.twig



Actions: Create, Edit, Delete



Status dropdown (todo, in-progress, done)



Assign user to task via select dropdown



Controllers:



AdminUserController.php → User CRUD



AdminTaskController.php → Task CRUD



5\. Running the Application



Start Symfony server:



symfony server:start





Access the application:



http://localhost:8000/login





Login with admin credentials and manage Users \& Tasks.

Routes

Route	Description

/login	Admin login

/logout	Logout

/admin	Admin Dashboard

/admin/users	List Users

/admin/users/create	Create User

/admin/users/{id}/edit	Edit User

/admin/users/{id}/delete	Delete User

/admin/tasks	List Tasks

/admin/tasks/create	Create Task

/admin/tasks/{id}/edit	Edit Task

/admin/tasks/{id}/delete	Delete Task



Notes


Roles: Only users with ROLE\_ADMIN can access /admin/\* routes.

Validation: Ensure email is unique and status is one of allowed choices.

CSRF: All delete forms include CSRF token.

Design: Bootstrap 5 with badges, cards, and centered forms.















