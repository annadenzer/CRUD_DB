<?php


/******************************************************************************
Curso: Engenharia de Software
Disciplina: Linguagem e Técnicas de Programacão
Professor: Flores
Turma: ESOFT-2

Componentes:
26001788-2 - Anna Julia Denzer de Paula
26009422-2 - Eduardo de Freitas Martins da Silva
26010318-2 - Fabio Pinheiro
26006516-2 - Guilherme Vinicius da Silva
26014696-2 - João Felipe Moro
26002013-2 - Juan Gabriel Ribeiro de Carvalho
26008749-2 - Juan Guilherme Mendes de Moraes 
26011565-2 - Lauane Gabrielly Putinato Lino

Data: 17 de Novembro de 2026

*******************************************************************************/
class Database {
    private $host = "localhost";
    private $db_name = "crud_db";
    private $username = "root";
    private $password = ""; // No XAMPP, a senha padrão do root é vazia
    public $conn;

    public function getConnection(){
        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // PARTE 02: faz o PDO lançar exceções quando houver erro no SQL (permite usar try/catch)
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            // PARTE 02: guarda o erro técnico no log e mostra uma mensagem amigável
            error_log("Erro de conexão: " . $exception->getMessage());
            die("<p class='error'>Erro de conexão com o banco de dados. Verifique se o MySQL está ligado no XAMPP e se o banco crud_db foi criado.</p>");
        }

        return $this->conn;
    }
}

?>
