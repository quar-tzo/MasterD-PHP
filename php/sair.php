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
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form {
            margin: 0 1.5vw;
        }

        input {
            font-size: xx-large;
            padding: 2.5vh 2.5vw;
            border: none;
            border-radius: 1vh;
            color: #FDF5DE;
            background-color: #5FBDC4;
            transition: 0.3s;
            cursor: pointer;
        }

        input:hover {
            background-color: #EE7674;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        include ("conexao.php");

        if (!isset($_SESSION['id'])) {
            header('Location: ../index.php');
            exit();
        }

        $id = (int) $_SESSION['id'];

        if (isset($_GET['apagarconta'])) {
            echo "<form action='sair.php' method='post'>
            <input type='hidden' name='confirma'>
            <input type='submit' value='Confirmar Exclusão da Conta'>
            </form>";

            echo "<form action='utilizador.php' method='post'>
            <input type='submit' value='Voltar'>
            </form>";

            exit();
        }

        if (isset($_POST['confirma'])) {
            $stmt = $conn->prepare("DELETE FROM noticias WHERE autor = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM consultas WHERE id_utilizador = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM utilizadores WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            exit();
        }

        session_start();
        $_SESSION = array();
        session_destroy();
        header("Location: ../index.php");
        exit();
    ?>
</body>
</html>