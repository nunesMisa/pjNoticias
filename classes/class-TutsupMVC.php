<?php
/**
 * TutsupMVC - Gerencia Models, Controllers e Views
 * 
 * @package TutsupMVC
 * @since 1.0
 */

class TutsupMVC{
    
    /**
     * $controlador
     * 
     * Receberá o valor do controlador (Vindo da URL)
     * exemplo.com/controlador
     * 
     * @access private
     */
    private $controlador;
            
    /**
     * $acao
     * 
     * Receberá o valor da ação (Também vem da URL)
     * exemplo.com/controlador/acao
     * 
     * @access private
     */
    private $acao;
    
    /**
     * $parametros
     * 
     * Receberá um array dos parâmetros (Também vem da URL)
     * exemplo.com/controlador/acao/param1/param2/param50
     * 
     * @access private
     */
    private $parametros;
    
    /**
     * $not_found
     * 
     * Caminho da página não encontrada
     * 
     * @access private
     */
    private $not_found = "/includes/404.php";
    
    /**
     * Construtor para essa classe
     * 
     * Obtém os valores do controlador, ação e parâmetros
     * o controlador e a ação (métodos)
     */
    public function __construct() {
        //Obtém os valores do controlador, ação e parâmetros da URL
        //E configura as propriedades da classe
        $this->get_url_data();
        
        /**
         * Verifica se o controlador existe. Caso contrário, adiciona o
         * controlador padrão (controllers/home-controller.php) e chama o método index()
         * 
         */
        if(!$this->controlador){
            //Adiciona o controladpr padrão
            require_once ABSPATH."/controllers/home-controller.php";
            
            //Cria o objeto do controlador "home-controller.php"
            //Este controlador deverá ter uma classe chamada HomeController
            $this->controlador->index();
            
            return;
        } 
        
        //Se o arquivo do controlador não existir, não faremos nada
        if(!file_exists(ABSPATH."/controllers/".$this->controlador.".php")){
            //Página não encontrada
            require_once ABSPATH.$this->not_found;
            
            return;
        } 
        
        //Inclui o arquivo do controlador
        require_once ABSPATH."/controllers/".$this->controlador.".php";
        
        //Remove caracteres inválidos do nome do controlador para gerar o nome
        //da classe. Se o arquivo chmar "news-controller.php", a classe deverá 
        //se chamar "NewController"
        $this->controlador = preg_replace("/[^a-zA-Z]/i", "", $this->controlador);
        
        //Se a classe do controlador indicado não existir, não faremos nada
        if(!class_exists($this->controlador)){
            //Página não encontrada
            require_once ABSPATH.$this->controlador;
            
            return;
        }
        
//        die("aaa");
//        die("$this->controlador = new $this->controlador($this->parametros);");
        
        //Cria o objeto da classe do controlador e envia os parâmetros
        $this->controlador = new ExemploController();// $this->controlador($this->parametros);
        
        //Se o método indicado existir, executa o método e envia os parâmetros
        if(method_exists($this->controlador, $this->acao)){
            $this->controlador->{$this->acao}($this->parametros);
            
            return;
        }    
        
        //Sem ação, chamamos o método index
        if(!$this->acao && method_exists($this->controlador, "index")){
            $this->controlador->index($this->parametros);
            
            return;      
        }
        
        //Página não encontrada
        require_once ABSPATH.$this->not_found;
        
        return;
    }
    
    /**
     * Obtém parâmetros de $_GET['path']
     * 
     * Obtém os parâmetros de $_GET['path'] e configura as propriedades
     * $this->controlador, $this->acao e $this->parametros
     * 
     * A URL deverá ter o seguinte formato:
     * http://www.exemple.com/controlador/acao/parametro1/parametro2/etc...
     */
    public function get_url_data(){
        //Verifica se o parâmetro path foi enviado
        if(isset($_GET["path"])){
            //Captura o valor de $_GET['path']
            $path = $_GET["path"];
            
            //Limpa os dados
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
            
            //Cria um array de parâmetros
            $path = explode("/", $path);
            
            //Configura as propriedades
            $this->controlador  = chk_array($path, 0);
            $this->controlador .= "-controller";
            $this->acao = chk_array($path, 1);
            
            //Configura os parâmetros 
            if(chk_array($path, 2)){
                unset($path[0]);
                unset($path[1]);
                
                //Os parâmetros sempre virão após a ação
                $this->parametros = array_values($path);               
            }
            //DEBUG
//            echo $this->controlador."<br>";
//            echo $this->acao."<br>";
//            echo "<pre>";
//            print_r($this->parametros);
//            echo "</pre>";
//            die("");
        }
    }
}   

?>

