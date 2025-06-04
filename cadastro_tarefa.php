<?php
// cadastro_tarefa.php
require_once 'tarefas.php';   // Contém cadastrarTarefa()
require_once 'usuarios.php';  // Contém getUsuarios()

$message = '';
$message_type = '';
$usuarios = getUsuarios(); // Busca usuários para popular o dropdown

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao_tarefa = trim($_POST["descricao_tarefa"]);
    $nome_setor = trim($_POST["nome_setor"]); // Reintroduzido o campo setor
    $prioridade = $_POST["prioridade"];
    $id_usuario = $_POST["id_usuario"];

    if (empty($descricao_tarefa) || empty($nome_setor) || empty($prioridade) || empty($id_usuario)) { // Validação do setor
        $message = "Por favor, preencha todos os campos.";
        $message_type = "danger";
    } else {
        // Passa $nome_setor para cadastrarTarefa()
        if (cadastrarTarefa($descricao_tarefa, $nome_setor, $prioridade, $id_usuario)) {
            $message = "Tarefa cadastrada com sucesso!";
            $message_type = "success";
            $_POST = array(); // Limpa os campos do formulário após o sucesso
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
    <link rel="stylesheet" href="style.css">
</head>
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
                <textarea id="descricao_tarefa" name="descricao_tarefa" required><?php echo isset($_POST['descricao_tarefa']) ? htmlspecialchars($_POST['descricao_tarefa']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="nome_setor">Setor:</label>
                <input type="text" id="nome_setor" name="nome_setor" value="<?php echo isset($_POST['nome_setor']) ? htmlspecialchars($_POST['nome_setor']) : ''; ?>" required> </div>
            <div class="form-group">
                <label for="prioridade">Prioridade:</label>
                <select id="prioridade" name="prioridade" required>
                    <option value="baixa" <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'baixa') ? 'selected' : ''; ?>>Baixa</option>
                    <option value="media" <?php echo (!isset($_POST['prioridade']) || (isset($_POST['prioridade']) && $_POST['prioridade'] == 'media')) ? 'selected' : ''; ?>>Média</option>
                    <option value="alta" <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id_usuario">Atribuir a Usuário:</label>
                <select id="id_usuario" name="id_usuario" required>
                    <option value="">Selecione um usuário</option>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>"
                                <?php echo (isset($_POST['id_usuario']) && $_POST['id_usuario'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
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