<?php
session_start();
include_once 'config/config.php';
include_once 'classes/Usuario.php';
include_once 'classes/Noticias.php';

$usuario = new Usuario($db);
$noticias = new Noticias($db);

// Buscar todas as notícias
$todas_noticias = $noticias->ler();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entrar'])) {
    echo "Formulário enviado<br>";

    if (!empty($_POST['email']) && !empty($_POST['senha'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        echo "Email: $email<br>";
        echo "Senha: $senha<br>";

        if ($dados_usuario = $usuario->login($email, $senha)) {
            echo "Login bem-sucedido!<br>";
            $_SESSION['usuario_id'] = $dados_usuario['id'];
            echo "Redirecionando para portal.php...<br>";
            header('Location: portal.php');
            exit();
        } else {
            echo "Login falhou!<br>";
            $mensagem_erro = 'Credenciais inválidas!';
        }
    } else {
        echo "Email ou senha vazios<br>";
    }

    exit(); // Evita que continue a renderização do HTML
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSL Times</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="currency-ticker">
        <div class="ticker-content">
            <div class="ticker-items">
                <div class="ticker-item">
                    <i class="fas fa-dollar-sign"></i>
                    <span class="currency-name">USD</span>
                    <span class="currency-value" id="usd-value">Carregando...</span>
                </div>
                <div class="ticker-item">
                    <i class="fas fa-euro-sign"></i>
                    <span class="currency-name">EUR</span>
                    <span class="currency-value" id="eur-value">Carregando...</span>
                </div>
                <div class="ticker-item">
                    <i class="fab fa-bitcoin"></i>
                    <span class="currency-name">BTC</span>
                    <span class="currency-value" id="btc-value">Carregando...</span>
                </div>
                <div class="ticker-item">
                    <i class="fas fa-pound-sign"></i>
                    <span class="currency-name">GBP</span>
                    <span class="currency-value" id="gbp-value">Carregando...</span>
                </div>
            </div>
            <button class="login-btn" onclick="openModal()">Entrar</button>
        </div>
    </div>

    <header class="main-header">
        <div class="header-content">
            <h1 class="logo">CSL Times</h1>
        </div>
    </header>

    <main class="news-container">
        <section class="featured-news">
            <h2>CSL Times - Your window to the world!</h2>
            <?php if (empty($todas_noticias)): ?>
                <div class="empty-state">
                    <p>Nenhuma notícia publicada ainda.</p>
                    <p>Seja o primeiro a compartilhar uma notícia!</p>
                </div>
            <?php else: ?>
                <div class="news-grid">
                    <?php foreach ($todas_noticias as $noticia): ?>
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
                                    <span class="news-author">
                                        <i class="fas fa-user"></i>
                                        <?php 
                                            $autor = $usuario->lerPorId($noticia['autor']);
                                            echo htmlspecialchars($autor['nome'] ?? 'Autor desconhecido');
                                        ?>
                                    </span>
                                    <span class="news-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('d/m/Y', strtotime($noticia['data'])); ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <div class="modal" id="loginModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required placeholder="Seu email">
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required placeholder="Sua senha">
                </div>
                <button type="submit" name="entrar" class="submit-btn">Entrar</button>
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

    <footer class="footer-main">
        <div class="social-links">
            <a href="#" class="linkedin" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="#" class="facebook" title="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" class="instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="youtube" title="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="#" class="twitter" title="Twitter"><i class="fab fa-twitter"></i></a>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> CSL Times. Todos os direitos reservados.
        </div>
    </footer>

    <script>
        // Função para atualizar as cotações
        async function updateCurrencies() {
            try {
                const response = await fetch('https://economia.awesomeapi.com.br/json/last/USD-BRL,EUR-BRL,BTC-BRL,GBP-BRL');
                const data = await response.json();
                
                document.getElementById('usd-value').textContent = `R$ ${parseFloat(data.USDBRL.bid).toFixed(2)}`;
                document.getElementById('eur-value').textContent = `R$ ${parseFloat(data.EURBRL.bid).toFixed(2)}`;
                document.getElementById('btc-value').textContent = `R$ ${parseFloat(data.BTCBRL.bid).toFixed(2)}`;
                document.getElementById('gbp-value').textContent = `R$ ${parseFloat(data.GBPBRL.bid).toFixed(2)}`;
            } catch (error) {
                console.error('Erro ao buscar cotações:', error);
                document.querySelectorAll('.currency-value').forEach(el => {
                    el.textContent = 'Erro ao carregar';
                });
            }
        }

        // Atualiza as cotações a cada 5 minutos
        updateCurrencies();
        setInterval(updateCurrencies, 300000);

        // Funções existentes do modal
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