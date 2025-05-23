<?php
session_start(); // Certifique-se de que a sessão está iniciada
include 'includes/db.php'; // Adicionado para definir $conn

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_estudante = $conn->real_escape_string($_POST['numero_estudante']);

    // Verifica se o número do estudante tem exatamente 10 caracteres alfanuméricos
    if (!preg_match('/^[a-zA-Z0-9]{10}$/', $numero_estudante)) {
        $erro = "O número do estudante deve ter exatamente 10 caracteres alfanuméricos!";
    } else {
        $result = $conn->query("SELECT * FROM estudantes WHERE numero_estudante = '$numero_estudante'");
        if ($result->num_rows > 0) {
            $estudante = $result->fetch_assoc();
            $_SESSION['estudante_logged_in'] = true;
            $_SESSION['curso_id'] = $estudante['curso_id'];
            $_SESSION['numero_estudante'] = $estudante['numero_estudante'];
            header("Location: index.php");
            exit;
        } else {
            $erro = "Número de estudante inválido!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Estudante</title>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<div class="login-wrapper">
    <div class="login-card">

        <?php if (!empty($erro)): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <form method="POST" class="form-inline">
            <h2>Login Estudante</h2>
            <p class="form-note"><strong>Nota:</strong> O nome do estudante nunca será revelado.</p>
            <label for="numero_estudante">Número de Estudante:</label>
            <input type="text" name="numero_estudante" id="numero_estudante" required>
            <button type="submit">Entrar</button>
            <br>    
            <a href="./cadastro-estudante.php" class="actions button">Não tem cadastro? Cadastre-se</a>
        </form>
    </div>
</div>
