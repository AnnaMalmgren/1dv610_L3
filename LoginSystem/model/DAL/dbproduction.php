<?php

function setDBconn() 
{
    $dbHost = getenv("DB_HOST");
    $dbUsername = getenv("DB_USERNAME");
    $dbPassword = getenv("DB_PASSWORD");
    $dbName = getenv("DB_DATABASE");

   return  mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
}



