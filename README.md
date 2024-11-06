# Sales management system

System I made as a part of a Laravel course at Udemy

## Features

- Poduct, Client, User CRUDs
- Sales creation and email receipt 
- Dashboard with information about each table

## Tecnologies 

- PHP 8.1
- Laravel 10.48.22
- Docker
- MySQL
- JavaScript
- Bootstrap

## Installation

### Requirements
- Docker
- Git 


### Setting up and running
1. Clone the repository

    - Navigate to a place you would like to clone the repository

    ```sh
    git clone https://github.com/FernandoROL/Laravel_sales_manager.git
    ```


2. Make sure you are operating from inside the repository
    ```sh
    cd ~/{path-to-repository}/Laravel_sales_manager
    ```


3. Create a `.env` file with the `.env.example` and adjust it to your liking

    - Copy and rename the `.env.example` to `.env` by hand and edit to your liking

        + or

    - Run the command to do it for you
        ```sh
        cp .env.example .env
        ```


4. Run the image using `docker-compose`

    ```sh
    docker compose up -d
    ```


5. Get inside the docker container 

    ```sh
    docker compose exec -it sistema_gestao bash
    ```
    `sistema_gestao` is the current container name and can be changed


6. Install the dependencies 

    ```sh
    composer install
    ```


7. Generate your `app_key`

    ```sh
    php artisan key:generate
    ```


8. Run the migrations for the database 

    ```sh
    php artisan migrate
    ```


9. OPTIONAL - Seed the database to populate with fictional data

    ```sh
    php artisan db:seed
    ```


### Running the app

- Follow the links below:

    - Main application: http://localhost:8989/
    - phpMyAdmin database: http://localhost:8899/