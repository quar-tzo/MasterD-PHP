<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/f5d677da44.js" crossorigin="anonymous"></script>
    <script src="../js/administrador.js"></script>
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

    if ($tipo != "admin") {
        header('Location: ../index.php');
        exit();
    }

    ?>
    <div id="sidebar">
        <a href="utilizador.php"><button>Voltar à Área do Utilizador</button></a>
        <a href="administrador.php"><button>Utilizadores</button></a>
        <a href="administrador.php?s=consultas"><button>Consultas</button></a>
        <a href="administrador.php?s=projetos"><button>Projetos</button></a>
        <a href="administrador.php?s=noticias"><button>Notícias</button></a>
    </div>
    <section>
        <?php
        if (isset($_GET['s'])) {
            $s = $_GET['s'];
        } else {
            $s = null;
        }

        switch ($s) {
            case 'consultas':
                if (isset($_POST['id'])) {
                    $consulta_id = (int) $_POST['id'];
                    $conteudo = $_POST['conteudo'];
                    $data = $_POST['data'];

                    $stmt = $conn->prepare("UPDATE consultas set conteudo = ?, data = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $conteudo, $data, $consulta_id);
                    $stmt->execute();

                    exit();
                }

                $stmt = $conn->prepare("SELECT consultas.id, utilizadores.nome, utilizadores.apelido, utilizadores.email, utilizadores.telefone, consultas.data, consultas.conteudo
                FROM consultas INNER JOIN utilizadores
                ON consultas.id_utilizador = utilizadores.id
                WHERE data >= CURDATE() ORDER BY data ASC");
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2 id='h2title'>Próximas Consultas</h2>";

                while ($row = $result->fetch_assoc()) {
                    $consulta_id = $row['id'];
                    $nome = $row['nome'];
                    $apelido = $row['apelido'];
                    $email = $row['email'];
                    $telefone = $row['telefone'];
                    $data = $row['data'];
                    $conteudo = $row['conteudo'];

                    echo "<form name='dados' onsubmit='return editaconsulta(this)' class='ajax-form' data-url='administrador.php?s=consultas' method='post'>
                    <label> Id: </label>
                    <input type='number' name='id' value='$consulta_id' readonly>
                    <label> Nome: </label>
                    <input type='text' value='$nome' readonly>
                    <label> Apelido: </label>
                    <input type='text' value='$apelido' readonly>
                    <label> Email: </label>
                    <input type='email' value='$email' readonly>
                    <label> Telefone: </label>
                    <input type='number' value='$telefone' readonly>
                    <label> Mensagem: </label>
                    <textarea name='conteudo' required>$conteudo</textarea>
                    <label> Data: </label>
                    <input type='date' name='data' value='$data' required>
                    <input type='submit' id='subbtn' value='Editar'>
                    </form>";
                }

                $stmt = $conn->prepare("SELECT consultas.id, utilizadores.nome, utilizadores.apelido, utilizadores.email, utilizadores.telefone, consultas.data, consultas.conteudo
                FROM consultas INNER JOIN utilizadores
                ON consultas.id_utilizador = utilizadores.id
                WHERE data < CURDATE() ORDER BY data DESC");
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2 id='h2title'>Consultas Antigas</h2>";

                while ($row = $result->fetch_assoc()) {
                    $consulta_id = $row['id'];
                    $nome = $row['nome'];
                    $apelido = $row['apelido'];
                    $email = $row['email'];
                    $telefone = $row['telefone'];
                    $data = $row['data'];
                    $conteudo = $row['conteudo'];

                    echo "<form name='dados'>
                    <label> Id: </label>
                    <input type='number' value='$consulta_id' disabled>
                    <label> Nome: </label>
                    <input type='text' value='$nome' disabled>
                    <label> Apelido: </label>
                    <input type='text' value='$apelido' disabled>
                    <label> Email: </label>
                    <input type='email' value='$email' disabled>
                    <label> Telefone: </label>
                    <input type='number' value='$telefone' disabled>
                    <label> Mensagem: </label>
                    <textarea disabled>$conteudo</textarea>
                    <label> Data: </label>
                    <input type='date' value='$data' disabled>
                    </form>";
                }
                break;
            case 'projetos':
                if (isset($_POST['apagar'])) {
                    $projeto_id = (int) $_POST['id'];

                    $stmt = $conn->prepare("DELETE FROM projetos WHERE id = ?");
                    $stmt->bind_param("i", $projeto_id);
                    $stmt->execute();
                    exit();
                } else if (isset($_POST['id'])) {
                    $projeto_id = (int) $_POST['id'];
                    $titulo = $_POST['titulo'];
                    $descricao = $_POST['descricao'];
                    $tecnologias = $_POST['tecnologias'];
                    $tempo = (int) $_POST['tempo'];

                    if ($_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                        $img = $_FILES['imagem'];

                        $extensao = pathinfo($img['name'], PATHINFO_EXTENSION);
                        $imagem = time() . '.' . $extensao;
                        $caminhoFinal = "../media/" . $imagem;
                        move_uploaded_file($img['tmp_name'], $caminhoFinal);

                        $stmt = $conn->prepare("UPDATE projetos set nome = ?, descricao = ?, tecnologias = ?, imagem = ?, tempo = ? WHERE id = ?");
                        $stmt->bind_param("ssssii", $titulo, $descricao, $tecnologias, $imagem, $tempo, $projeto_id);
                        $stmt->execute();
                        exit();
                    } else {
                        $stmt = $conn->prepare("UPDATE projetos set nome = ?, descricao = ?, tecnologias = ?, tempo = ? WHERE id = ?");
                        $stmt->bind_param("sssii", $titulo, $descricao, $tecnologias, $tempo, $projeto_id);
                        $stmt->execute();
                        exit();
                    }
                } else if (isset($_POST['titulo'])) {
                    $titulo = $_POST['titulo'];
                    $img = $_FILES['imagem'];
                    $descricao = $_POST['descricao'];
                    $tecnologias = $_POST['tecnologias'];
                    $tempo = (int) $_POST['tempo'];

                    $extensao = pathinfo($img['name'], PATHINFO_EXTENSION);
                    $imagem = time() . '.' . $extensao;
                    $caminhoFinal = "../media/" . $imagem;
                    move_uploaded_file($img['tmp_name'], $caminhoFinal);

                    $stmt = $conn->prepare("INSERT INTO projetos (nome, descricao, tecnologias, imagem, tempo) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssi", $titulo, $descricao, $tecnologias, $imagem, $tempo);
                    $stmt->execute();
                    exit();
                }

                echo "<h2 id='h2title'>Publicar Projeto</h2>";

                echo "<form class='ajax-form' enctype='multipart/form-data' data-url='administrador.php?s=projetos' method='post'>
                <label>Título:</label>
                <input type='text' name='titulo'>
                <label>Imagem:</label>
                <div id='imgdiv'>
                    <label for='imagem'>Selecionar Ficheiro</label>
                    <img class='preview' src='' alt=''>
                    <input type='file' name='imagem' id='imagem' class='imagem' accept='image/*' required>
                </div>
                <label>Descrição:</label>
                <textarea name='descricao'></textarea>
                <label>Tecnologias:</label>
                <textarea name='tecnologias'></textarea>
                <label>Tempo:</label>
                <input type='number' name='tempo' placeholder='Em meses'>
                <input type='submit' id='subbtn' value='Enviar'>
                </form>";

                $stmt = $conn->prepare("SELECT id, nome, descricao, tecnologias, imagem, tempo FROM projetos");
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2 id='h2title'>Editar Projetos</h2>";

                while ($row = $result->fetch_assoc()) {
                    $projeto_id = (int) $row['id'];
                    $titulo = $row['nome'];
                    $imagem = $row['imagem'];
                    $descricao = $row['descricao'];
                    $tecnologias = $row['tecnologias'];
                    $tempo = (int) $row['tempo'];

                    echo "<form enctype='multipart/form-data' class='ajax-form' data-url='administrador.php?s=projetos' method='post'>
                    <label>Id:</label>
                    <input type='number' name='id' value='$projeto_id' onlyread>
                    <label>Título:</label>
                    <input type='text' name='titulo' value='$titulo'>
                    <label>Imagem:</label>
                    <div id='imgdiv'>
                        <label for='imagem$projeto_id'>Selecionar Ficheiro</label>
                        <img class='preview' src='../media/$imagem' alt='$imagem'>
                        <input type='file' name='imagem' id='imagem$projeto_id' class='imagem' accept='image/*'>
                    </div>
                    <label>Descrição:</label>
                    <textarea name='descricao'>$descricao</textarea>
                    <label>Tecnologias:</label>
                    <textarea name='tecnologias'>$tecnologias</textarea>
                    <label>Tempo:</label>
                    <input type='number' name='tempo' value='$tempo' placeholder='Em meses'>
                    <div id='actionmenu'>
                        <input type='submit' class='actionbtn' name='editar' name='apagar' value='Editar'>
                        <input type='submit' class='actionbtn' id='sairbtn' name='apagar' value='Apagar'>
                    </div>
                    </form>";
                }
                break;
            case 'noticias':
                if (isset($_POST['apagar'])) {
                    $noticia_id = (int) $_POST['id'];

                    $stmt = $conn->prepare("DELETE FROM noticias WHERE id = ?");
                    $stmt->bind_param("i", $noticia_id);
                    $stmt->execute();

                    exit();
                } else if (isset($_POST['id'])) {
                    $noticia_id = (int) $_POST['id'];
                    $titulo = $_POST['titulo'];
                    $materia = $_POST['materia'];

                    $stmt = $conn->prepare("UPDATE noticias set titulo = ?, conteudo = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $titulo, $materia, $noticia_id);
                    $stmt->execute();

                    exit();
                } else if (isset($_POST['materia'])) {
                    $titulo = $_POST['titulo'];
                    $materia = $_POST['materia'];
                    $data = date("Y-m-d");

                    $stmt = $conn->prepare("INSERT INTO noticias (titulo, conteudo, data, autor) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sssi", $titulo, $materia, $data, $id);
                    $stmt->execute();
                    
                    exit();
                }

                echo "<h2 id='h2title'>Publicar Notícia</h2>
                <form class='ajax-form' data-url='administrador.php?s=noticias' method='post'>
                <label>Título:</label>
                <input type='text' name='titulo' required>
                <label>Matéria:</label>
                <textarea name='materia' required></textarea>
                <input type='submit' id='subbtn' value='Enviar'>
                </form>";

                $stmt = $conn->prepare("SELECT noticias.id, noticias.titulo, noticias.conteudo, noticias.data, utilizadores.nome, utilizadores.apelido
                FROM noticias INNER JOIN utilizadores
                ON noticias.autor = utilizadores.id");
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2 id='h2title'>Notícias Publicadas</h2>";

                while ($row = $result->fetch_assoc()) {
                    $noticia_id = $row['id'];
                    $titulo = $row['titulo'];
                    $materia = $row['conteudo'];
                    $data = $row['data'];
                    $autor_nome = $row['nome'];
                    $autor_apelido = $row['apelido'];

                    echo "<form class='ajax-form' data-url='administrador.php?s=noticias' method='post'>
                    <label>Id:</label>
                    <input type='text' name='id' value='$noticia_id' readonly>
                    <label>Título:</label>
                    <input type='text' name='titulo' value='$titulo' required>
                    <label>Matéria:</label>
                    <textarea name='materia' required>$materia</textarea>
                    <label>Autor:</label>
                    <input type='text' value='$autor_nome $autor_apelido' readonly>
                    <label>Data:</label>
                    <input type='date' value='$data' readonly>
                    <div id='actionmenu'>
                        <input type='submit' class='actionbtn' name='editar' name='apagar' value='Editar'>
                        <input type='submit' class='actionbtn' id='sairbtn' name='apagar' value='Apagar'>
                    </div>
                    </form>";
                }
                break;
            default:
                if (isset($_POST['apagar'])) {
                    $user_id = (int) $_POST['id'];

                    $stmt = $conn->prepare("DELETE FROM noticias WHERE autor = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();

                    $stmt = $conn->prepare("DELETE FROM consultas WHERE id_utilizador = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();

                    $stmt = $conn->prepare("DELETE FROM utilizadores WHERE id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();

                    exit();
                } else if (isset($_POST['id'])) {
                    $user_id = (int) $_POST['id'];
                    $nome = $_POST['nome'];
                    $apelido = $_POST['apelido'];
                    $email = $_POST['email'];
                    $telefone = $_POST['telefone'];
                    $tipo = $_POST['tipo'];

                    $stmt = $conn->prepare("UPDATE utilizadores set nome = ?, apelido = ?, email = ?, telefone = ?, tipo = ? WHERE id = ?");
                    $stmt->bind_param("sssssi", $nome, $apelido, $email, $telefone, $tipo, $user);
                    $stmt->execute();
                    
                    exit();
                }

                $stmt = $conn->prepare("SELECT id, nome, apelido, email, telefone, tipo FROM utilizadores");
                $stmt->execute();
                $result = $stmt->get_result();

                echo "<h2>Utilizadores</h2>";

                while ($row = $result->fetch_assoc()) {
                    $user_id = $row['id'];
                    $nome = $row['nome'];
                    $apelido = $row['apelido'];
                    $email = $row['email'];
                    $telefone = $row['telefone'];
                    $tipo = $row['tipo'];

                    if ($user_id != $id) {
                        echo "<form name='dados' onsubmit='return editauser(this)' class='ajax-form' data-url='administrador.php' method='post'>
                        <label> Id: </label>
                        <input type='number' name='id' value='$user_id' readonly>
                        <label> Nome: </label>
                        <input type='text' name='nome' value='$nome'>
                        <label> Apelido: </label>
                        <input type='text' name='apelido' value='$apelido'>
                        <label> Email: </label>
                        <input type='email' name='email' value='$email'>
                        <label> Telefone: </label>
                        <input type='number' name='telefone' value='$telefone' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' maxlength = '9' required>
                        <label> Tipo: </label>
                        <select name='tipo'>";
                        if ($tipo == "cliente") {
                            echo "<option value='cliente' selected>Cliente</option>
                            <option value='admin'>Administrador</option>";
                        } else {
                            echo "<option value='admin' selected>Administrador</option>
                            <option value='cliente'>Cliente</option>";
                        }
                        echo"</select>
                        <div id='actionmenu'>
                            <input type='submit' class='actionbtn' name='editar' name='apagar' value='Editar'>
                            <input type='submit' class='actionbtn' id='sairbtn' name='apagar' value='Apagar'>
                        </div>
                        </form>";
                    }
                }
                break;
        }
        ?>
    </section>
    <script src="../js/script3.js"></script>
</body>
</html>