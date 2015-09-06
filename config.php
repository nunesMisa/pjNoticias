<?php
/**
 * Configuração geral
 */

//Caminho para a raiz
define("ABSPATH", dirname(__FILE__));

//Caminho para a pasta de uploads
define("UP_ABSPATH", ABSPATH."/views/_uploads");

//URL da home
define("HOME_URI", "http://localhost:8080/Cursos/crud");

//Nome do host da base de dados
define("HOSTNAME", "localhost:8080");

//Nome do DB
define("DB_NAME", "tutsup");

//Usuário do DB
define("DB_USER", "root");

//Senha do DB
define("DB_PASSWORD", "");

//Charset da conexão PDO
define("DB_CHARSET", "utf8");

//Caso esteja desenvolvendo
define("DEBUG", true);

require_once ABSPATH.'/loader.php';
?>

