<?php
include_once './config/config.php';  
include_once './classes/Noticias.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noticias = new Noticias($db);
    $titulo = $_POST['titulo'];
    $noticia = $_POST['noticia'];
    $data = $_POST['data'];
    $autor = $_POST['autor'];
    $imagem = $_POST['imagem']; 
    $noticias->criar($titulo, $noticia, $data, $autor, $imagem);
    header('Location: portal.php');
    exit();
}
$sql = "SELECT id, nome FROM usuarios ORDER BY nome ASC";
$stmt = $db->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Noticia</title>
</head>
<body>
    <h1>Adicionar Noticia</h1>
    <form method="POST">

        <label for="titulo">Titulo:</label>
        <input type="text" name="titulo" required>
        <br><br>
        <label>Noticia</label>
        <label for="noticia">
         <input type="text" name="noticia" required>
        </label>
        <br><br>
        <label for="data">Data:</label>
        <input type="date" name="data" required>
        <br><br>
         <label for="autor">Autor:</label>
    <select name="autor" id="autor" required>
        <option value="">Selecione um autor</option>
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?= htmlspecialchars($usuario['id']) ?>">
                <?= htmlspecialchars($usuario['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>
        <br><br>
        <label for="imagem">Imagem:</label>
        <input type="image" name="imagem" >
        <br><br>
        <input type="submit" value="Adicionar">
    </form>
</body>
</html>