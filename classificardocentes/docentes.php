<?php
include 'includes/header.php';
include 'includes/db.php';

// Atualizar a consulta para refletir o esquema atualizado
$result = $conn->query("
    SELECT 
        d.id, 
        d.nome, 
        d.foto,
        COUNT(av.id) AS total_votos,
        AVG(av.didatica) AS didatica,
        AVG(av.dominio) AS dominio,
        AVG(av.pontualidade) AS pontualidade,
        AVG(av.relacao) AS relacao,
        AVG((av.didatica + av.dominio + av.pontualidade + av.relacao) / 4) AS media
    FROM docentes d
    LEFT JOIN avaliacoes av ON av.docente_id = d.id
    GROUP BY d.id, d.nome, d.foto
");
?>
<div class="container">
    <h1>Lista de Docentes</h1>
    <table>
        <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Didática</th>
            <th>Domínio</th>
            <th>Pontualidade</th>
            <th>Relação</th>
            <th>Média Geral</th>
            <th>Total de Votos</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nome']) ?>" style="width: 50px; height: 50px; border-radius: 50%;"></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= number_format($row['didatica'] ?? 0, 2) ?></td>
            <td><?= number_format($row['dominio'] ?? 0, 2) ?></td>
            <td><?= number_format($row['pontualidade'] ?? 0, 2) ?></td>
            <td><?= number_format($row['relacao'] ?? 0, 2) ?></td>
            <td><?= number_format($row['media'] ?? 0, 2) ?></td>
            <td><?= $row['total_votos'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'includes/footer.php'; ?>
