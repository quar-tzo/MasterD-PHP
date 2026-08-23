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
    <link rel="stylesheet" href="../css/noticiasprojetos.css">
</head>
<body>
    <nav>
        <button onclick="rss()"><i class="fa-solid fa-rss"></i> RSS </button>
        <button onclick="std()"><i class="fa-solid fa-phone"></i> Contactos </button>
        <a href="noticias.php"><button><i class="fa-solid fa-newspaper"></i> Notícias </button></a>
        <a href="../index.php"><button><i class="fa-solid fa-house"></i></i> Home </button></a>
        <?php
            session_start();
            include ("conexao.php");

            if (!isset($_SESSION['id'])) {
                echo "<label><a href='php/login.php'>Login</a></label>";
            } else {
                $id = (int) $_SESSION['id'];
                $stmt = $conn->prepare("SELECT nome, apelido FROM utilizadores WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($user = $result->fetch_assoc()) {
                    $nome = $user['nome'];
                    $apelido = $user['apelido'];
                }

                echo "<label><a href='utilizador.php'>$nome $apelido</a></label>";
            }
        ?>
    </nav>
    <div id="modal" style="display: none;">
        <img id="modalpic" src="media/site1.png" alt="Site da Banda Weezer" onclick="modal()">
        <a href="#" id="closemodal" onclick="modal()"><i class="fa-solid fa-x"></i></a>
        <p id="modaltext"></p>
    </div>
    <div id="rss"></div>
    <section id="galeria">
        <?php
            if (isset($_GET['id'])) {
                $projeto_id = $_GET['id'];

                $stmt = $conn->prepare("SELECT nome, descricao, tecnologias, imagem, tempo FROM projetos WHERE id = ?");
                $stmt->bind_param("i", $projeto_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $nome = $row['nome'];
                    $descricao = $row['descricao'];
                    $tecnologias = $row['tecnologias'];
                    $imagem = $row['imagem'];
                    $tempo = (int) $row['tempo'];

                    if ($tempo == 1) {
                        $periodo = "mês";
                    } else {
                        $periodo = "meses";
                    }
                    
                }

                echo "<h2>$nome</h2>
                <img id='bigpicture' src='../media/$imagem' alt='$imagem'>
                <p id='descricao'><b>Descrição: </b>$descricao</p>
                <p id='descricao'><b>Tecnologias: </b>$tecnologias</p>
                <p id='descricao'><b>Tempo de Desenvolvimento: </b>$tempo $periodo</p>";

                exit();
            }

            echo "<h2> Projetos </h2>";

            $stmt = $conn->prepare("SELECT id, nome, descricao, imagem FROM projetos LIMIT 9");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $projeto_id = $row['id'];
                $nome = $row['nome'];
                $descricao = $row['descricao'];
                $imagem = $row['imagem'];

                echo "<a href='projetos.php?id=$projeto_id'><img class='gpic' src='../media/$imagem' alt='$descricao'></a>";
            }
        ?>
    </section>
</body>
</html>