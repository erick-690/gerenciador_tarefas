<?php
// editar_tarefa.php
require_once 'tarefas.php'; // Alterado
require_once 'usuarios.php'; // Alterado

$message = '';
$message_type = '';
$tarefa = null;
$usuarios = getUsuarios();

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id_tarefa = trim($_GET["id"]);
    $tarefa = getTarefaById($id_tarefa);

    if (empty($tarefa)) {
        header("location: index.php");
        exit();
    }
} else {
    header("location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tarefa = $_POST["id_tarefa"];
    $descricao_tarefa = trim($_POST["descricao_tarefa"]);
    $nome_setor = trim($_POST["nome_setor"]);
    $prioridade = $_POST["prioridade"];
    $status_tarefa = $_POST["status_tarefa"];
    $id_usuario = $_POST["id_usuario"];

    if (empty($descricao_tarefa) || empty($nome_setor) || empty($prioridade) || empty($status_tarefa) || empty($id_usuario)) {
        $message = "Por favor, preencha todos os campos.";
        $message_type = "danger";
    } else {
        if (atualizarTarefa($id_tarefa, $descricao_tarefa, $nome_setor, $prioridade, $status_tarefa, $id_usuario)) {
            $message = "Tarefa atualizada com sucesso!";
            $message_type = "success";
            $tarefa = getTarefaById($id_tarefa);
        } else {
            $message = "Erro ao atualizar tarefa.";
            $message_type = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa - Gerenciador de Tarefas SENAI</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <header>
        <h1>Gerenciador de Tarefas SENAI</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Gerenciamento de Tarefas</a></li>
            <li><a href="cadastro_tarefa.php">Cadastrar Tarefa</a></li>
            <li><a href="cadastro_usuario.php">Cadastrar Usuário</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>Editar Tarefa</h2>
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <?php if ($tarefa): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $tarefa['id_tarefa']); ?>" method="post">
                <input type="hidden" name="id_tarefa" value="<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>">
                <div class="form-group">
                    <label for="descricao_tarefa">Descrição da Tarefa:</label>
                    <textarea id="descricao_tarefa" name="descricao_tarefa" required><?php echo htmlspecialchars($tarefa['descricao_tarefa']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="nome_setor">Setor:</label>
                    <input type="text" id="nome_setor" name="nome_setor" value="<?php echo htmlspecialchars($tarefa['nome_setor']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="prioridade">Prioridade:</label>
                    <select id="prioridade" name="prioridade" required>
                        <option value="baixa" <?php echo ($tarefa['prioridade'] == 'baixa') ? 'selected' : ''; ?>>Baixa</option>
                        <option value="media" <?php echo ($tarefa['prioridade'] == 'media') ? 'selected' : ''; ?>>Média</option>
                        <option value="alta" <?php echo ($tarefa['prioridade'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status_tarefa">Status:</label>
                    <select id="status_tarefa" name="status_tarefa" required>
                        <option value="a fazer" <?php echo ($tarefa['status_tarefa'] == 'a fazer') ? 'selected' : ''; ?>>A Fazer</option>
                        <option value="fazendo" <?php echo ($tarefa['status_tarefa'] == 'fazendo') ? 'selected' : ''; ?>>Fazendo</option>
                        <option value="pronto" <?php echo ($tarefa['status_tarefa'] == 'pronto') ? 'selected' : ''; ?>>Pronto</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_usuario">Atribuir a Usuário:</label>
                    <select id="id_usuario" name="id_usuario" required>
                        <option value="">Selecione um usuário</option>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>"
                                    <?php echo ($tarefa['id_usuario'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($usuario['nome_usuario']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhum usuário cadastrado</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Atualizar Tarefa</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        <?php else: ?>
            <p>Tarefa não encontrada.</p>
        <?php endif; ?>
    </div>
</body>
</html>