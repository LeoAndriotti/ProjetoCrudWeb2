<?php
session_start();
include_once 'config/config.php';
include_once 'classes/Usuario.php';

$usuario = new Usuario($db);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['login'])){
        $email = $_POST['login'];
        $senha = $_POST['senha'];
        if($dados_usuario = $usuario->login($email, $senha)){
            $_SESSION['usuario_id'] = $dados_usuario['id'];
            header('Location: portal.php');
            exit();
        }else{
            $mensagem_erro = 'Credenciais inválidas!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Globalist - Your window to the world</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <h1 class="logo">THE GLOBALIST</h1>
            <button class="login-btn" onclick="openModal()">Entrar</button>
        </div>
    </header>

    <main class="news-container">
        <section class="featured-news">
            <h2>Notícias em Destaque</h2>
            <div class="empty-state">
                <p>Nenhuma notícia publicada ainda.</p>
                <p>Seja o primeiro a compartilhar uma notícia!</p>
            </div>
        </section>
    </main>

    <div class="modal" id="loginModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="login" id="email" required placeholder="Seu email">
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required placeholder="Sua senha">
                </div>
                <button type="submit" name="submit" class="submit-btn">Entrar</button>
            </form>
            <div class="register-link">
                <p>Não tem uma conta? <a href="./registrar.php">Registre-se aqui</a></p>
            </div>
            <?php if (isset($mensagem_erro)): ?>
                <div class="mensagem">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="social-links">
            <a href="#" class="linkedin" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="#" class="facebook" title="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" class="instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="youtube" title="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="#" class="twitter" title="Twitter"><i class="fab fa-twitter"></i></a>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> The Globalist. Todos os direitos reservados.
        </div>
    </footer>

    <script>
        function openModal() {
            document.getElementById('loginModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('loginModal').classList.remove('active');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>