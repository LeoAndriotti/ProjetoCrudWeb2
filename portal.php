<?php
session_start();
include_once './config/config.php';
include_once './classes/Usuario.php';
include_once './classes/Noticias.php';
include_once './classes/Categoria.php';
// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}
$usuario = new Usuario($db);
$noticias = new Noticias($db);
$categoria = new Categoria($db);
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $usuario->deletar($id);
    header('Location: portal.php');
    exit();
}
$dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
if ($dados_usuario) {
    $nome_usuario = $dados_usuario['nome'];
    $usuario_id = $dados_usuario['id'];
} else {
    header('Location: logout.php');
    exit();
}
$dados = $usuario->ler();
$noticias_usuario = $noticias->lerPorAutor($usuario_id);
function saudacao() {
    $hora = date('H');
    if ($hora >= 6 && $hora < 12) {
        return "Bom dia";
    } elseif ($hora >= 12 && $hora < 18) {
        return "Boa tarde";
    } else {
        return "Boa noite";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CSL Times - Portal</title>
    <link rel="stylesheet" href="./uploads/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="./assets/img/logo.png" type="image/png">

</head>
<body class="portal-body">
    <div class="portal-header portal-header-portal">
        <img src="./assets/img/logo2.png" alt="CSL Times" class="portal-logo-img" style="width: 150px; height: 130px;">
        <div class="portal-header-content">
            <h1><span class="saudacao-portal"><?php echo saudacao(); ?></span>, <?php echo $nome_usuario; ?>!</h1>
            <div class="portal-nav">
                <a href="alterar.php?id=<?php echo $usuario_id; ?>"><i class="fas fa-user-edit"></i> Editar Usuário</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </div>
        </div>
    </div>

    <div class="portal-container">
        <a href="cadastrarNoticia.php" class="portal-add-btn"><i class="fas fa-plus"></i> Adicionar Notícia</a>
        <h2 class="portal-section-title">Suas Notícias</h2>
        <?php if (empty($noticias_usuario)): ?>
            <div class="empty-state">
                <p>Você ainda não publicou nenhuma notícia.</p>
            </div>
        <?php else: ?>
            <div class="news-grid">
                <?php foreach ($noticias_usuario as $noticia): ?>
                    <article class="news-card">
                        <?php if (!empty($noticia['imagem'])): ?>
                            <div class="news-image">
                                <?php
                                $img = ltrim($noticia['imagem'], '@');
                                if (strpos($img, 'http') === 0) {
                                    $src = $img;
                                } else {
                                    $src = 'uploads/' . $img;
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($src); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="news-content">
                            <h3 class="news-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                            <p class="news-excerpt"><?php echo htmlspecialchars(substr($noticia['noticia'], 0, 150)) . '...'; ?></p>
                            <div class="news-meta">
                                <div class="news-meta-top">
                                    <span class="news-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d/m/Y', strtotime($noticia['data'])); ?>
                                    </span>
                                </div>
                                <span class="news-category">
                                    <i class="fas fa-tag"></i>
                                    <?php 
                                        $cat = $categoria->lerPorId($noticia['categoria']);
                                        echo htmlspecialchars($cat['nome'] ?? 'Sem categoria');
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="news-card-actions">
                            <a href="alterarNoticia.php?id=<?php echo $noticia['id']; ?>" class="news-edit-btn" title="Editar Notícia"><i class="fas fa-edit"></i></a>
                            <a href="deletarNoticia.php?id=<?php echo $noticia['id']; ?>" class="news-delete-btn" title="Excluir Notícia"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
</body>
</html>