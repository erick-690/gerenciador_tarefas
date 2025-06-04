<?php
// index.php
require_once 'tarefas.php'; // Alterado

// Lógica para exclusão de tarefa
if (isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    if (excluirTarefa(trim($_GET["id"]))) {
        header("location: index.php?message=Tarefa excluída com sucesso!&type=success");
        exit();
    } else {
        header("location: index.php?message=Erro ao excluir tarefa.&type=danger");
        exit();
    }
}

// Lógica para atualização de status (via POST do select)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $id_tarefa = $_POST['id_tarefa_status'];
    $novo_status = $_POST['status_tarefa_select'];
    if (atualizarStatusTarefa($id_tarefa, $novo_status)) {
        header("location: index.php?message=Status da tarefa atualizado com sucesso!&type=success");
        exit();
    } else {
        header("location: index.php?message=Erro ao atualizar status da tarefa.&type=danger");
        exit();
    }
}

$tarefas_a_fazer = getTarefasByStatus('a fazer');
$tarefas_fazendo = getTarefasByStatus('fazendo');
$tarefas_pronto = getTarefasByStatus('pronto');

$message = '';
$message_type = '';
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = htmlspecialchars($_GET['type']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas SENAI</title>
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
        <h2>Gerenciamento de Tarefas</h2>
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="kanban-board">
            <div class="kanban-column">
                <h2>A Fazer</h2>
                <?php if (!empty($tarefas_a_fazer)): ?>
                    <?php foreach ($tarefas_a_fazer as $tarefa): ?>
                        <div class="task-card prioridade-<?php echo htmlspecialchars($tarefa['prioridade']); ?>">
                            <h3><?php echo htmlspecialchars($tarefa['descricao_tarefa']); ?></h3>
                            <p><strong>Setor:</strong> <?php echo htmlspecialchars($tarefa['nome_setor']); ?></p> <p><strong>Prioridade:</strong> <?php echo ucfirst(htmlspecialchars($tarefa['prioridade'])); ?></p>
                            <p><strong>Atribuído a:</strong> <?php echo htmlspecialchars($tarefa['nome_usuario']); ?></p>
                            <?php if (isset($tarefa['data_cadastro'])): ?>
                                <p><strong>Cadastro:</strong> <?php echo date('d/m/Y H:i', strtotime(htmlspecialchars($tarefa['data_cadastro']))); ?></p>
                            <?php endif; ?>
                            <div class="task-actions">
                                <a href="editar_tarefa.php?id=<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>" class="btn">Editar</a>
                                <form action="index.php" method="get" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>">
                                    <button type="submit" class="btn btn-danger delete-btn">Excluir</button>
                                </form>
                                <form action="index.php" method="post">
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="id_tarefa_status" value="<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>">
                                    <select name="status_tarefa_select" class="btn status-select-button" onchange="this.form.submit()">
                                        <option value="a fazer" <?php echo ($tarefa['status_tarefa'] == 'a fazer') ? 'selected' : ''; ?>>A Fazer</option>
                                        <option value="fazendo" <?php echo ($tarefa['status_tarefa'] == 'fazendo') ? 'selected' : ''; ?>>Fazendo</option>
                                        <option value="pronto" <?php echo ($tarefa['status_tarefa'] == 'pronto') ? 'selected' : ''; ?>>Pronto</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma tarefa "A Fazer".</p>
                <?php endif; ?>
            </div>

            <div class="kanban-column">
                <h2>Fazendo</h2>
                <?php if (!empty($tarefas_fazendo)): ?>
                    <?php foreach ($tarefas_fazendo as $tarefa): ?>
                        <div class="task-card prioridade-<?php echo htmlspecialchars($tarefa['prioridade']); ?>">
                            <h3><?php echo htmlspecialchars($tarefa['descricao_tarefa']); ?></h3>
                            <p><strong>Setor:</strong> <?php echo htmlspecialchars($tarefa['nome_setor']); ?></p> <p><strong>Prioridade:</strong> <?php echo ucfirst(htmlspecialchars($tarefa['prioridade'])); ?></p>
                            <p><strong>Atribuído a:</strong> <?php echo htmlspecialchars($tarefa['nome_usuario']); ?></p>
                            <?php if (isset($tarefa['data_cadastro'])): ?>
                                <p><strong>Cadastro:</strong> <?php echo date('d/m/Y H:i', strtotime(htmlspecialchars($tarefa['data_cadastro']))); ?></p>
                            <?php endif; ?>
                            <div class="task-actions">
                                <a href="editar_tarefa.php?id=<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>" class="btn">Editar</a>
                                <form action="index.php" method="get" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>">
                                    <button type="submit" class="btn btn-danger delete-btn">Excluir</button>
                                </form>
                                <form action="index.php" method="post">
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="id_tarefa_status" value="<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>">
                                    <select name="status_tarefa_select" class="btn status-select-button" onchange="this.form.submit()">
                                        <option value="a fazer" <?php echo ($tarefa['status_tarefa'] == 'a fazer') ? 'selected' : ''; ?>>A Fazer</option>
                                        <option value="fazendo" <?php echo ($tarefa['status_tarefa'] == 'fazendo') ? 'selected' : ''; ?>>Fazendo</option>
                                        <option value="pronto" <?php echo ($tarefa['status_tarefa'] == 'pronto') ? 'selected' : ''; ?>>Pronto</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma tarefa "Fazendo".</p>
                <?php endif; ?>
            </div>

            <div class="kanban-column">
                <h2>Pronto</h2>
                <?php if (!empty($tarefas_pronto)): ?>
                    <?php foreach ($tarefas_pronto as $tarefa): ?>
                        <div class="task-card prioridade-<?php echo htmlspecialchars($tarefa['prioridade']); ?>">
                            <h3><?php echo htmlspecialchars($tarefa['descricao_tarefa']); ?></h3>
                            <p><strong>Setor:</strong> <?php echo htmlspecialchars($tarefa['nome_setor']); ?></p> <p><strong>Prioridade:</strong> <?php echo ucfirst(htmlspecialchars($tarefa['prioridade'])); ?></p>
                            <p><strong>Atribuído a:</strong> <?php echo htmlspecialchars($tarefa['nome_usuario']); ?></p>
                            <?php if (isset($tarefa['data_cadastro'])): ?>
                                <p><strong>Cadastro:</strong> <?php echo date('d/m/Y H:i', strtotime(htmlspecialchars($tarefa['data_cadastro']))); ?></p>
                            <?php endif; ?>
                            <div class="task-actions">
                                <a href="editar_tarefa.php?id=<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>" class="btn">Editar</a>
                                <form action="index.php" method="get" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>">
                                    <button type="submit" class="btn btn-danger delete-btn">Excluir</button>
                                </form>
                                <form action="index.php" method="post">
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="id_tarefa_status" value="<?php echo htmlspecialchars($tarefa['id_tarefa']); ?>">
                                    <select name="status_tarefa_select" class="btn status-select-button" onchange="this.form.submit()">
                                        <option value="a fazer" <?php echo ($tarefa['status_tarefa'] == 'a fazer') ? 'selected' : ''; ?>>A Fazer</option>
                                        <option value="fazendo" <?php echo ($tarefa['status_tarefa'] == 'fazendo') ? 'selected' : ''; ?>>Fazendo</option>
                                        <option value="pronto" <?php echo ($tarefa['status_tarefa'] == 'pronto') ? 'selected' : ''; ?>>Pronto</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma tarefa "Pronto".</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>