<?php
// Conexão ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Padaria";

// Criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificando se o ID do produto foi passado pela URL
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Preparando a consulta para excluir o produto
    $sql_delete = "DELETE FROM Produtos WHERE id_produto = '$id_produto'";

    if ($conn->query($sql_delete) === TRUE) {
        // Sucesso: Redirecionar para o painel do administrador
        echo "<script>
                alert('Produto excluído com sucesso!');
                window.location.href = 'index.php'; // Redireciona para o painel de administração
              </script>";
    } else {
        echo "Erro ao excluir o produto: " . $conn->error;
    }
}

$conn->close();
?>
