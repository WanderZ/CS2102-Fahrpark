# Fahrgemeinschaft
Carpool in German (Fahrpark also)
This is a copy code repository.

## Credits
[Faruq](https://github.com/ruqqq)
[Yanxian](https://github.com/yanxian)
[Juliana](https://github.com/hexcone)
[Chun How](https://github.com/tanch88)

## Quickstart

### Requirements
- PHP 5.5.9
- Composer 1.0
- MySQL 5.5.9

### Setup
- Run `composer install`
- Copy `.env.example` to `.env`
- Edit `.env` to fit your configuration (Create the db, set the db info inside `.env`)
- if you need, create a new user & database in mysql:
  * Run `mysql` or `mysql -u root -p`
  * Enter password for root, if any
  * `CREATE DATABASE homestead;` To create a database - change homestead to dbname
  * `CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';`
  * `GRANT ALL PRIVILEGES ON * . * TO 'newuser'@'localhost';`
  * `FLUSH PRIVILEGES;`
  * `exit` to exit mysql command line control.
- Run `php artisan cs2102:create-tables`

### Start Development Server
- Run `php artisan serve --port 3000`
- Run `php artisan serve --host=0.0.0.0 --port=3000` // Alternative, if using nitrous.io

## Documentations
- [Laravel Tutorial (make a ToDo app)](https://www.flynsarmy.com/2015/02/creating-a-basic-todo-application-in-laravel-5-part-1)
- [Laravel Official Guide](http://laravel.com/docs/5.1/routing)

## Laravel Crash Course
Created specifically for CS2102 Project by Faruq

To be honest, you might not need to go through the tutorial linked above. You should only need to know PHP5, Laravel file structure, read **The Basics** from Laravel Official Guide and *finally*, lots of trial and error. I've written a short crash course which mention only the crucial information needed to get our project done.

Laravel follows the standard MVC pattern.
- Model: `app/Models`
- View: `resources/views`
- Controller: `app/Http/Controllers`

### Model
Laravel comes with Eloquent as its Model base class which allows the developer to use Active Record style querying. However, due to our module requirements, Faruq extended this base class for us able to handwrite our own queries. See `app/Models/User.php` for example, and `app/Models/CS2102Model.php` for the extended base class.

At the time of this writing, `CS2102Model` is incomplete.

### View
Imagine HTML with OOP. Minimum stuffs you should know about Laravel Blade syntax: `{{ ... }}`, `{!! ... !!}`, `@include`, `@extends`, `@stack`, `@yield`, `@section`, `@if`, `@foreach`. Google 'em and you're set to write your view files.

[Read more here](http://laravel.com/docs/5.1/blade and http://laravel.com/docs/5.1/views)

### Controller
Controllers are basically classes where you write your business logic. The data will then be passed to view for rendering. You write controllers with usual PHP5, along with the help of Laravel built-in libraries or even with third-party libraries installed via `composer` (Google it up!). To pass data to views, at the end of the function, do `return view('my-view', $data);` where `$data` is your array of data to pass to view.

[Read more here](http://laravel.com/docs/5.1/controllers and http://laravel.com/docs/5.1/views)

### Routes (`app/Http/routes.php`)
This is where you map URLs to Controllers. It allows regex and shit. Super convenient to use.

[Read more here](http://laravel.com/docs/5.1/routing)

### Artisan Tinker
One of the most powerful things about Laravel is its tinker command: Run `php artisan tinker` in the root directory of this repo. You will be presented a command line input where you can execute PHP commands in the context of the project. For example, you can run `\App\Models\User::createTable()` in the command line to create the table for `Users`. No more using `echo` to print debug stuff or just to run some functions which are not permanent to the source code.

### Clockwork Plugin
[Download the Chrome extension](https://chrome.google.com/webstore/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp?hl=en)

Bring up Chrome Developer panel on the website you deployed this project to and look into Clockwork tab. Can debug DB queries, or output logs here. 

[Read more](https://github.com/itsgoingd/clockwork)

## Coding Standards
[Refer here](http://laravel.com/docs/5.1/contributions#coding-style)
