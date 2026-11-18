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
include_once __DIR__ . '/../../controllers/CategoriaController.php';

$controller = new CategoriaController();

$id = isset($_GET['id']) ? $_GET['id'] : die('ID da categoria não fornecido.');

$categoria = $controller->readOne($id);

// PARTE 02: se o id for inválido ou a categoria não existir, interrompe com uma mensagem
if (!$categoria) {
    die('Categoria não encontrada.');
}

$erros = array();
$nome = $categoria->nome;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    if ($controller->update($id, $nome)) {
        header('Location: /crud_php/public/index.php?page=categorias');
        exit();
    } else {
        $erros = $controller->erros;
    }
}

include __DIR__ . '/../../views/includes/header.php';
?>

<h2>Editar Categoria</h2>

<?php include __DIR__ . '/../../views/includes/erros.php'; ?>

<form action="/crud_php/public/index.php?page=categorias&action=edit&id=<?php echo $id; ?>" method="POST">
    <label for="nome">Nome da Categoria:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome, ENT_QUOTES); ?>" required>
    <button type="submit" class="button">Salvar</button>
    <a href="/crud_php/public/index.php?page=categorias" class="button">Cancelar</a>
</form>

<?php include __DIR__ . '/../../views/includes/footer.php'; ?>
