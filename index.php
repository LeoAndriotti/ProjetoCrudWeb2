<?php
session_start();
include_once 'config/config.php';
include_once 'classes/Usuario.php';
include_once 'classes/Noticias.php';
include_once 'classes/Categoria.php';

$usuario = new Usuario($db);
$noticias = new Noticias($db);
$categoria = new Categoria($db);

$todas_noticias = $noticias->ler();

$ultimas_noticias = $noticias->lerUltimas(5);

$erro_login = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entrar'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $usuario_data = $usuario->buscarPorEmail($email);

    if ($usuario_data && password_verify($senha, $usuario_data['senha'])) {
        $_SESSION['usuario_id'] = $usuario_data['id'];
        header('Location: portal.php');
        exit();
    } else {
        $erro_login = 'Email ou senha inválidos';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSL Times</title>
    <link rel="stylesheet" href="./uploads/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="./assets/img/logo.png" type="image/png">
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
          
        </div>
    </header>

    <main class="news-container">
        <section class="featured-news" style="text-align:center;">
            <img src="./assets/img/logo2.png" alt="Logo CSL Times" class="logo-img" style="display:block;margin:0 auto 10px auto;max-width:250px;">
            <h2>CSL Times - Your window to the world!</h2>
            <?php if (empty($todas_noticias)): ?>
                <div class="empty-state">
                    <p>Publique a sua notícia, acessando o portal!</p>
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
                                    <div class="news-meta-top">
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
                                    <span class="news-category">
                                        <i class="fas fa-tag"></i>
                                        <?php 
                                            $cat = $categoria->lerPorId($noticia['categoria']);
                                            echo htmlspecialchars($cat['nome'] ?? 'Sem categoria');
                                        ?>
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
                <p style="color:black;">Não tem uma conta? <a href="./registrar.php">Registre-se aqui</a></p>
            </div>
            
            <?php if (!empty($erro_login)): ?>
                <div class="login-error"><?php echo $erro_login; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer-main">
        <div class="social-links">
            <a href="https://br.linkedin.com" class="linkedin" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="https://pt-br.facebook.com" class="facebook" title="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com" class="instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://www.youtube.com/?gl=BR" class="youtube" title="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="https://x.com/" class="twitter" title="Twitter"><i class="fab fa-twitter"></i></a>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> CSL Times. Todos os direitos reservados.
        </div>
    </footer>

    <script>
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

        updateCurrencies();
        setInterval(updateCurrencies, 300000);

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

        // Controle do footer
        window.addEventListener('scroll', function() {
            const footer = document.querySelector('.footer-main');
            const scrollPosition = window.scrollY + window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            
            // Mostra o footer quando estiver próximo ao final da página
            if (scrollPosition >= documentHeight - 100) {
                footer.classList.add('visible');
            } else {
                footer.classList.remove('visible');
            }
        });
    </script>
</body>
</html>