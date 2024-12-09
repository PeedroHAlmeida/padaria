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

session_start();

// Verificando se o parâmetro 'id' foi passado na URL
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Buscar as informações do produto pelo ID
    $sql = "SELECT * FROM Produtos WHERE id_produto = '$id_produto'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado!";
        exit();
    }
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
                window.location.href = 'editar_produto.php?id=$id_produto'; // Redireciona para a edição
              </script>";
        exit();
    }

    // Verificar se o estoque é negativo
    if ($estoque < 0) {
        echo "<script>
                alert('O estoque não pode ser negativo.');
                window.location.href = 'editar_produto.php?id=$id_produto'; // Redireciona para a edição
              </script>";
        exit();
    }

    // Atualizar o produto no banco de dados
    $sql_update = "UPDATE Produtos SET nome = '$nome', preco = '$preco', categoria = '$categoria', estoque = '$estoque' WHERE id_produto = '$id_produto'";

    if ($conn->query($sql_update) === TRUE) {
        // Sucesso: Redirecionar de volta para o painel
        echo "<script>
                alert('Produto atualizado com sucesso!');
                window.location.href = 'index.php'; // Redireciona para o painel do administrador
              </script>";
    } else {
        echo "Erro ao atualizar produto: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
</head>
<body>
    <h1>Editar Produto</h1>

    <form action="editar_produto.php?id=<?php echo $produto['id_produto']; ?>" method="POST">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? $_POST['nome'] : $produto['nome']; ?>" required><br><br>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" value="<?php echo isset($_POST['preco']) ? $_POST['preco'] : $produto['preco']; ?>" step="0.01" required><br><br>

        <label for="categoria">Categoria:</label>
        <input type="text" id="categoria" name="categoria" value="<?php echo isset($_POST['categoria']) ? $_POST['categoria'] : $produto['categoria']; ?>"><br><br>

        <label for="estoque">Estoque:</label>
        <input type="number" id="estoque" name="estoque" value="<?php echo isset($_POST['estoque']) ? $_POST['estoque'] : $produto['estoque']; ?>" required><br><br>

        <button type="submit">Atualizar Produto</button>
    </form>
</body>
</html>

