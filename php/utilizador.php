<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/f5d677da44.js" crossorigin="anonymous"></script>
    <script src="../js/utilizador.js"></script>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="stylesheet" href="../css/utilizador.css">
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

    $stmt = $conn->prepare("SELECT nome, apelido, telefone, email, palavra_passe, tipo FROM utilizadores WHERE id = $id");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $nome = $user['nome'];
        $apelido = $user['apelido'];
        $telefone = $user['telefone'];
        $email = $user['email'];
        $palavra_passe = $user['palavra_passe'];
        $tipo = $user['tipo'];
    }

    ?>
    <div id="sidebar">
        <a href="../index.php"><button>Voltar à Home</button></a>
        <?php
        if ($tipo == "admin") {
            echo "<a href='administrador.php'><button>Área do Administrador</button></a>";
        }
        ?>
        <a href="utilizador.php"><button>Alterar dados pessoais</button></a>
        <a href="utilizador.php?s=novaconsulta"><button>Nova Consulta</button></a>
        <a href="utilizador.php?s=minhasconsultas"><button>Minhas Consultas</button></a>
        <a href="sair.php?apagarconta"><button id='sairbtn'>Apagar Conta</button></a>
        <a href="sair.php"><button id='sairbtn'>Logout</button></a>
    </div>
    <section>
        <?php
        if (isset($_GET['s'])) {
            $s = $_GET['s'];
        } else {
            $s = null;
        }
        switch ($s) {
            case 'novaconsulta':
                if (isset($_POST['mensagem'])) {
                    $mensagem = $_POST['mensagem'];
                    $data = $_POST['data'];

                    $stmt = $conn->prepare("INSERT INTO consultas (id_utilizador, conteudo, data) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $id, $mensagem, $data);
                    $stmt->execute();
                    header('Location: utilizador.php?s=novaconsulta');
                    exit();
                }

                echo "<form name='novaconsulta' onsubmit='return consulta(this)' class='ajax-form' data-url='utilizador.php?s=novaconsulta' method='post'>
                <h2> Marcar Consulta </h2>
                <label> Mensagem: </label>
                <textarea name='mensagem' required></textarea>
                <label> Data: </label>
                <input type='date' name='data' required>
                <input id='subbtn' type='submit' value='Confirmar'>
                </form>";
                break;

            case 'minhasconsultas':
                $hoje = new DateTime();
                $datahoje = $hoje->format('Y-m-d');

                $hoje->modify('+3 days');
                $datalimite = $hoje->format('Y-m-d');

                if (isset($_POST['data'])) {
                    $consulta_id = (int) $_POST['id'];
                    $mensagem = $_POST['mensagem'];
                    $data = $_POST['data'];

                    $stmt = $conn->prepare("UPDATE consultas set conteudo = ?, data = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $mensagem, $data, $consulta_id);
                    $stmt->execute();
                    
                    echo "<script>
                        alert('Consulta atualizada com sucesso!');
                        window.location.href = 'utilizador.php?s=minhasconsultas';
                    </script>";
                    exit();
                } else if (isset($_POST['mensagem'])) {
                    $consulta_id = (int) $_POST['id'];
                    $mensagem = $_POST['mensagem'];

                    $stmt = $conn->prepare("UPDATE consultas set conteudo = ? WHERE id = ?");
                    $stmt->bind_param("si", $mensagem, $consulta_id);
                    $stmt->execute();
                    
                    echo "<script>
                        alert('Consulta atualizada com sucesso!');
                        window.location.href = 'utilizador.php?s=minhasconsultas';
                    </script>";
                    exit();
                }

                $stmt = $conn->prepare("SELECT id, data, conteudo FROM consultas WHERE id_utilizador = ? AND data >= CURDATE() ORDER BY data ASC");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2 id='h2title'>Próximas Consultas</h2>";

                while ($row = $result->fetch_assoc()) {
                    $consulta_id = $row['id'];
                    $data = $row['data'];
                    $conteudo = $row['conteudo'];

                    if ($data <= $datalimite) {
                        echo "<form name='consulta' onsubmit='return edtconsulta(this)' class='ajax-form' data-url='utilizador.php?s=minhasconsultas' method='post'>
                        <input type='hidden' name='id' value='$consulta_id'>
                        <label> Mensagem: </label>
                        <textarea name='mensagem' required>$conteudo</textarea>
                        <label> Data: </label>
                        <input type='date' name='data' value='$data' disabled>
                        <input id='subbtn' type='submit' value='Atualizar'>
                        </form>";
                    } else {
                        echo "<form name='consulta' onsubmit='return edtconsulta(this)' class='ajax-form' data-url='utilizador.php?s=minhasconsultas' method='post'>
                        <input type='hidden' name='id' value='$consulta_id'>
                        <label> Mensagem: </label>
                        <textarea name='mensagem' required>$conteudo</textarea>
                        <label> Data: </label>
                        <input type='date' name='data' value='$data' required>
                        <input id='subbtn' type='submit' value='Atualizar'>
                        </form>";
                    }
                }

                $stmt = $conn->prepare("SELECT data, conteudo FROM consultas WHERE id_utilizador = ? AND data < CURDATE() ORDER BY data DESC");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2>Consultas Antigas</h2>";

                while ($row = $result->fetch_assoc()) {
                    $data = $row['data'];
                    $conteudo = $row['conteudo'];

                    echo "<form>
                    <label> Mensagem: </label>
                    <textarea disabled>$conteudo</textarea>
                    <label> Data: </label>
                    <input type='date' value='$data' disabled>
                    </form>";
                }
                break;
            
            default:
                if (isset($_POST['nome'])) {
                    $nome = trim($_POST['nome']);
                    $apelido = trim($_POST['apelido']);
                    $telefone = trim($_POST['telefone']);

                    $stmt = $conn->prepare("UPDATE utilizadores set nome = ?, apelido = ?, telefone = ? WHERE id = $id");
                    $stmt->bind_param("sss", $nome, $apelido, $telefone);
                    $stmt->execute();
                    
                    echo "<script>
                        alert('Nome, Apelido e Telefone alterados com sucesso!');
                        window.location.href = 'utilizador.php';
                    </script>";
                    exit();
                }

                if (isset($_POST['password']) && password_verify($_POST['password'], $palavra_passe)) {
                    $password = trim($_POST['password']);
                    $email = trim($_POST['email']);

                    $stmt = $conn->prepare("UPDATE utilizadores set email = ? WHERE id = $id");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();

                    $alert = "O email foi alterado com sucesso!";

                    if (!empty($_POST['newpassword'])) {
                        $newpassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);

                        $stmt = $conn->prepare("UPDATE utilizadores set palavra_passe = ? WHERE id = $id");
                        $stmt->bind_param("s", $newpassword);
                        $stmt->execute();

                        $alert = "A palavra-passe e o email foram alterados com sucesso!";
                    }

                    echo "<script>
                        alert('$alert');
                        window.location.href = 'utilizador.php';
                    </script>";
                    exit();
                } else if (isset($_POST['password'])) {
                    echo "<script>
                        alert('Palavra-passe incorreta!');
                    </script>";
                }

                echo "<form name='dados' onsubmit='return altdados(this)' class='ajax-form' data-url='utilizador.php' method='post'>
                <h2> Alterar nome, apelido ou telefone </h2>
                <label> Nome: </label>
                <input type='text' name='nome' value='$nome' required>
                <label> Apelido: </label>
                <input type='text' name='apelido' value='$apelido' required>
                <label> Telefone: </label>
                <input type='number' name='telefone' value='$telefone' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' maxlength = '9' required>
                <input id='subbtn' type='submit' value='Confirmar'>
                </form>";

                echo "<form name='emailsenha' onsubmit='return altemailsenha(this)' class='ajax-form' data-url='utilizador.php' method='post'>
                <h2> Alterar email ou palavra-passe </h2>
                <label> Email: </label>
                <input type='email' name='email' value='$email' required>
                <label> Palavra-passe Atual: </label>
                <input type='password' name='password' required>
                <label> Nova palavra-passe: </label>
                <input type='password' name='newpassword'>
                <input id='subbtn' type='submit' value='Confirmar'>
                </form>";
                break;
        }
        ?>
    </section>
    <script src="../js/script3.js"></script>
</body>
</html>