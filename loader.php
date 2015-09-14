<?php

//Evita que usuários acessem este arquivo diretamente

if(!defined("ABSPATH")){
    exit;
}

//Inicia a sessão
session_start();

//Verifica o modo para debugar
if(!defined("DEBUG") || DEBUG == FALSE){
    //Esconde todos os erros
    error_reporting(0);
    ini_set("display_errors", 0);
}else{
    //Mostra todos os erros
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set("display_errors", 1);
}

//Funções globais
require_once ABSPATH.'/functions/global-functions.php';

//Carrega a aplicação
$tutsup_mvc = new TutsupMVC();
?>

