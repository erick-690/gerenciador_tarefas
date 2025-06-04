<?php
// config.php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'gerenciador_tarefas');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERRO: Não foi possível conectar ao banco de dados. " . mysqli_connect_error());
}
?>