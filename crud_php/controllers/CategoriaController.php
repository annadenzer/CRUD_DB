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
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/Categoria.php';

class CategoriaController {
    private $conn;
    private $categoria;

    // PARTE 02: lista de mensagens de erro (validação ou banco de dados)
    public $erros = array();

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->categoria = new Categoria($this->conn);
    }

    // PARTE 02: validação dos campos. Retorna true se estiver tudo certo.
    private function validar($nome) {
        $this->erros = array();

        if ($nome === '') {
            $this->erros[] = "O nome da categoria é obrigatório.";
        } elseif (mb_strlen($nome) > 255) {
            $this->erros[] = "O nome da categoria deve ter no máximo 255 caracteres.";
        }

        return count($this->erros) === 0;
    }

    public function index() {
        $stmt = $this->categoria->read();
        $num = $stmt->rowCount();
        return ['stmt' => $stmt, 'num' => $num];
    }

    public function create($nome) {
        $nome = trim($nome);
        if (!$this->validar($nome)) {
            return false;
        }

        try {
            $this->categoria->nome = $nome;
            if($this->categoria->create()) {
                return true;
            }
            $this->erros[] = "Não foi possível salvar a categoria.";
        } catch (PDOException $e) {
            error_log("Erro ao criar categoria: " . $e->getMessage());
            $this->erros[] = "Erro ao salvar a categoria no banco de dados.";
        }
        return false;
    }

    // Retorna o objeto categoria, ou false se o id for inválido ou não existir
    public function readOne($id) {
        if (!ctype_digit((string)$id)) {
            return false;
        }

        try {
            $this->categoria->id = $id;
            if ($this->categoria->readOne()) {
                return $this->categoria;
            }
        } catch (PDOException $e) {
            error_log("Erro ao buscar categoria: " . $e->getMessage());
        }
        return false;
    }

    public function update($id, $nome) {
        $nome = trim($nome);
        if (!$this->validar($nome)) {
            return false;
        }

        try {
            $this->categoria->id = $id;
            $this->categoria->nome = $nome;
            if($this->categoria->update()) {
                return true;
            }
            $this->erros[] = "Não foi possível atualizar a categoria.";
        } catch (PDOException $e) {
            error_log("Erro ao atualizar categoria: " . $e->getMessage());
            $this->erros[] = "Erro ao atualizar a categoria no banco de dados.";
        }
        return false;
    }

    public function delete($id) {
        if (!ctype_digit((string)$id)) {
            return false;
        }

        try {
            $this->categoria->id = $id;
            if($this->categoria->delete()) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir categoria: " . $e->getMessage());
        }
        return false;
    }
}
?>
