<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verifica se a senha tem pelo menos 6 caracteres
    if (strlen($senha) < 6) {
        echo "<p class='error'>A senha deve ter pelo menos 6 caracteres!</p>";
    } else {
        $result = $conn->query("SELECT * FROM admin WHERE usuario = '$usuario' AND senha = '$senha'");
        if ($result->num_rows > 0) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: admin/dashboard.php');
            exit;
        } else {
            echo "<p class='error'>Usuário ou senha incorretos!</p>";
        }
    }
}
?>
<div class="login-container">
    <h1>Login</h1>
    <form method="POST" class="login-form">
        <label>Usuário: <input type="text" name="usuario" required></label>
        <label>Senha: <input type="password" name="senha" required></label>
        <button type="submit">Entrar</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
