## Set up project

```
git clone https://github.com/phucuongit/covid-nien-luan.git
yarn add -g lerna
lerna bootstrap
```

## Start project admin-api

Switch to the repo folder

    cd ./apps/admin-api

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Generate a new JWT authentication secret key

    php artisan jwt:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    yarn run admin:dev start

You can now access the server at http://localhost:8000

## Start project template-ui

```
yarn run template:dev start
```
