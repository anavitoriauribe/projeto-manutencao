<?php

$host = "192.168.10.77";
$senha = "1234";
$banco = "manutencao";
$usuario = "postgres";

$pdo = new PDO (

    "pgsql:host=$host;port=5432;dbname=$banco",$usuario,$senha 
);






