<?php
session_start();
include_once 'config/config.php';
include_once 'classes/Usuario.php';

$usuario = new Usuario($db);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['login'])){
        $email = $_POST['login'];
        $senha = $_POST['senha'];
        if($dados_usuario = $usuario->login($login, $senha)){
            $_SESSION['usuario_id'] = $dados_usuario['id'];
            header('Location: portal.php');
            exit();
        }else{
            $mensagem_erro = 'Credenciais invélidas!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTENTICAÇÃO</title>
</head>
<body>
    <div class="box">
        <h1>A U T E N T I C A Ç Ã O</h1>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <br><br>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
            <br><br>
            <input type="submit" name="login" value="Login">
        </form>
        <p>Não tem uma conta? <a href="./registrar.php">Registre-se aqui</a></p>
        <div class="mensagem">
            <?php if (isset($mensagem_erro)) echo '<p>' . $mensagem_erro . '</p>'; ?>
        </div>
    </div>
</body>
</html>