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
include_once __DIR__ . '/../models/Produto.php';
include_once __DIR__ . '/../models/Categoria.php';

class ProdutoController {
    private $conn;
    private $produto;
    private $categoria;

    // PARTE 02: lista de mensagens de erro (validação ou banco de dados)
    public $erros = array();

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->produto = new Produto($this->conn);
        $this->categoria = new Categoria($this->conn);
    }

    // PARTE 02: validação dos campos. Retorna true se estiver tudo certo.
    private function validar($nome, $preco, $categoria_id) {
        $this->erros = array();

        // Nome
        if ($nome === '') {
            $this->erros[] = "O nome do produto é obrigatório.";
        } elseif (mb_strlen($nome) > 255) {
            $this->erros[] = "O nome do produto deve ter no máximo 255 caracteres.";
        }

        // Preço
        if ($preco === '') {
            $this->erros[] = "O preço é obrigatório.";
        } elseif (!is_numeric($preco)) {
            $this->erros[] = "O preço deve ser um número válido.";
        } elseif ($preco < 0) {
            $this->erros[] = "O preço não pode ser negativo.";
        } elseif ($preco > 99999999.99) {
            $this->erros[] = "O preço informado é muito alto.";
        }

        // Categoria: precisa ser um número e existir na tabela categorias
        if ($categoria_id === '' || !ctype_digit($categoria_id)) {
            $this->erros[] = "Selecione uma categoria.";
        } else {
            $busca = new Categoria($this->conn);
            $busca->id = $categoria_id;
            if (!$busca->readOne()) {
                $this->erros[] = "A categoria selecionada não existe.";
            }
        }

        return count($this->erros) === 0;
    }

    public function index() {
        $stmt = $this->produto->read();
        $num = $stmt->rowCount();
        return ['stmt' => $stmt, 'num' => $num];
    }

    public function create($nome, $descricao, $preco, $categoria_id) {
        $nome = trim($nome);
        $descricao = trim($descricao);
        $preco = trim($preco);
        $categoria_id = trim($categoria_id);

        try {
            if (!$this->validar($nome, $preco, $categoria_id)) {
                return false;
            }

            $this->produto->nome = $nome;
            $this->produto->descricao = $descricao;
            $this->produto->preco = $preco;
            $this->produto->categoria_id = $categoria_id;
            if($this->produto->create()) {
                return true;
            }
            $this->erros[] = "Não foi possível salvar o produto.";
        } catch (PDOException $e) {
            error_log("Erro ao criar produto: " . $e->getMessage());
            $this->erros[] = "Erro ao salvar o produto no banco de dados.";
        }
        return false;
    }

    // Retorna o objeto produto, ou false se o id for inválido ou não existir
    public function readOne($id) {
        if (!ctype_digit((string)$id)) {
            return false;
        }

        try {
            $this->produto->id = $id;
            if ($this->produto->readOne()) {
                return $this->produto;
            }
        } catch (PDOException $e) {
            error_log("Erro ao buscar produto: " . $e->getMessage());
        }
        return false;
    }

    public function update($id, $nome, $descricao, $preco, $categoria_id) {
        $nome = trim($nome);
        $descricao = trim($descricao);
        $preco = trim($preco);
        $categoria_id = trim($categoria_id);

        try {
            if (!$this->validar($nome, $preco, $categoria_id)) {
                return false;
            }

            $this->produto->id = $id;
            $this->produto->nome = $nome;
            $this->produto->descricao = $descricao;
            $this->produto->preco = $preco;
            $this->produto->categoria_id = $categoria_id;
            if($this->produto->update()) {
                return true;
            }
            $this->erros[] = "Não foi possível atualizar o produto.";
        } catch (PDOException $e) {
            error_log("Erro ao atualizar produto: " . $e->getMessage());
            $this->erros[] = "Erro ao atualizar o produto no banco de dados.";
        }
        return false;
    }

    public function delete($id) {
        if (!ctype_digit((string)$id)) {
            return false;
        }

        try {
            $this->produto->id = $id;
            if($this->produto->delete()) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir produto: " . $e->getMessage());
        }
        return false;
    }

    public function getCategorias() {
        $stmt = $this->categoria->read();
        return $stmt;
    }
}
?>
