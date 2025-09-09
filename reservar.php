<?php
session_start();
// Verifica se o usuário é professor antes de permitir o acesso
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'agenda_etec');

$sql_salas = "SELECT id, nome_sala FROM salas";
$salas = $conn->query($sql_salas);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sala = $_POST['id_sala'];
    $data = $_POST['data'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $id_usuario = $_SESSION['usuario_id'];

    // Lógica para verificar se o horário está disponível [cite: 38]
    $verificar = "SELECT * FROM reservas WHERE id_sala = '$id_sala' AND data_reserva = '$data' AND (
        (hora_inicio >= '$hora_inicio' AND hora_inicio < '$hora_fim') OR
        (hora_fim > '$hora_inicio' AND hora_fim <= '$hora_fim')
    )";
    $resultado = $conn->query($verificar);

    if ($resultado->num_rows == 0) {
        // Insere a nova reserva no banco de dados [cite: 38]
        $sql_inserir = "INSERT INTO reservas (id_sala, id_usuario, data_reserva, hora_inicio, hora_fim) VALUES ('$id_sala', '$id_usuario', '$data', '$hora_inicio', '$hora_fim')";
        if ($conn->query($sql_inserir) === TRUE) {
            $mensagem = "Reserva realizada com sucesso!";
        } else {
            $mensagem = "Erro ao fazer a reserva: " . $conn->error;
        }
    } else {
        $mensagem = "O horário selecionado já está ocupado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Reservar Sala - Agenda ETEC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <h1>Agenda ETEC</h1>
            <nav>
                <ul>
                    <li><a href="agenda.php">Ver Agenda</a></li>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
        <h2>Reservar uma Sala</h2>
        <?php if (isset($mensagem)) { echo "<p class='message'>$mensagem</p>"; } ?>
        <form action="reservar.php" method="POST" class="form-reserva">
            <div class="form-group">
                <label for="id_sala">Sala:</label>
                <select id="id_sala" name="id_sala" required>
                    <?php while($sala = $salas->fetch_assoc()) {
                        echo "<option value='" . $sala['id'] . "'>" . $sala['nome_sala'] . "</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>
            </div>
            <div class="form-group">
                <label for="hora_inicio">Hora de Início:</label>
                <input type="time" id="hora_inicio" name="hora_inicio" required>
            </div>
            <div class="form-group">
                <label for="hora_fim">Hora de Fim:</label>
                <input type="time" id="hora_fim" name="hora_fim" required>
            </div>
            <button type="submit" class="btn">Confirmar Reserva</button>
        </form>
    </main>
</body>
</html>