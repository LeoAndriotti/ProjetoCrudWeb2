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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f6fa;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #1a1a1a, #2c3e50);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 500;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .nav-buttons a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .nav-buttons a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2c3e50;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-buttons a {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background: #3498db;
            color: white;
        }

        .edit-btn:hover {
            background: #2980b9;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background: #c0392b;
        }

        .add-user-btn {
            display: inline-block;
            background: #2ecc71;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 1rem;
            transition: background 0.3s ease;
        }

        .add-user-btn:hover {
            background: #27ae60;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .nav-buttons {
                width: 100%;
                justify-content: center;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">THE GLOBALIST</div>
        <h1><?php echo saudacao() . ", " . $nome_usuario; ?>!</h1>
        <div class="nav-buttons">
            <a href="registrar.php">Adicionar Usuário</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <div class="container">
        <a href="registrar.php" class="add-user-btn">+ Adicionar Novo Usuário</a>
        
        <div class="table-container">
            <table>
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
                                <div class="action-buttons">
                                    <a href="alterar.php?id=<?php echo $row['id']; ?>" class="edit-btn">Editar</a>
                                    <a href="deletar.php?id=<?php echo $row['id']; ?>" class="delete-btn">Deletar</a>
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