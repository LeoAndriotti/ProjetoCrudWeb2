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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a1a1a, #2c3e50);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding-bottom: 100px;
        }

        .hero {
            text-align: center;
            padding: 2rem;
        }

        .logo {
            font-size: 4rem;
            font-weight: 700;
            letter-spacing: 4px;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .tagline {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: #ecf0f1;
        }

        .login-btn {
            background: #3498db;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .login-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            position: relative;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: #7f8c8d;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }

        .submit-btn {
            width: 100%;
            background: #3498db;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: #2980b9;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .register-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .mensagem {
            text-align: center;
            margin-top: 1rem;
            padding: 0.8rem;
            border-radius: 5px;
            background: #e74c3c;
            color: white;
            font-size: 0.9rem;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            padding: 1rem 0;
            text-align: center;
            backdrop-filter: blur(5px);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 0.5rem;
        }

        .social-links a {
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-3px);
        }

        .social-links a.linkedin:hover {
            color: #0077b5;
        }

        .social-links a.facebook:hover {
            color: #1877f2;
        }

        .social-links a.instagram:hover {
            color: #e4405f;
        }

        .social-links a.youtube:hover {
            color: #ff0000;
        }

        .social-links a.twitter:hover {
            color: #1da1f2;
        }

        .copyright {
            color: #ecf0f1;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1 class="logo">THE GLOBALIST</h1>
        <p class="tagline">Sua Janela para o Mundo</p>
        <button class="login-btn" onclick="openModal()">Entrar</button>
    </div>

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

        // Fechar modal quando clicar fora dele
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Fechar modal com a tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>