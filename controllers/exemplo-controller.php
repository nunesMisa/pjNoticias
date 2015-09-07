<?php

class ExemploController extends MainController{
    //URL: dominio.com/exemplo/
    public function index(){
        //Carrega o modelo
        $modelo = $this->load_model("exemplo/exemplo-modelo");
        
        //Carrega o view
        require_once ABSPATH."/view/exemplo/exemplo-view.php";      
    }
}

?>

