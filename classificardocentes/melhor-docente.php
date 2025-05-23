<?php
include 'includes/header.php';
include 'includes/db.php';

// Consulta para buscar os 10 melhores docentes com base na média geral
$melhores_docentes = $conn->query("
    SELECT 
        d.nome, 
        AVG((av.didatica + av.dominio + av.pontualidade + av.relacao) / 4) AS media_geral
    FROM docentes d
    LEFT JOIN avaliacoes av ON av.docente_id = d.id
    GROUP BY d.id, d.nome
    ORDER BY media_geral DESC
    LIMIT 10
");

// Consultas para os 5 melhores em cada critério
$top_didatica = $conn->query("
    SELECT 
        d.nome, 
        AVG(av.didatica) AS media
    FROM docentes d
    LEFT JOIN avaliacoes av ON av.docente_id = d.id
    GROUP BY d.id, d.nome
    ORDER BY media DESC
    LIMIT 5
");

$top_dominio = $conn->query("
    SELECT 
        d.nome, 
        AVG(av.dominio) AS media
    FROM docentes d
    LEFT JOIN avaliacoes av ON av.docente_id = d.id
    GROUP BY d.id, d.nome
    ORDER BY media DESC
    LIMIT 5
");

$top_pontualidade = $conn->query("
    SELECT 
        d.nome, 
        AVG(av.pontualidade) AS media
    FROM docentes d
    LEFT JOIN avaliacoes av ON av.docente_id = d.id
    GROUP BY d.id, d.nome
    ORDER BY media DESC
    LIMIT 5
");

$top_relacao = $conn->query("
    SELECT 
        d.nome, 
        AVG(av.relacao) AS media
    FROM docentes d
    LEFT JOIN avaliacoes av ON av.docente_id = d.id
    GROUP BY d.id, d.nome
    ORDER BY media DESC
    LIMIT 5
");
?>
<div class="container">
    <h1>Top 10 Docentes</h1>
    <ol class="styled-list">
        <?php while ($docente = $melhores_docentes->fetch_assoc()): ?>
        <li>
            <strong><?= htmlspecialchars($docente['nome']) ?></strong>
            <span class="media">Média Geral: <?= number_format($docente['media_geral'] * 20, 2) ?>%</span>
        </li>
        <?php endwhile; ?>
    </ol>

    <h1>Top 5 por Critério</h1>

    <h2>Didática</h2>
    <ol class="styled-list">
        <?php while ($docente = $top_didatica->fetch_assoc()): ?>
        <li>
            <strong><?= htmlspecialchars($docente['nome']) ?></strong>
            <span class="media">Média: <?= number_format($docente['media'] * 20, 2) ?>%</span>
        </li>
        <?php endwhile; ?>
    </ol>

    <h2>Domínio</h2>
    <ol class="styled-list">
        <?php while ($docente = $top_dominio->fetch_assoc()): ?>
        <li>
            <strong><?= htmlspecialchars($docente['nome']) ?></strong>
            <span class="media">Média: <?= number_format($docente['media'] * 20, 2) ?>%</span>
        </li>
        <?php endwhile; ?>
    </ol>

    <h2>Pontualidade</h2>
    <ol class="styled-list">
        <?php while ($docente = $top_pontualidade->fetch_assoc()): ?>
        <li>
            <strong><?= htmlspecialchars($docente['nome']) ?></strong>
            <span class="media">Média: <?= number_format($docente['media'] * 20, 2) ?>%</span>
        </li>
        <?php endwhile; ?>
    </ol>

    <h2>Relação com Alunos</h2>
    <ol class="styled-list">
        <?php while ($docente = $top_relacao->fetch_assoc()): ?>
        <li>
            <strong><?= htmlspecialchars($docente['nome']) ?></strong>
            <span class="media">Média: <?= number_format($docente['media'] * 20, 2) ?>%</span>
        </li>
        <?php endwhile; ?>
    </ol>
</div>
<?php include 'includes/footer.php'; ?>
