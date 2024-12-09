<?php
// Conexão ao banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // Substitua pela senha correta
$dbname = "Padaria";

// Conectar ao banco
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultar produtos
$sql = "SELECT nome, preco, categoria, estoque FROM Produtos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibir produtos
    echo "<table border='1'><tr><th>Nome</th><th>Preço</th><th>Categoria</th><th>Estoque</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["nome"] . "</td><td>" . $row["preco"] . "</td><td>" . $row["categoria"] . "</td><td>" . $row["estoque"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Nenhum produto encontrado.";
}

$conn->close();
?>
