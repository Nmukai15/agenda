<?php
session_start();
// Autenticação: verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'agenda_etec');

// Verifica se uma data foi enviada via formulário GET
// Se sim, usa a data do formulário. Se não, usa a data de hoje como padrão.
$data_consulta = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

// Validação simples para evitar SQL Injection (recomendado usar Prepared Statements)
// Garante que a data tem o formato YYYY-MM-DD
if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $data_consulta)) {
    // Se a data for inválida, volta para a data de hoje para evitar erros
    $data_consulta = date('Y-m-d');
}

// Modifica a consulta SQL para usar a data selecionada ou a data atual
$sql_reservas = "SELECT r.hora_inicio, r.hora_fim, s.nome_sala FROM reservas r INNER JOIN salas s ON r.id_sala = s.id WHERE r.data_reserva = '$data_consulta'";
$reservas_do_dia = $conn->query($sql_reservas);

// Verifica se a consulta retornou resultados
if ($reservas_do_dia === false) {
    echo "Erro na consulta: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Salas - Agenda ETEC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <h1>Agenda ETEC</h1>
            <nav>
                <ul>
                    <?php if ($_SESSION['usuario_tipo'] == 'professor') { ?>
                        <li><a href="reservar.php">Reservar Sala</a></li>
                    <?php } ?>
                    <li><a href="logout.php">Sair</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Agenda de Salas</h2>
        <form action="" method="get">
            <label for="data">Escolha a data:</label>
            <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($data_consulta); ?>">
            <button type="submit">Consultar</button>
        </form>

        <p>Data consultada: <?php echo date('d/m/Y', strtotime($data_consulta)); ?></p>

        <div class="agenda-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Horário</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Exibe a agenda de horários em uma tabela HTML
                    if ($reservas_do_dia->num_rows > 0) {
                        while($reserva = $reservas_do_dia->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($reserva['nome_sala']) . "</td>";
                            echo "<td>" . htmlspecialchars($reserva['hora_inicio']) . " - " . htmlspecialchars($reserva['hora_fim']) . "</td>";
                            echo "<td>Ocupado</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Não há reservas para a data selecionada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>