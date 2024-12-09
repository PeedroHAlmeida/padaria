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
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha
    $tipo = 'cliente'; // Todos os usuários serão clientes inicialmente

    // Verificar se o email já está cadastrado
    $sql_check_email = "SELECT * FROM usuarios WHERE email = '$email'";
    $result_check_email = $conn->query($sql_check_email);

    if ($result_check_email->num_rows > 0) {
        // Email já está em uso, exibe um alerta de erro
        echo "<script>
                alert('Este email já está em uso. Por favor, use outro email.');
                window.location.href = 'cadastro.php';
              </script>";
    } else {
        // Inserir os dados no banco se o email não existir
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES ('$nome', '$email', '$senha', '$tipo')";

        if ($conn->query($sql) === TRUE) {
            // Cadastro realizado com sucesso, exibe o alerta de sucesso e redireciona para a página de login
            echo "<script>
                    alert('Cadastro realizado com sucesso!');
                    window.location.href = 'login.php';
                  </script>";
        } else {
            echo "Erro ao cadastrar: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <h2>Cadastro de Cliente</h2>
    <form action="cadastro.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>
        
        <div class="button-container">
            <button type="submit">Cadastrar</button>
            <a href="login.php"><button type="button">Voltar</button></a>
        </div>
    </form>
</body>
</html>
