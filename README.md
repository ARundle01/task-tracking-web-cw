# task-tracking-web-cw

A web application which tracks tasks using database storage and a Web API. This is a piece of coursework and is not guaranteed to function or be secure.

## What is this Project?
This is my coursework for my Web Development module at University. The specification was to create a website using PHP, CSS, JavaScript and AJAX. The website was based on a task calender of sorts, with the user being able to create, delete, edit and mark tasks as completed. See the requirements below:
- create new tasks
- delete tasks
- edit existing tasks
- import tasks to Module Leader's custom API
- export tasks from Module Leader's custom API
- store tasks in a database on Uni Web Server
- register/login for users
- use the MVC architecture
- be resistant to SQL injections and XSS attacks (this code is not resistant to XSS)

## Installation and Dependencies

This code was written in PHP 5.6 using [PHPStorm](https://www.jetbrains.com/phpstorm/). The web server used MySQL, but has been ommitted from this code for security reasons. For this code to work with your own web server, the directory /mydir/ should be a sub-directory of your web server. This is where all of these files should be placed and accessed from. Unfortunately, the Web API that was used for import/export cannot be publically accessed and I cannot give any information on how that worked; it is unlikely that the import/export will be useful outside of the scope of this project but has been included just to show how it was done.

## Can I use this code?

You can use it, but I am not really sure how or why you would want to. This repo really only exists as a way of maintaining my this University project and being able to show it off as part of a portfolio.
