<?php
/* CONNECT TO LOCAL HOST */
function openConnection()
{
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $database = "Users";

    $connection = new mysqli($servername, $username, $password, $database);

    return $connection;
}

function closeConnection($connection){
    $connection -> close();
}
?>