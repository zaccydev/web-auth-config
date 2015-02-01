# web-auth-conf
## What's this app ?
This a small command line application which provide ability to easily set user, group and permission for a Web project.
The concept of the app is based on this main point:
User (you) want to be able to modify, remove and read each files of the Web project.
The Web server should not be able to modify files in the project, with the exception of some directories depending on the type of project you use.
Two project's type are defined by default (Symfony project and Drupal project). For a Symfony project app/logs and app/cache must be writtable by the server, for a Drupal project the sites/default/files project is concerned. 
The configuration file (src/wac-conf.json) allow to define other projects like you want.
The main point is that the **server acl will be set for the group of the server**, so the user property would be your logname and the group property would be the server group (e.g.: www-data). 
## First step
Once you retrieved the files the following command will install dependencies (mainly the Symfony console component):
*composer install* or
*php composer.phar install* if composer is not in the path
> see [how to install composer](https://getcomposer.org/doc/00-intro.md) if you don't use it yet. 
## Second step 
Next the only thing to do is to set the user and the group properties in src/wac-conf.json
## Test
To test the application run *php web-auth-conf.php --path /my/project* from your shell
Note that --debug option allow to show all the command generated and act like a safe mode without modify anyhting.
The --full option will add more task by allowing to set user and group permission on the whole project.
## Install
If you want to use the app from anywhere (not only from directory where you have downloaded this package), run the *install* command ('ll ask su privilege).
**sh install**
Once this is done you can run the command from where you want with *web-auth-conf*, also if you use it from your project root you no more have to specify the path of the project.

