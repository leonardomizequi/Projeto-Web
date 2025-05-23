<?php
include 'header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nome = $conn->real_escape_string($_POST['nome']);
        
        $foto = 'default.png';
        if (!empty($_FILES['foto']['name'])) {
            $targetDir = "../uploads/";
            $foto = time() . "_" . basename($_FILES['foto']['name']);
            $targetFile = $targetDir . $foto;
            move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile);
        }

        $conn->query("INSERT INTO docentes (nome, foto) VALUES ('$nome', '$foto')");
    } elseif (isset($_POST['add_cadeira'])) {
        $docente_id = intval($_POST['docente']);
        $curso_id = intval($_POST['curso']);
        $ano = intval($_POST['ano']);
        $cadeira = $conn->real_escape_string($_POST['cadeira']);
        
        // Inserir associação usando o nome da cadeira selecionada
        $conn->query("INSERT INTO aulas (docente_id, curso_id, ano, cadeira) VALUES ($docente_id, $curso_id, $ano, '$cadeira')");
    }
}

$cursos = $conn->query("SELECT id, nome FROM cursos");
if (!$cursos) {
    die("Erro ao carregar cursos: " . $conn->error);
}

$docentes = $conn->query("SELECT id, nome FROM docentes");
if (!$docentes) {
    die("Erro ao carregar docentes: " . $conn->error);
}

// Buscar cadeiras existentes para o select
$cadeiras = $conn->query("SELECT nome FROM cadeiras");
?>
<div class="admin-nav">
    <a href="dashboard.php" class="button">Dashboard</a>
    <a href="gerir-docentes.php" class="button">Gerir Docentes</a>
    <a href="../index.php" class="button">Página Principal</a>
    <a href="../logout.php" class="button">Sair</a>
</div>
<div class="admin-container">
    <!-- Formulário para associar cadeiras e anos -->
    <section class="form-section">
        <h1>Associar Cadeiras e Anos a um Docente</h1>
        <form method="POST" class="form-inline">
            <label>Docente:
                <select name="docente" required>
                    <option value="">Selecione o docente</option>
                    <?php while ($docente = $docentes->fetch_assoc()): ?>
                        <option value="<?= $docente['id'] ?>"><?= htmlspecialchars($docente['nome']) ?></option>
                    <?php endwhile; ?>
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
            <label>Ano:
                <select name="ano" required>
                    <option value="">Selecione o ano</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>">Ano <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label>
            <label>Cadeira:
                <select name="cadeira" required>
                    <option value="">Selecione a cadeira</option>
                    <?php while ($cadeira = $cadeiras->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($cadeira['nome']) ?>"><?= htmlspecialchars($cadeira['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </label>
            <button type="submit" name="add_cadeira">Adicionar Cadeira</button>
        </form>
    </section>
</div>
<?php include 'footer.php'; ?>
