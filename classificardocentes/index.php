<?php
// Página inicial: lista todos os docentes associados, permite avaliar se logado ou direciona para login/cadastro.
session_start();
include 'includes/header.php';
include 'includes/db.php';

// --- Verifica se estudante está logado ---
$estudante_logado = isset($_SESSION['estudante_logged_in']) && $_SESSION['estudante_logged_in'];
$curso_id = $estudante_logado ? intval($_SESSION['curso_id']) : null;

// --- Consulta todos os docentes associados (sem agrupar por ano) ---
if ($estudante_logado) {
    // Se logado, filtra pelo curso do estudante
    $query = $conn->prepare("
        SELECT 
            d.id AS docente_id, 
            d.nome AS docente, 
            d.foto, 
            a.cadeira AS disciplina,
            COUNT(av.id) AS total_votos,
            AVG(av.didatica) AS didatica,
            AVG(av.dominio) AS dominio,
            AVG(av.pontualidade) AS pontualidade,
            AVG(av.relacao) AS relacao,
            AVG((av.didatica + av.dominio + av.pontualidade + av.relacao) / 4) AS media
        FROM aulas a
        JOIN docentes d ON a.docente_id = d.id
        LEFT JOIN avaliacoes av ON av.docente_id = d.id
        WHERE a.curso_id = ?
        GROUP BY d.id, d.nome, d.foto, a.cadeira
        ORDER BY d.nome
    ");
    $query->bind_param('i', $curso_id);
    $query->execute();
    $result = $query->get_result();
} else {
    // Se não logado, mostra todos os docentes e disciplinas de todos os cursos
    $result = $conn->query("
        SELECT 
            d.id AS docente_id, 
            d.nome AS docente, 
            d.foto, 
            a.cadeira AS disciplina,
            COUNT(av.id) AS total_votos,
            AVG(av.didatica) AS didatica,
            AVG(av.dominio) AS dominio,
            AVG(av.pontualidade) AS pontualidade,
            AVG(av.relacao) AS relacao,
            AVG((av.didatica + av.dominio + av.pontualidade + av.relacao) / 4) AS media
        FROM aulas a
        JOIN docentes d ON a.docente_id = d.id
        LEFT JOIN avaliacoes av ON av.docente_id = d.id
        GROUP BY d.id, d.nome, d.foto, a.cadeira
        ORDER BY d.nome
    ");
}
?>
<div class="container">
    <h1>Docentes Associados</h1>
    <div class="docentes-horizontal">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($docente = $result->fetch_assoc()): ?>
                <div class="docente-card">
                    <a href="docente.php?id=<?= $docente['docente_id'] ?>" class="docente-link">
                        <img src="uploads/<?= htmlspecialchars($docente['foto']) ?>" alt="<?= htmlspecialchars($docente['docente']) ?>" class="docente-photo">
                        <h3><?= htmlspecialchars($docente['docente']) ?></h3>
                    </a>
                    <div class="avaliacoes">
                        <div class="avaliacao-item">
                            <span>Didática:</span>
                            <strong><?= number_format(($docente['didatica'] ?? 0) * 20, 2) ?>%</strong>
                        </div>
                        <div class="avaliacao-item">
                            <span>Domínio:</span>
                            <strong><?= number_format(($docente['dominio'] ?? 0) * 20, 2) ?>%</strong>
                        </div>
                        <div class="avaliacao-item">
                            <span>Pontualidade:</span>
                            <strong><?= number_format(($docente['pontualidade'] ?? 0) * 20, 2) ?>%</strong>
                        </div>
                        <div class="avaliacao-item">
                            <span>Relação com Alunos:</span>
                            <strong><?= number_format(($docente['relacao'] ?? 0) * 20, 2) ?>%</strong>
                        </div>
                        <div class="avaliacao-item">
                            <span>Média Geral:</span>
                            <strong><?= number_format(($docente['media'] ?? 0) * 20, 2) ?>%</strong>
                        </div>
                        <div class="avaliacao-item">
                            <span>Total de Votos:</span>
                            <strong><?= $docente['total_votos'] ?></strong>
                        </div>
                    </div>
                    <?php if ($estudante_logado): ?>
                        <a href="avaliar.php?id=<?= $docente['docente_id'] ?>" class="vote-link">Avaliar</a>
                    <?php else: ?>
                        <div style="margin-top: 10px; text-align: center;">
                            <a href="login-estudante.php" class="vote-link">Faça login para avaliar</a>
                            <br>
                            <a href="cadastro-estudante.php" class="vote-link" style="background:#0062cc;margin-top:5px;">Não tem cadastro? Cadastre-se</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum docente encontrado.</p>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
