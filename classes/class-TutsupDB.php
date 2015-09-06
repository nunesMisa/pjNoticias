<?php

/**
 * TutsupDB - Classe para gerenciamento da bse de dados
 * 
 * @package Tutusup
 * @since 0.1
 */
class TutsupDB {

    /** Propriedades DB */
    public $host = "localhost", //Hosst da base de dados
            $db_name = "tutsup", //Nome do banco de dados
            $user = "root", //usuário da base de dados
            $password = "", //Senha do usuário da base de dados 
            $charset = "utf8", //Cherset da bse de dados
            $pdo = null, //Nossa conexão com o BD
            $error = null, //Configura o erro
            $debug = false, //Mostra todos os erros
            $last_id = null;          //Último ID inserido 

    /**
     * Construtor da classe
     * 
     * @since 1.0
     * @access public
     * @param string $host
     * @param string $db_name
     * @param string $user
     * @param string $password
     * @param string $charset
     * @param string $debug
     */

    public function __construct($host = null, $db_name = null, $user = null, $password = null, $charset = null, $debug = null) {
        // Configura as propriedades novamente.
        // Se você fez isso no início dessa classe, as constantes não serão
        // necessárias. Você escolhe...
        $this->host = defined('HOSTNAME') ? HOSTNAME : $this->host;
        $this->db_name = defined('DB_NAME') ? DB_NAME : $this->db_name;
        $this->password = defined('DB_PASSWORD') ? DB_PASSWORD : $this->password;
        $this->user = defined('DB_USER') ? DB_USER : $this->user;
        $this->charset = defined('DB_CHARSET') ? DB_CHARSET : $this->charset;
        $this->debug = defined('DEBUG') ? DEBUG : $this->debug;

        //Conecta
        $this->connect();
    }

    /**
     * Cria a conexão PDO
     * 
     * @since 1.0
     * @final
     * @access protected
     */
    final protected function connect() {
        /* Os detalhes da nossa conexão PDO */
        $pdo_details = "mysql:host={$this->host}";
        $pdo_details .= "dbname={$this->db_name}";
        $pdo_details .= "charset={$this->charset}";

        //Tenta conectar
        try {
            $this->pdo = new PDO($pdo_details, $this->user, $this->password);

            //Verifica se devemos debugar
            if ($this->debug === true) {
                //Configura o PDO ERROR MODE
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }

            //Não precisamos mais dessas propriedades
            unset($this->host);
            unset($this->db_name);
            unset($this->user);
            unset($this->password);
            unset($this->charset);
        } catch (PDOException $e) {
            //Verifica se devemos debugar
            if ($this->debug === true) {
                //Mostra a mensagem de erro
                echo "Erro: " . $e->getMessage();
            }
            //Kill the script;
            die();
        }
    }
    
    /**
     * query - Consulta PDO
     * 
     * @since 1.0
     * @access public
     * @return object|bool Retorna a consulta ou falso
     */
    public function query($stmt, $data_array = null){
        //Prepara e executa
        $query       = $this->pdo->prepare($stmt);
        $check_exec  = $query->execute($data_array);
        
        //Verifica se a consulta aconteceu
        if($check_exec){
            return $query;
        }else{
            //Configura o erro
            $error          = $query->errorInfo();
            $this->error    = $error[2];
            
            //Retorna falso
            return false;
        }
    }
    
    /**
     * insert - Insere valores
     * 
     * Insere os valores e tenta retornar o último id enviado
     * 
     * @since 1.0
     * @access public
     * @param string $table O nome da tabela
     * @param array ... Ilimitado número de arrays com chaves e valores
     * @return object|bool Retorna a consulta ou falso
     */
    public function insert($table){
        //Configura o array de coluna
        $cols = array();
        
        //Configura o valor inicial do modelo
        $place_holders = "(";
        
        //Configura o array inicial de alores
        $values = array();
        
        //O $i irá assegurar que as colunas serão configuradas apenas uma vez
        $numCols = 1;
        
        //Obtém os argumentos enviados
        $data = func_get_args();
        
        //É preciso pelo menos um array de chaves e valores
        if(!isset($data[1]) || !is_array($data[1])){
            return;
        }
        
        //Faz um laço nos argumentos
        for($i = 1; $i < count($data); $i++){
            //Obtém as chaves com colunas e valores como valores
            foreach ($data[$i] as $cols => $val){
                //A primeira volta do laço configura as colunas
                if($i === 1){
                    $cols[] = "`$col`";
                }
                
                if($numCols <> $i){
                    //Configura os divisores
                    $place_holders .= '), (';
                }
                
                //Configura os places holders do PDO
                $place_holders .= "?, ";
                
                //Configura os valores que vamos enviar
                $values[] = $val;
                
                $numCols = $i;
            }
            
            //Removeos caracteres extras dos place holders
            $place_holders = substr($place_holders, 0, strlen($place_holders) - 2);
        }
        
        //Separa as colunas por vírgula
        $cols = implode(", ", $cols);
        
        //Cria a declaração para enviar ao PDO
        $sqlInsert = "INSERT INTO `{$table}` ({$cols} VALUES {$place_holders})";
        
        //Insere os valores
        $qryInsert = $this->query($stmt, $values);
        
        //Verifica se a inserção foi realizada com sucesso
        if($qryInsert){
            //Verifica se temos o último ID enviado
            if(method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()){
                //Configura o último ID
                $this->last_id = $this->pdo->lastInsertId();
            }
            
            //Retorna a consulta
            return $qryInsert;
        }
     
        return;
    }
}
?>

