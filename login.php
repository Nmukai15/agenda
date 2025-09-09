<?php
session_start();
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'agenda_etec');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta para verificar o usuário
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        // Verifica a senha (em um projeto real, use hash_password)
        if ($senha == $usuario['senha']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            header('Location: agenda.php'); // Redireciona para a agenda
            exit();
        } else {
            $erro_login = "Senha incorreta.";
        }
    } else {
        $erro_login = "Usuário não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Agenda ETEC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container login-form">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>
        <?php if (isset($erro_login)) { echo "<p class='error'>$erro_login</p>"; } ?>
    </div>
</body>
</html>