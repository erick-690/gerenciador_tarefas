<?php
// cadastro_usuario.php
require_once 'usuarios.php'; // Alterado

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = trim($_POST["nome_usuario"]);
    $email_usuario = trim($_POST["email_usuario"]);

    if (empty($nome_usuario) || empty($email_usuario)) {
        $message = "Por favor, preencha todos os campos.";
        $message_type = "danger";
    } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
        $message = "Formato de e-mail inválido.";
        $message_type = "danger";
    } else {
        if (cadastrarUsuario($nome_usuario, $email_usuario)) {
            $message = "Usuário cadastrado com sucesso!";
            $message_type = "success";
        } else {
            $message = "Erro ao cadastrar usuário. Verifique se o e-mail já existe.";
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
    <title>Cadastro de Usuário - Gerenciador de Tarefas SENAI</title>
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
        <h2>Cadastro de Usuário</h2>
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nome_usuario">Nome do Usuário:</label>
                <input type="text" id="nome_usuario" name="nome_usuario" required>
            </div>
            <div class="form-group">
                <label for="email_usuario">E-mail do Usuário:</label>
                <input type="email" id="email_usuario" name="email_usuario" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Cadastrar Usuário</button>
            </div>
        </form>
    </div>
</body>
</html>