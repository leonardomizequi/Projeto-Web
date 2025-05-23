<?php
include 'header.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nome = $conn->real_escape_string($_POST['nome']);
        
        // Handle file upload
        $foto = 'default.png'; // Default photo
        if (!empty($_FILES['foto']['name'])) {
            $targetDir = "../uploads/";
            $foto = time() . "_" . basename($_FILES['foto']['name']);
            $targetFile = $targetDir . $foto;
            move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile);
        }

        // Insert docente into the database
        $conn->query("INSERT INTO docentes (nome, foto) VALUES ('$nome', '$foto')");
    } elseif (isset($_POST['add_cadeira'])) {
        $docente_id = intval($_POST['docente']);
        $curso_id = intval($_POST['curso']);
        $ano = intval($_POST['ano']);
        $cadeira = $conn->real_escape_string($_POST['cadeira']);
        
        // Insert association into the database
        $conn->query("INSERT INTO aulas (docente_id, curso_id, ano, cadeira) VALUES ($docente_id, $curso_id, $ano, '$cadeira')");
    } elseif (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $docente = $conn->query("SELECT foto FROM docentes WHERE id = $id")->fetch_assoc();
        if ($docente && $docente['foto'] !== 'default.png') {
            unlink("../uploads/" . $docente['foto']); // Delete the photo file
        }
        $conn->query("DELETE FROM docentes WHERE id = $id");
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
?>
<div class="admin-nav">
    <a href="dashboard.php" class="button">Dashboard</a>
    <a href="gerir-docentes.php" class="button">Gerir Docentes</a>
    <a href="../index.php" class="button">Página Principal</a>
    <a href="../logout.php" class="button">Sair</a>
</div>
<div class="admin-container">
    <h1>Adicionar Docente</h1>
    
    <section class="form-section">
        <form method="POST" enctype="multipart/form-data" class="form-inline">
            <label>Nome: <input type="text" name="nome" required></label>
            <label>Foto: <input type="file" name="foto" accept="image/*"></label>
            <button type="submit" name="add">Adicionar</button>
        </form>
    </section>

    <hr>

</div>
<?php include 'footer.php'; ?>
