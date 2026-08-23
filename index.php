<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/f5d677da44.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <nav>
        <button onclick="rss()"><i class="fa-solid fa-rss"></i> RSS </button>
        <button onclick="std()"><i class="fa-solid fa-phone"></i> Contactos </button>
        <a href="php/noticias.php"><button><i class="fa-solid fa-newspaper"></i> Notícias </button></a>
        <a href="php/projetos.php"><button><i class="fa-solid fa-diagram-project"></i> Projetos </button></a>
        <?php
            session_start();
            include ("php/conexao.php");

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

                echo "<label><a href='php/utilizador.php'>$nome $apelido</a></label>";
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
        <h2> Projetos </h2>
        <?php
            $stmt = $conn->prepare("SELECT id, nome, descricao, imagem FROM projetos LIMIT 9");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $projeto_id = $row['id'];
                $nome = $row['nome'];
                $descricao = $row['descricao'];
                $imagem = $row['imagem'];

                echo "<img class='gpic' src='media/$imagem' alt='$descricao' data-descricao='$descricao <a href=\"php/projetos.php?id=$projeto_id\">Ver mais...</a>' onclick='modal(this)'>";
            }
        ?>
    </section>
    <section id="noticias">
        <h2> Notícias </h2>
        <?php
            $stmt = $conn->prepare("SELECT id, titulo, conteudo FROM noticias LIMIT 5");
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
                <a href='php/noticias.php?id=$noticia_id'>Ver mais...</a>
                </div>";
            }
        ?>
    </section>
    <section>
        <form name="formulario" onchange="orcar()" onsubmit="return validar(this)" method="post" action="php/orcamento.php">
            <h2> Dados </h2>
            <input type="text" name="nome" id="nome" placeholder="Nome" required>
            <input type="text" name="apelido" id="apelido" placeholder="Apelidos" required>
            <input type="email" name="email" id="email" placeholder="E-mail" required>
            <input type="number" name="telemovel" id="telemovel" placeholder="Telemóvel" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "9" required>
            <h2> Pedido de Orçamento </h2>
            <select name="sites" id="sites" placeholder="">
                <option value="0" disabled selected> Tipo de Página Web </option>
                <option value="1"> Site institucional </option>
                <option value="2"> Site dinâmico </option>
                <option value="3"> Site one-page </option>
                <option value="4"> Portal </option>
            </select>
            <input type="number" name="prazo" id="prazo" placeholder="Prazo em meses" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); prazo = this.value;" maxlength = "2" required>
            <h2> Marque os separadores desejados </h2>
            <div>
                <input type="checkbox" name="qs" id="qs" class="cbox">
                <label for="qs"> Quem Somos </label> <br>
                <input type="checkbox" name="oe" id="oe" class="cbox">
                <label for="oe"> Onde Estamos </label> <br>
                <input type="checkbox" name="gf" id="gf" class="cbox">
                <label for="gf"> Galeria de Fotografias </label> <br>
                <input type="checkbox" name="ec" id="ec" class="cbox">
                <label for="ec"> eCommerce </label> <br>
                <input type="checkbox" name="gi" id="gi" class="cbox">
                <label for="gi"> Gestão Interna </label> <br>
                <input type="checkbox" name="no" id="no" class="cbox">
                <label for="gi"> Notícias </label> <br>
                <input type="checkbox" name="rs" id="rs" class="cbox">
                <label for="rs"> Redes Sociais </label> <br>
            </div>
            <h2> Orçamento Estimado </h2>
            <p> (É um valor meramente indicativo, pode sofrer alterações) </p>
            <input type="hidden" id="resul" name=resul>
            <input value="0€" id="res" type="submit">
        </form>
    </section>
    <footer>
        <div id="infos">
            <h1> Empresa X </h1>
            <h2> Rua das Avenidas 10 </h2>
            <h2> Lisboa </h2>
            <h3> 1000-100 </h3><br>
            <h2> 234 567 891 </h2>
            <h2> exemplo@email.com </h2><br>
            <button onclick="stt()"><i class="fa-solid fa-square-caret-up"></i></button>
            <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-square-facebook"></i></a>
            <a href="https://instagram.com/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-square-instagram"></i></a>
            <a href="https://x.com" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-square-x-twitter"></i></a>
        </div>
        <form name="contacto" onsubmit="return valicon(this)" method="post" action="php/contacto.php">
            <h2> Formulário de Contacto </h2>
            <input type="text" name="nome" id="nome" placeholder="Nome" required>
            <input type="text" name="apelido" id="apelido" placeholder="Apelidos" required>
            <input type="email" name="email" id="email" placeholder="E-mail" required>
            <input type="number" name="telemovel" id="telemovel" placeholder="Telemóvel" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "9" required>
            <input type="date" name="data" id="data" required>
            <textarea name="motivo" id="motivo" placeholder="Motivo do Contacto da Reunião" required></textarea>
            <input type="submit" value="Enviar" id="subf2">
        </form>
        <div id="map"></div>
    </footer>
    <script src="js/script2.js"></script>
</body>
</html>