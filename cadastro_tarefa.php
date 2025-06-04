<?php
// cadastro_tarefa.php
require_once 'tarefas.php'; // Alterado
require_once 'usuarios.php'; // Alterado

$message = '';
$message_type = '';
$usuarios = getUsuarios();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao_tarefa = trim($_POST["descricao_tarefa"]);
    $nome_setor = trim($_POST["nome_setor"]);
    $prioridade = $_POST["prioridade"];
    $id_usuario = $_POST["id_usuario"];

    if (empty($descricao_tarefa) || empty($nome_setor) || empty($prioridade) || empty($id_usuario)) {
        $message = "Por favor, preencha todos os campos.";
        $message_type = "danger";
    } else {
        if (cadastrarTarefa($descricao_tarefa, $nome_setor, $prioridade, $id_usuario)) {
            $message = "Tarefa cadastrada com sucesso!";
            $message_type = "success";
            $_POST = array();
        } else {
            $message = "Erro ao cadastrar tarefa.";
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
    <title>Cadastro de Tarefa - Gerenciador de Tarefas SENAI</title>
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
        <h2>Cadastro de Tarefa</h2>
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="descricao_tarefa">Descrição da Tarefa:</label>
                <textarea id="descricao_tarefa" name="descricao_tarefa" required></textarea>
            </div>
            <div class="form-group">
                <label for="nome_setor">Setor:</label>
                <input type="text" id="nome_setor" name="nome_setor" required>
            </div>
            <div class="form-group">
                <label for="prioridade">Prioridade:</label>
                <select id="prioridade" name="prioridade" required>
                    <option value="baixa">Baixa</option>
                    <option value="media" selected>Média</option>
                    <option value="alta">Alta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id_usuario">Atribuir a Usuário:</label>
                <select id="id_usuario" name="id_usuario" required>
                    <option value="">Selecione um usuário</option>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">
                                <?php echo htmlspecialchars($usuario['nome_usuario']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Nenhum usuário cadastrado</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Cadastrar Tarefa</button>
            </div>
        </form>
    </div>
</body>
</html>