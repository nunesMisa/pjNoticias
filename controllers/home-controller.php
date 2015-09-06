<?php

/**
 * home - controller exemplo
 * 
 * @package controller
 * @since 1.0
 */

class HomeController extends MainController{
    /**
     * Carrega a página "/views/home/home-view.php"
     */
    public function index(){
        //Título da página
        $this->title = "Home";
        
        //Parametros da função
        $parametros = (func_num_args() >= 1)? func_get_arg(0) : array();
        
        //Essa página não precisa de modelo(model)
        
        //Carrega todos os arquivos do view
        
        // /views/_includes/header.php
        require ABSPATH."/view/_includes/header.php";
        
        // /views/_includes/menu.php
        require ABSPATH."/view/_includes/menu.php";
        
        // /views/_includes/menu.php
        require ABSPATH."/view/_includes/menu.php";
        
        // /views/_includes/home-view.php
        require ABSPATH."/view/_includes/home-view.php";
        
        // /views/_includes/header.php
        require ABSPATH."/view/_includes/footer.php";
    }
}

?>

