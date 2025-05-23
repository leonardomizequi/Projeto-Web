<?php
// Página de avaliação de docente: formulário de avaliação e gravação no banco
session_start();
if (!isset($_SESSION['estudante_logged_in']) || $_SESSION['estudante_logged_in'] !== true) {
    header('Location: login-estudante.php');
    exit;
}
// --- Inclui cabeçalho e conexão com o banco de dados ---
include 'includes/header.php';
include 'includes/db.php';

// --- Recupera docente e estudante ---
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$numero_estudante = $_SESSION['numero_estudante'] ?? '';

if (empty($numero_estudante)) {
    echo "<p class='error'>Erro: Número do estudante não encontrado na sessão. Faça login novamente.</p>";
    include 'includes/footer.php';
    exit;
}

// --- Gera hash do estudante ---
$hash_estudante = hash('sha256', $numero_estudante);

// --- Verifica docente ---
$docente = $conn->query("SELECT nome FROM docentes WHERE id = $id")->fetch_assoc();
if (!$docente) {
    echo "<p class='error'>Docente não encontrado.</p>";
    include 'includes/footer.php';
    exit;
}

// --- Verifica se já avaliou ---
$avaliacao_existente = $conn->query("SELECT id FROM avaliacoes WHERE docente_id = $id AND estudante_hash = '$hash_estudante'")->fetch_assoc();
if ($avaliacao_existente) {
    echo "<p class='error'>Você já avaliou este docente.</p>";
    include 'includes/footer.php';
    exit;
}

// --- Processa avaliação ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $didatica = isset($_POST['didatica']) ? intval($_POST['didatica']) : 0;
    $dominio = isset($_POST['dominio']) ? intval($_POST['dominio']) : 0;
    $pontualidade = isset($_POST['pontualidade']) ? intval($_POST['pontualidade']) : 0;
    $relacao = isset($_POST['relacao']) ? intval($_POST['relacao']) : 0;
    $comentario = isset($_POST['comentario']) ? $conn->real_escape_string($_POST['comentario']) : '';

    // Verifica se os valores estão entre 1 e 5
    if ($didatica >= 1 && $didatica <= 5 && 
        $dominio >= 1 && $dominio <= 5 && 
        $pontualidade >= 1 && $pontualidade <= 5 && 
        $relacao >= 1 && $relacao <= 5) {
        
        // Insere a avaliação no banco de dados
        $stmt = $conn->prepare("INSERT INTO avaliacoes (docente_id, estudante_hash, didatica, dominio, pontualidade, relacao, comentario) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issiiis", $id, $hash_estudante, $didatica, $dominio, $pontualidade, $relacao, $comentario);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "<p class='error'>Erro ao salvar a avaliação. Por favor, tente novamente.</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error'>Os valores das avaliações devem estar entre 1 e 5.</p>";
    }
}
?>
<!-- Formulário de avaliação -->
<div class="container">
    <h1>Avaliar <?= htmlspecialchars($docente['nome']) ?></h1>
    <form method="POST">
        <label>Didática:</label>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="didatica-<?= $i ?>" name="didatica" value="<?= $i ?>" required>
                <label for="didatica-<?= $i ?>" data-value="<?= $i ?>">★</label>
            <?php endfor; ?>
        </div>
        <label>Domínio do Conteúdo:</label>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="dominio-<?= $i ?>" name="dominio" value="<?= $i ?>" required>
                <label for="dominio-<?= $i ?>" data-value="<?= $i ?>">★</label>
            <?php endfor; ?>
        </div>
        <label>Pontualidade:</label>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="pontualidade-<?= $i ?>" name="pontualidade" value="<?= $i ?>" required>
                <label for="pontualidade-<?= $i ?>" data-value="<?= $i ?>">★</label>
            <?php endfor; ?>
        </div>
        <label>Relação com Alunos:</label>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="relacao-<?= $i ?>" name="relacao" value="<?= $i ?>" required>
                <label for="relacao-<?= $i ?>" data-value="<?= $i ?>">★</label>
            <?php endfor; ?>
        </div>
        <label>Comentário: <textarea name="comentario"></textarea></label>
        <button type="submit">Enviar Avaliação</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
