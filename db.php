<?php
require_once "config.php";

function OpenConnection()
{

    $conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME)
    or die("Connection failed: %s\n" . $conn->error);
    return $conn;

}

function CloseConnection()
{
    $conn->close();
}