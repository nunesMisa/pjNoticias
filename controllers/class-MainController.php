<?php

require_once ABSPATH."/controllers/class-UserLogin.php";

/**
 * MainController - Todos os controllers deverão extender dessa classe
 * 
 * @package controller
 * @since 1.0
 */
class MainController extends UserLogin{

    /**
     * $db
     * 
     * Nossa conexão com a base de dos. Manterá o objeto PDO
     * 
     * @access public
     */
    public $phppass;

    /**
     * $title
     * 
     * Título das páginas
     * 
     * @access public
     */
    public $title;

    /**
     * $login_requered
     * 
     * Se a página precisa de login
     * 
     * @access public
     */
    public $login_requered = false;
    
    /**
     * $permission_required
     * 
     * Permissão necesária
     * 
     * @access public
     */
    public $permission_requered = "any";
    
    /**
     * $parametros
     * 
     * @access public
     */
    public $parametro = array();
    
    /**
     * Construtor da classe
     * 
     * Configura as propriedades e métodos da clase.
     * 
     * @since 1.0
     * @access public
     */
    public function __construct($parametros = array()) {
        //Instancia do DB
        $this->db = new TutsupDB();
        
        //Phpass
//        $this->phppass = new PasswordHash(8, false);
        
        //Paâmetros
        $this->parametro = $parametros;
        
        //Verifica o login
        $this->check_userlogin();
    }
    
    /**
     * Load model
     * 
     * Carrega os modelos presentes na pasta /models/
     * 
     * @since 1.0
     * @access public
     */
    public function load_model($model_name = false){
        //Um arquivo deverá ser enviadp
        if(!$model_name){
            return;
        }
        
        //Garante que o nome do modelo tenha letras minúsculas
        $model_name = strtolower($model_name);
        
        //Inclui o arquivo
        $model_path = ABSPATH."/models/".$model_name.".php";                
        
        //Verifica se o arquivo existe
        if(file_exists($model_path)){
            //Inclui o arquivo
            require_once $model_path;
            
            //Remove os caminhos do arquivo(se tiver algum);
            $model_name = explode("/", $model_name);
            
            //Pega só o nome final do caminho
            $model_name = end($model_name);
            
            //Remove caracteres inválidos do nome do arquivo
            $model_name = preg_replace("/[^a-zA-Z0-9]/is", "", $model_name);
            
            //Veridica se a classe existe
            if(class_exists($model_name)){
                //Retorna um objeto da clase
                return new $model_name($this->db, $this);
            }
            
            return;
        }        
    }
}
