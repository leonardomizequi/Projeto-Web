<?php
include 'header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nome = $conn->real_escape_string($_POST['nome']);
        
            } elseif (isset($_POST['add_admin'])) {
        $usuario = $conn->real_escape_string($_POST['usuario']);
        $senha = $conn->real_escape_string($_POST['senha']);

        // Verifica se a senha tem pelo menos 6 caracteres
        if (strlen($senha) < 6) {
            echo "<p class='error'>A senha deve ter pelo menos 6 caracteres!</p>";
        } else {
            $conn->query("INSERT INTO admin (usuario, senha) VALUES ('$usuario', '$senha')");
            echo "<p class='success'>Administrador adicionado com sucesso!</p>";
        }
    }

}

?>
<div class="admin-nav">
</div>
<div class="admin-container">

    <h1>Gerir Administradores</h1>
    <form method="POST" class="form-inline">
        <label>Usuário: <input type="text" name="usuario" required></label>
        <label>Senha: <input type="password" name="senha" required></label>
        <button type="submit" name="add_admin">Adicionar Administrador</button>
    </form>
</div>
<?php include 'footer.php'; ?>
