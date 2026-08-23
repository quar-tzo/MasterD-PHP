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
        <a href="../index.php"><button><i class="fa-solid fa-house"></i></i> Home </button></a>
        <a href="projetos.php"><button><i class="fa-solid fa-diagram-project"></i> Projetos </button></a>
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
    <section id="noticias">
        <?php
            if (isset($_GET['id'])) {
                $noticia_id = $_GET['id'];

                $stmt = $conn->prepare("SELECT titulo, conteudo, data, nome, apelido FROM noticias INNER JOIN utilizadores ON noticias.autor = utilizadores.id WHERE noticias.id = ?");
                $stmt->bind_param("i", $noticia_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $titulo = $row['titulo'];
                    $materia = $row['conteudo'];
                    $data = date("d/m/Y", strtotime($row['data']));
                    $nome = $row['nome'];
                    $apelido = $row['apelido'];
                }

                echo "<h2>$titulo</h2>
                <h3 class='namedate'>Por $nome $apelido</h3>
                <h4 class='namedate'>$data</h4>
                <p id='materia'>$materia</p>";

                exit();
            }

            echo "<h2> Notícias </h2>";
        
            $stmt = $conn->prepare("SELECT id, titulo, conteudo FROM noticias");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $noticia_id = $row['id'];
                $titulo = ucfirst($row['titulo']);
                $materia = $row['conteudo'];

                $palavras = explode(" ", $materia);
                $primeiras10 = array_slice($palavras, 0, 10);
                $materia = implode(" ", $primeiras10);

                echo "<div class='noticia'>
                <h3> $titulo </h3>
                <p> $materia... </p>
                <a href='noticias.php?id=$noticia_id'>Ver mais...</a>
                </div>";
            }
        ?>
    </section>
</body>
</html>