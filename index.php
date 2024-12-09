<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
</head>
<body>
    <h1>Painel do Administrador</h1>
    <div>
        <?php
            session_start();
            echo "<p>Bem-vindo, " . $_SESSION['nome'] . "!</p>";
        ?>
    </div>

    <h2>Adicionar Produto</h2>
    <form action="cadastrar_produto.php" method="POST">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" required><br><br>

        <label for="categoria">Categoria:</label>
        <input type="text" id="categoria" name="categoria"><br><br>

        <label for="estoque">Estoque:</label>
        <input type="number" id="estoque" name="estoque" required><br><br>

        <button type="submit">Cadastrar Produto</button>
    </form>

    <h2>Produtos Disponíveis</h2>
    <div id="produtos">
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

        // Captura a variável de ordenação (caso exista)
        $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'nome'; // Padrão: ordenar por 'nome'

        // Garantir que a variável de ordenação seja segura
        $valid_columns = ['nome', 'preco', 'categoria', 'estoque'];
        if (!in_array($order_by, $valid_columns)) {
            $order_by = 'nome'; // Fallback para 'nome' se o parâmetro for inválido
        }

        // Consulta SQL com ordenação
        $sql = "SELECT * FROM Produtos ORDER BY $order_by";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Exibir produtos em uma tabela
            echo "<table border='1'>
                    <tr>
                        <th><a href='?order_by=nome'>Nome</a></th>
                        <th><a href='?order_by=preco'>Preço</a></th>
                        <th><a href='?order_by=categoria'>Categoria</a></th>
                        <th><a href='?order_by=estoque'>Estoque</a></th>
                        <th>Editar</th>
                        <th>Excluir</th> <!-- Nova coluna para excluir -->
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["nome"] . "</td>
                        <td>" . $row["preco"] . "</td>
                        <td>" . $row["categoria"] . "</td>
                        <td>" . $row["estoque"] . "</td>
                        <td><a href='editar_produto.php?id=" . $row["id_produto"] . "'><button type='button'>Editar</button></a></td>
                        <td><a href='excluir_produto.php?id=" . $row["id_produto"] . "'><button type='button'>Excluir</button></a></td> <!-- Botão de excluir -->
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "Nenhum produto encontrado.";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
