<?php
session_start();
include_once './config/config.php';
include_once './classes/Usuario.php';
// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}
$usuario = new Usuario($db);
// Processar exclusão de usuário
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];

    $usuario->deletar($id);
    header('Location: portal.php');
    exit();
}
// Obter dados do usuário logado
$dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
if ($dados_usuario) {
    $nome_usuario = $dados_usuario['nome'];
} else {
    // Se não encontrar o usuário, redireciona para o logout
    header('Location: logout.php');
    exit();
}
// Obter dados dos usuários
$dados = $usuario->ler();
// Função para determinar a saudação
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
    <title>The Globalist - Portal</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body class="portal-body">
    <div class="portal-header">
        <div class="portal-logo">THE GLOBALIST</div>
        <h1><?php echo saudacao() . ", " . $nome_usuario; ?>!</h1>
        <div class="portal-nav">
            <a href="registrar.php">Adicionar Usuário</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <div class="portal-container">
        <a href="registrar.php" class="portal-add-btn">+ Adicionar Novo Usuário</a>
        
        <div class="portal-table-container">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Sexo</th>
                        <th>Fone</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados as $row) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo ($row['sexo'] === 'M') ? 'Masculino' : 'Feminino'; ?></td>
                            <td><?php echo $row['fone']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <div class="portal-action-buttons">
                                    <a href="alterar.php?id=<?php echo $row['id']; ?>" class="portal-edit-btn">Editar</a>
                                    <a href="deletar.php?id=<?php echo $row['id']; ?>" class="portal-delete-btn">Deletar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>