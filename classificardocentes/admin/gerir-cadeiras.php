<?php
include 'header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_cadeira'])) {
        $nome = $conn->real_escape_string($_POST['nome']);
        $ano = intval($_POST['ano']);
        $curso_id = intval($_POST['curso']);
        $docente_id = intval($_POST['docente']);
        $conn->query("INSERT INTO cadeiras (nome, ano, curso_id, docente_id) VALUES ('$nome', $ano, $curso_id, $docente_id)");
    }
}

$cursos = $conn->query("SELECT id, nome FROM cursos");
$docentes = $conn->query("SELECT id, nome FROM docentes");
?>
<div class="admin-container">
    <h1>Gerir Cadeiras</h1>
    <form method="POST" class="form-inline">
        <h2>Adicionar Cadeira</h2>
        <label>Nome da Cadeira: <input type="text" name="nome" required></label>
        <label>Ano:
            <select name="ano" required>
                <option value="">Selecione o ano</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>">Ano <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </label>
        <label>Curso:
            <select name="curso" required>
                <option value="">Selecione o curso</option>
                <?php while ($curso = $cursos->fetch_assoc()): ?>
                    <option value="<?= $curso['id'] ?>"><?= htmlspecialchars($curso['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </label>
        <label>Docente:
            <select name="docente" required>
                <option value="">Selecione o docente</option>
                <?php while ($docente = $docentes->fetch_assoc()): ?>
                    <option value="<?= $docente['id'] ?>"><?= htmlspecialchars($docente['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </label>
        <button type="submit" name="add_cadeira">Adicionar Cadeira</button>
    </form>
</div>
<?php include 'footer.php'; ?>
