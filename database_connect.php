<?php
/* CONNECT TO LOCAL HOST */
function openConnection()
{
    $servername = "localhost"; //name of the server
    $username = "username"; //server login username
    $password = "password"; //server login password
    $database = "Users"; //database to connect to name

    $connection = new mysqli($servername, $username, $password, $database);

    return $connection;
}

/* Close local host */
function closeConnection($connection){
    $connection -> close();
}
?>