<?php
// tarefas.php
require_once 'config.php'; // Alterado

// Função para cadastrar uma nova tarefa
function cadastrarTarefa($descricao, $setor, $prioridade, $id_usuario) {
    global $link;
    $sql = "INSERT INTO tarefas (descricao_tarefa, nome_setor, prioridade, id_usuario) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $param_descricao, $param_setor, $param_prioridade, $param_id_usuario);

        $param_descricao = $descricao;
        $param_setor = $setor;
        $param_prioridade = $prioridade;
        $param_id_usuario = $id_usuario;

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }
    return false;
}

// Função para obter tarefas por status
function getTarefasByStatus($status) {
    global $link;
    $sql = "SELECT t.*, u.nome_usuario FROM tarefas t JOIN usuarios u ON t.id_usuario = u.id_usuario WHERE status_tarefa = ? ORDER BY data_cadastro DESC";
    $tarefas = [];
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_status);
        $param_status = $status;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $tarefas[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $tarefas;
}

// Função para obter uma tarefa pelo ID
function getTarefaById($id_tarefa) {
    global $link;
    $sql = "SELECT * FROM tarefas WHERE id_tarefa = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id_tarefa);
        $param_id_tarefa = $id_tarefa;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tarefa = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $tarefa;
    }
    return null;
}

// Função para atualizar uma tarefa
function atualizarTarefa($id_tarefa, $descricao, $setor, $prioridade, $status, $id_usuario) {
    global $link;
    $sql = "UPDATE tarefas SET descricao_tarefa = ?, nome_setor = ?, prioridade = ?, status_tarefa = ?, id_usuario = ? WHERE id_tarefa = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssii", $param_descricao, $param_setor, $param_prioridade, $param_status, $param_id_usuario, $param_id_tarefa);

        $param_descricao = $descricao;
        $param_setor = $setor;
        $param_prioridade = $prioridade;
        $param_status = $status;
        $param_id_usuario = $id_usuario;
        $param_id_tarefa = $id_tarefa;

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }
    return false;
}

// Função para excluir uma tarefa
function excluirTarefa($id_tarefa) {
    global $link;
    $sql = "DELETE FROM tarefas WHERE id_tarefa = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id_tarefa);
        $param_id_tarefa = $id_tarefa;
        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }
    return false;
}

// Função para atualizar o status da tarefa
function atualizarStatusTarefa($id_tarefa, $novo_status) {
    global $link;
    $sql = "UPDATE tarefas SET status_tarefa = ? WHERE id_tarefa = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $param_status, $param_id);

        $param_status = $novo_status;
        $param_id = $id_tarefa;

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }
    return false;
}
?>