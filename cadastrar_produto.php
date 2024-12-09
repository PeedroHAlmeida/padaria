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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $estoque = $_POST['estoque'];

    // Verificar se o preço é negativo
    if ($preco < 0) {
        echo "<script>
                alert('O preço não pode ser negativo.');
                window.location.href = 'index.php';
            </script>";
        exit();
    }

    // Verificar se o estoque é negativo
    if ($estoque < 0) {
        echo "<script>
        alert('O estoque não pode ser negativo.');
        window.location.href = 'index.php';
        </script>";
        exit();
    }

    // Verificar se o produto já existe no banco de dados (mesmo nome)
    $sql_check = "SELECT * FROM Produtos WHERE nome = '$nome'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        echo "<script>
                alert('Já existe um produto com o nome \"$nome\". Tente outro nome.');
                window.location.href = 'index.php';
            </script>";
        exit();
    }

    // Inserir o produto no banco
    $sql = "INSERT INTO Produtos (nome, preco, categoria, estoque) VALUES ('$nome', '$preco', '$categoria', '$estoque')";

    if ($conn->query($sql) === TRUE) {
        // Cadastro realizado com sucesso
        echo "<script>
                alert('Produto cadastrado com sucesso!');
                window.location.href = 'index.php'; // Redireciona para o painel de administrador
              </script>";
    } else {
        echo "Erro ao cadastrar produto: " . $conn->error;
    }

    $conn->close();
}
?>
