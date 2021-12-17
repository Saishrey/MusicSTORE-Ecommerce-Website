# DBMS- Project 
# MusicSTORE Ecommerce Website

This repo contains the code for out MusicSTORE e-commerce website:

User can register an account, before login user need to verify his/her account. User will receive an OTP code sent by PHPMailer.

How to use this source code:
requirement:
1) install wamp
here is the link to install wamp (64-bit) 
https://www.wampserver.com/en/#wampserver-64-bits-php-5-6-25-php-7

First step:
1) download this repo 
2) create a folder name as DBMS-Project -> extract to your wamp64 folder -> www -> on folder DBMS-Project
3) go to phpmyadmin -> create database 'dbmsproject' 
4) copy all the query command from SQL.sql -> paste it under the database 'dbmsproject' sql.
5) modify the account and password under the file functions.php to your email id and password
6) modify the admin email which you want to set, in the login.php file (make sure that the email and password for admin is present in the database)
7) NOTE: inorder to send email through php mailer, you have to first enable Less secure app access for your email
        -> to do that go to your gmail
        -> click on Manage your Google account
        -> Click on security
        -> scroll down till you find Less secure app access
        -> turn it on
6) now you are ready to run your RDBMS E-commerce project!
7) Happy Coding


If you get an error saying 
"Fatal error: Uncaught PDOException: SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it. in C:\wamp64\www\DBMS\private\connection.php on line 14"

-> then go to connection.php
-> under define('DB_HOST', "localhost:3308"); 
    remove ':3308' and try

 

More details refer to this youtube video with clear explanation
https://youtu.be/-1SJPDL-9o8
