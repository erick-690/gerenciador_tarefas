<?php
// usuarios.php
require_once 'config.php'; // Alterado

// Função para cadastrar um novo usuário
function cadastrarUsuario($nome, $email) {
    global $link;
    $sql = "INSERT INTO usuarios (nome_usuario, email_usuario) VALUES (?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $param_nome, $param_email);

        $param_nome = $nome;
        $param_email = $email;

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt);
    }
    return false;
}

// Função para obter todos os usuários
function getUsuarios() {
    global $link;
    $sql = "SELECT id_usuario, nome_usuario FROM usuarios ORDER BY nome_usuario ASC";
    $result = mysqli_query($link, $sql);
    $usuarios = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }
    }
    return $usuarios;
}
?>