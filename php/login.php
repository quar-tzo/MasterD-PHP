<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/f5d677da44.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <?php
    session_start();
    include ("conexao.php");

    if (isset($_POST['nome'])) {
        $nome = $_POST['nome'];
        $apelido = $_POST['apelido'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO utilizadores (nome, apelido, telefone, email, palavra_passe) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $apelido, $telefone, $email, $password);
        $stmt->execute();
        $id = $conn->insert_id;

        $_SESSION['id'] = $id;
        header('Location: ../index.php');

        exit();
    }

    if (!isset($_POST['email'])) {
        echo "<form action='login.php' method='post'>
            <h2>Entrar ou Registrar-se</h2>
            <label> Email: </label>
            <input type='email' name='email' required>
            <input id='subbtn' type='submit' value='Entrar/Registrar-se'>
        </form>";
    } else {
        $email = $_POST['email'];

        $stmt = $conn->prepare("SELECT id, palavra_passe FROM utilizadores WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (isset($_POST['password'])) {
            $password = $_POST['password'];

                if (password_verify($password, $user['palavra_passe'])) {
                    $_SESSION['id'] = $user['id'];
                    header("Location: ../index.php");
                    exit();
                } else {
                    echo "<script>
                        alert('Palavra-passe incorreta!');
                    </script>";
                }
            }

            // está na base de dados
            echo "<form action='login.php' method='post'>
                <h2>Entrar</h2>
                <label> Palavra-Chave: </label>
                <input type='hidden' name='email' value='$email'>
                <input type='password' name='password' minlength='8' required>
                <input id='subbtn' type='submit' value='Entrar'>
            </form>";

        } else {
            // não está na base de dados
            echo "<form action='login.php' method='post'>
                <h2>Registrar-se</h2>
                <input type='hidden' name='email' value='$email'>
                <label> Nome: </label>
                <input type='text' name='nome' required>
                <label> Apelido: </label>
                <input type='text' name='apelido' required>
                <label> Telefone: </label>
                <input type='number' name='telefone' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' maxlength = '9' required>
                <label> Palavra-passe: </label>
                <input type='password' name='password' minlength='8' required>
                <input id='subbtn' type='submit' value='Registrar-se'>
            </form>";
        }
    }
    
    ?>
</body>
</html>