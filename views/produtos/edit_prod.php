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
include_once __DIR__ . '/../../controllers/ProdutoController.php';

$controller = new ProdutoController();

$id = isset($_GET['id']) ? $_GET['id'] : die('ID do produto não fornecido.');

$produto = $controller->readOne($id);

if (!$produto) {
    die('Produto não encontrado.');
}

$categorias = $controller->getCategorias()->fetchAll(PDO::FETCH_ASSOC);

$erros = array();
$nome = $produto->nome;
$descricao = $produto->descricao;
$preco = $produto->preco;
$categoria_id = $produto->categoria_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $preco = isset($_POST['preco']) ? $_POST['preco'] : '';
    $categoria_id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : '';

    if ($controller->update($id, $nome, $descricao, $preco, $categoria_id)) {
        header('Location: /crud_php/public/index.php?page=produtos');
        exit();
    } else {
        $erros = $controller->erros;
    }
}

include __DIR__ . '/../../views/includes/header.php';
?>

<h2>Editar Produto</h2>

<?php include __DIR__ . '/../../views/includes/erros.php'; ?>

<form action="/crud_php/public/index.php?page=produtos&action=edit&id=<?php echo $id; ?>" method="POST">
    <label for="nome">Nome do Produto:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome, ENT_QUOTES); ?>" required>

    <label for="descricao">Descrição:</label>
    <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($descricao, ENT_QUOTES); ?></textarea>

    <label for="preco">Preço:</label>
    <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?php echo htmlspecialchars($preco, ENT_QUOTES); ?>" required>

    <label for="categoria_id">Categoria:</label>
    <select id="categoria_id" name="categoria_id" required>
        <?php
        foreach ($categorias as $categoria) {
            $selected = ($categoria['id'] == $categoria_id) ? 'selected' : '';
            echo "<option value=\"" . $categoria['id'] . "\" {$selected}>" . $categoria['nome'] . "</option>";
        }
        ?>
    </select>

    <button type="submit" class="button">Salvar</button>
    <a href="/crud_php/public/index.php?page=produtos" class="button">Cancelar</a>
</form>

<?php include __DIR__ . '/../../views/includes/footer.php'; ?>
