<?php
// tarefas.php
require_once 'config.php'; // Certifique-se de que 'config.php' define $link para a conexão mysqli

// Função para cadastrar uma nova tarefa
function cadastrarTarefa($descricao, $nome_setor, $prioridade, $id_usuario) { // Adicionado $nome_setor
    global $link;
    $sql = "INSERT INTO tarefas (descricao_tarefa, nome_setor, prioridade, id_usuario, status_tarefa, data_cadastro) VALUES (?, ?, ?, ?, 'a fazer', NOW())";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Tipo 's' para $descricao, 's' para $nome_setor, 's' para $prioridade, 'i' para $id_usuario
        mysqli_stmt_bind_param($stmt, "sssis", $param_descricao, $param_nome_setor, $param_prioridade, $param_id_usuario);

        $param_descricao = $descricao;
        $param_nome_setor = $nome_setor; // Retornou o nome do setor
        $param_prioridade = $prioridade;
        $param_id_usuario = $id_usuario;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Erro ao cadastrar tarefa: " . mysqli_error($link));
            mysqli_stmt_close($stmt);
            return false;
        }
    }
    error_log("Erro no prepare statement (cadastrarTarefa): " . mysqli_error($link));
    return false;
}

// Função para obter tarefas por status
function getTarefasByStatus($status) {
    global $link;
    $tarefas = [];
    // 'nome_setor' está direto em 'tarefas'
    $sql = "SELECT t.*, u.nome_usuario
            FROM tarefas t
            JOIN usuarios u ON t.id_usuario = u.id_usuario
            WHERE t.status_tarefa = ?
            ORDER BY t.data_cadastro DESC"; // Ordena pelas mais recentes primeiro

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_status);
        $param_status = $status;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $tarefas[] = $row;
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Erro no prepare statement (getTarefasByStatus): " . mysqli_error($link));
    }
    return $tarefas;
}

// Função para obter uma tarefa pelo ID
function getTarefaById($id_tarefa) {
    global $link;
    // Seleciona 't.*' para incluir 'nome_setor' e 'data_cadastro'
    $sql = "SELECT t.*, u.nome_usuario
            FROM tarefas t
            JOIN usuarios u ON t.id_usuario = u.id_usuario
            WHERE t.id_tarefa = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id_tarefa);
        $param_id_tarefa = $id_tarefa;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tarefa = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $tarefa;
    }
    error_log("Erro no prepare statement (getTarefaById): " . mysqli_error($link));
    return null;
}

// Função para atualizar uma tarefa
function atualizarTarefa($id_tarefa, $descricao, $nome_setor, $prioridade, $status, $id_usuario) { // Adicionado $nome_setor
    global $link;
    // 'nome_setor' agora é uma string direta e será atualizada
    $sql = "UPDATE tarefas SET descricao_tarefa = ?, nome_setor = ?, prioridade = ?, status_tarefa = ?, id_usuario = ? WHERE id_tarefa = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Tipos de parâmetros ajustados ('s' para $nome_setor)
        mysqli_stmt_bind_param($stmt, "sssssi", $param_descricao, $param_nome_setor, $param_prioridade, $param_status, $param_id_usuario, $param_id_tarefa);

        $param_descricao = $descricao;
        $param_nome_setor = $nome_setor; // Retornou o nome do setor
        $param_prioridade = $prioridade;
        $param_status = $status;
        $param_id_usuario = $id_usuario;
        $param_id_tarefa = $id_tarefa;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Erro ao atualizar tarefa: " . mysqli_error($link));
            mysqli_stmt_close($stmt);
            return false;
        }
    }
    error_log("Erro no prepare statement (atualizarTarefa): " . mysqli_error($link));
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
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Erro ao excluir tarefa: " . mysqli_error($link));
            mysqli_stmt_close($stmt);
            return false;
        }
    }
    error_log("Erro no prepare statement (excluirTarefa): " . mysqli_error($link));
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
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Erro ao atualizar status da tarefa: " . mysqli_error($link));
            mysqli_stmt_close($stmt);
            return false;
        }
    }
    error_log("Erro no prepare statement (atualizarStatusTarefa): " . mysqli_error($link));
    return false;
}
?>