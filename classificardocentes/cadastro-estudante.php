<?php
session_start();
include 'includes/db.php';

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_estudante = $conn->real_escape_string($_POST['numero_estudante']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $curso_id = intval($_POST['curso']);

    // Verifica se o número do estudante tem exatamente 10 caracteres alfanuméricos
    if (!preg_match('/^[a-zA-Z0-9]{10}$/', $numero_estudante)) {
        $erro = "O número do estudante deve ter exatamente 10 caracteres alfanuméricos!";
    } else {
        // Verifica se o número de estudante já existe
        $result = $conn->query("SELECT * FROM estudantes WHERE numero_estudante = '$numero_estudante'");
        if ($result->num_rows > 0) {
            $erro = "Número de estudante já cadastrado!";
        } else {
            $conn->query("INSERT INTO estudantes (numero_estudante, nome, curso_id) VALUES ('$numero_estudante', '$nome', $curso_id)");
            $_SESSION['estudante_logged_in'] = true;
            $_SESSION['curso_id'] = $curso_id;
            $_SESSION['numero_estudante'] = $numero_estudante; // Adicionado para manter login após cadastro
            header("Location: index.php");
            exit;
        }
    }
}

$cursos = $conn->query("SELECT id, nome FROM cursos");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Estudante</title>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<div class="login-container">
    <div class="login-card">
        <form method="POST" class="login-form">
            <h2>Cadastro de Estudante</h2>
            <p class="form-note"><strong>Nota:</strong> O nome do estudante nunca será revelado.</p>

            <?php if (!empty($erro)): ?>
                <p class="error"><?= $erro ?></p>
            <?php endif; ?>

            <label for="numero_estudante">Número de Estudante:</label>
            <input type="text" name="numero_estudante" id="numero_estudante" placeholder="Maximo de 10 caracteres" required>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
            <label for="curso">Curso:</label>
            <select name="curso" id="curso" required>
                <option value="">Selecione o curso</option>
                <?php while ($curso = $cursos->fetch_assoc()): ?>
                    <option value="<?= $curso['id'] ?>"><?= htmlspecialchars($curso['nome']) ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Cadastrar</button>
        </form>
        <div style="margin-top: 20px; text-align: center;">
            <a href="./login-estudante.php" class="actions button">Já tem cadastro? Faça login</a>
        </div>
    </div>
</div>
