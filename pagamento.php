<?php
// Iniciar a sessão para acessar o carrinho
session_start();

// Verificar se o carrinho está vazio
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<script>alert('Seu carrinho está vazio.'); window.location.href = 'cliente_dashboard.php';</script>";
    exit();
}

// Calculando o total do carrinho
$total_carrinho = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total_carrinho += $item['preco'] * $item['quantidade'];
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Padaria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Se o pagamento for bem-sucedido
if (isset($_POST['finalizar_pagamento'])) {
    // Processo de atualização do estoque
    foreach ($_SESSION['carrinho'] as $item) {
        $produto_id = $item['id'];
        $quantidade_comprada = $item['quantidade'];

        // Atualizando o estoque no banco de dados
        $sql_atualizar_estoque = "UPDATE Produtos SET estoque = estoque - $quantidade_comprada WHERE id_produto = $produto_id";
        
        if ($conn->query($sql_atualizar_estoque) === TRUE) {
            // Estoque atualizado com sucesso
        } else {
            echo "<script>alert('Erro ao atualizar estoque: " . $conn->error . "');</script>";
        }
    }

    // Simulação de pagamento bem-sucedido
    echo "<script>alert('Pagamento realizado com sucesso!'); window.location.href = 'cliente_dashboard.php';</script>";

    // Limpar o carrinho após o pagamento
    unset($_SESSION['carrinho']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
</head>
<body>
    <h1>Detalhes da Compra</h1>
    <h2>Carrinho de Compras</h2>

    <table border="1">
        <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Total</th>
        </tr>

        <?php
        // Exibir os itens do carrinho
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                echo "<tr>
                        <td>" . $item['nome'] . "</td>
                        <td>" . $item['preco'] . "</td>
                        <td>" . $item['quantidade'] . "</td>
                        <td>" . ($item['preco'] * $item['quantidade']) . "</td>
                    </tr>";
            }
        }
        ?>
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong><?php echo "R$ " . number_format($total_carrinho, 2, ',', '.'); ?></strong></td>
        </tr>
    </table>

    <h2>Finalizar Pagamento</h2>
    <form action="pagamento.php" method="POST">
        <p>Confirme os itens acima e clique no botão abaixo para finalizar a compra.</p>
        <button type="submit" name="finalizar_pagamento">Finalizar Compra</button>
    </form>

    <br>
    <a href="cliente_dashboard.php">Voltar para o Painel</a>
</body>
</html>
