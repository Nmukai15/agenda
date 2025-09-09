<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'agenda_etec');

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    // Prepara a consulta SQL para evitar injeção de SQL
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);

    // Executa a consulta
    if ($stmt->execute()) {
        $mensagem_sucesso = "Cadastro realizado com sucesso! Agora você pode fazer o login.";
    } else {
        $mensagem_erro = "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Agenda ETEC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <h1>Cadastro</h1>
            <nav>
                <ul>
                    <li><a href="login.php" class="btn">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container login-form">
        <h2>Criar uma nova conta</h2>
        
        <?php if (isset($mensagem_sucesso)) { ?>
            <p class="message success"><?php echo $mensagem_sucesso; ?></p>
        <?php } ?>
        
        <?php if (isset($mensagem_erro)) { ?>
            <p class="message error"><?php echo $mensagem_erro; ?></p>
        <?php } ?>

        <form action="cadastro.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Usuário:</label>
                <select id="tipo" name="tipo" required>
                    <option value="professor">Professor</option>
                    <option value="aluno">Aluno</option>
                </select>
            </div>
            <button type="submit" class="btn">Cadastrar</button>
        </form>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2023 Agenda ETEC. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>