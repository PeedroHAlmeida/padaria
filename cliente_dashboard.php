<?php
// Iniciar a sessão para armazenar o carrinho
session_start();

// Conexão com o banco de dados
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

// Adicionar produto ao carrinho
if (isset($_POST['adicionar_carrinho'])) {
    $produto_id = $_POST['adicionar_carrinho'];

    // Consultar produto para obter os dados e verificar o estoque
    $sql = "SELECT * FROM Produtos WHERE id_produto = '$produto_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
        $produto_nome = $produto['nome'];
        $produto_preco = $produto['preco'];
        $estoque_disponivel = $produto['estoque'];

        // Verificar se o produto já está no carrinho
        $produto_encontrado = false;
        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['id'] == $produto_id) {
                // Verifica se a quantidade a ser adicionada não ultrapassa o estoque disponível
                if ($item['quantidade'] < $estoque_disponivel) {
                    $item['quantidade'] += 1;  // Incrementa a quantidade
                } else {
                    echo "<script>alert('Não há estoque suficiente para adicionar mais deste produto.');</script>";
                }
                $produto_encontrado = true;
                break;
            }
        }

        // Se o produto não estiver no carrinho, adiciona
        if (!$produto_encontrado && $estoque_disponivel > 0) {
            $_SESSION['carrinho'][] = [
                'id' => $produto_id,
                'nome' => $produto_nome,
                'preco' => $produto_preco,
                'quantidade' => 1
            ];
        }
    }
}

// Remover item do carrinho
if (isset($_POST['remover_item'])) {
    $id_item = $_POST['remover_item'];
    
    foreach ($_SESSION['carrinho'] as $key => $item) {
        if ($item['id'] == $id_item) {
            unset($_SESSION['carrinho'][$key]);
            break;
        }
    }
    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // Reindexa o array
}

// Consulta para obter produtos com estoque > 0
$sql = "SELECT * FROM Produtos WHERE estoque > 0";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Cliente</title>
</head>
<body>
    <h1>Painel do Cliente</h1>
    <div>
        <?php
            echo "<p>Bem-vindo, " . $_SESSION['nome'] . "!</p>";
        ?>
    </div>

    <h2>Produtos Disponíveis</h2>

    <form action="cliente_dashboard.php" method="POST">
        <table border="1">
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Estoque</th>
                <th>Adicionar ao Carrinho</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                // Exibir produtos em uma tabela
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["nome"] . "</td>
                            <td>" . $row["preco"] . "</td>
                            <td>" . $row["categoria"] . "</td>
                            <td>" . $row["estoque"] . "</td>
                            <td>
                                <button type='submit' name='adicionar_carrinho' value='" . $row["id_produto"] . "'>Adicionar</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum produto disponível.</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </form>

    <h2>Carrinho de Compras</h2>
    <table border="1">
        <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Total</th>
            <th>Remover</th>
        </tr>

        <?php
        $total_carrinho = 0;
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                echo "<tr>
                        <td>" . $item['nome'] . "</td>
                        <td>" . $item['preco'] . "</td>
                        <td>" . $item['quantidade'] . "</td>
                        <td>" . ($item['preco'] * $item['quantidade']) . "</td>
                        <td>
                            <form action='cliente_dashboard.php' method='POST'>
                                <button type='submit' name='remover_item' value='" . $item['id'] . "'>Remover</button>
                            </form>
                        </td>
                    </tr>";
                $total_carrinho += $item['preco'] * $item['quantidade'];
            }
            echo "<tr><td colspan='3'>Total</td><td colspan='2'>" . $total_carrinho . "</td></tr>";
        }
        ?>

    </table>

    <!-- Botão Pagar -->
    <form action="pagamento.php" method="GET">
        <button type="submit">Pagar</button>
    </form>

</body>
</html>
