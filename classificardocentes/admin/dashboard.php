<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

include 'header.php';
include '../includes/db.php';

$docentesCount = $conn->query("SELECT COUNT(*) AS total FROM docentes")->fetch_assoc()['total'];
$avaliacoesCount = $conn->query("SELECT COUNT(*) AS total FROM avaliacoes")->fetch_assoc()['total'];
$topDocentes = $conn->query("SELECT nome, AVG((didatica + dominio + pontualidade + relacao) / 4) AS media FROM docentes JOIN avaliacoes ON docentes.id = avaliacoes.docente_id GROUP BY docentes.id ORDER BY media DESC LIMIT 5");



?>
<div class="admin-nav">
    <a href="dashboard.php" class="button">Dashboard</a>
    <a href="gerir-docentes.php" class="button">Gerir Docentes</a>
    <a href="../index.php" class="button">Página Principal</a>
    <a href="../logout.php" class="button">Sair</a>
</div>
<div class="admin-container">
    <h1>Dashboard</h1>
    <p><strong>Docentes cadastrados:</strong> <?= $docentesCount ?></p>
    <p><strong>Total de avaliações:</strong> <?= $avaliacoesCount ?></p>
    <h2>Mais bem avaliados</h2>
    <ul>
        <?php while ($row = $topDocentes->fetch_assoc()): ?>
        <li><?= $row['nome'] ?> - Média: <?= number_format($row['media'], 2) ?></li>
        <?php endwhile; ?>
    </ul>
</div>
<?php include 'footer.php'; ?>
