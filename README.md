## About App

Admin panel built with Laravel 8, Jetstream, Inertia, AdminLTE, Spatie's Laravel-permissions, Jetstrap, and Bootstrap 5 for Role and Permission Management

## How To Use

-   Clone/ Download the source code
-   go to public folder and inside that folder, run rm storage in terminal, to delete storage folder
-   go back to the root project, then run php artisan storage:link in terminal, to create new storage folder
-   go to storage/app/public, create new folder, name it "images"
-   download all images from https://www.dropbox.com/sh/mve2m5s0zdbmodl/AACCpwAdZeniPxa9KNQn33Qza?dl=0, put them inside the "images" folder that you've just created
-   go to .ENV file to setup database
-   start your server ( XAMPP or other services ) then create new database with the exact name as DB_DATABASE in .ENV
-   make sure that the port and the host is the same as DB_HOST and DB_PORT
-   type php artisan migrate:fresh in console to migrate the database
-   type npm install in console terminal
-   type php artisan serve in console terminal
-   type npm run watch in console terminal

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
