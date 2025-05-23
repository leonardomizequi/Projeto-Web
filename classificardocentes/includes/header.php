<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica Docentes</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel=icon href="img/favicon.ico" type="image/x-icon">
</head>
<body>
<header>
    <!-- Cabeçalho e menu principal -->
    <div class="header-content">
        <div class="logo-container">
            <img src="img/logo.png" alt="Classifica Docentes Logo" class="logo" style="width: 70px; height: auto;">
        </div>
        <div class="site-info">
            <h1 style="margin-left: 0;  text-align: left;">SISTEMA DE CLASSIFICAÇÃO DE DOCENTES E DISCIPLINAR ONLINE</h1>
            <p class="slogan">Sistema de classificar docente disciplinar online</p>
        </div>
        <nav>
            <!-- Menu de navegação -->
            <a href="index.php">Início</a>
            <a href="docentes.php">Docentes</a>
            <a href="melhor-docente.php">Melhor Docente</a>
            <?php
            // Exibe apenas Login se não estiver logado, e apenas Logout se estiver logado
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (isset($_SESSION['estudante_logged_in']) && $_SESSION['estudante_logged_in']) {
                echo '<a href="logout.php" style="background: #dc3545; color: white;">Logout</a>';
            } else {
                echo '<a href="login-estudante.php" style="background: white; color: black;">Login</a>';
            }
            ?>
        </nav>
    </div>
    <?php
    // Saudação ao usuário logado e exibição do curso
    if (isset($_SESSION['estudante_logged_in']) && $_SESSION['estudante_logged_in']) {
        include_once __DIR__ . '/db.php';
        $numero = $_SESSION['numero_estudante'] ?? '';
        $curso_id = $_SESSION['curso_id'] ?? '';
        $nome = '';
        $curso_nome = '';
        if ($numero) {
            $res = $conn->query("SELECT nome, curso_id FROM estudantes WHERE numero_estudante = '$numero'");
            if ($res && $row = $res->fetch_assoc()) {
                $nome = $row['nome'];
                $curso_id = $row['curso_id'];
            }
        }
        if ($curso_id) {
            $res2 = $conn->query("SELECT nome FROM cursos WHERE id = '$curso_id'");
            if ($res2 && $row2 = $res2->fetch_assoc()) {
                $curso_nome = $row2['nome'];
            }
        }
        if ($nome) {
            echo '<div style="text-align:right;padding:5px 20px 0 0;color:#004085;font-weight:bold;">Bem-vindo, ' . htmlspecialchars($nome);
            if ($curso_nome) {
                echo ' <span style="font-weight:normal;">| Curso: ' . htmlspecialchars($curso_nome) . '</span>';
            }
            echo '</div>';
        }
    } elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
        echo '<div style="text-align:right;padding:5px 20px 0 0;color:#004085;font-weight:bold;">Admin logado</div>';
    }
    ?>
</header>
