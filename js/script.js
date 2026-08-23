/*setTimeout(() => {
    alert("Bem-Vindo!");
}, 5000);*/

function std() {
    window.scroll({top: 5000, behavior: "smooth"});
}

function stt() {
    window.scroll({top: -5000, behavior: "smooth"});
}

function rss(){
    if ($('#rss')[0].style.display != 'block') {
        $('#rss')[0].style.display = 'block';
    } else {
        $('#rss')[0].style.display = '';
    }

    var url = 'https://news.google.com/rss?hl=pt-PT&gl=PT&ceid=PT:pt-150';
            $.ajax({
                url: "https://api.rss2json.com/v1/api.json?rss_url=" + url,
                type: 'GET',
                success: function (data) {
                    objeto_json = eval(data);
                    // ler conteúdo
                    var frase = "";
                    for (i = 0; i < 5; i++){
                        frase = frase + "<h3>" + objeto_json.items[i].title + "</h3><br>";
                        frase = frase + objeto_json.items[i].description + "<br>";
                    }

                    $("#rss").html(frase);
                },
                error: function (xhr, status) {
                    alert('Ocorreu um erro!');
                }
            });
}

function validar(){
    var telemovel = document.formulario.telemovel.value;

    if (telemovel.length != 9){
        alert("O número de telemóvel deverá conter 9 dígitos.");
        return false;
    };

    if(telemovel[0] != 9) {
        alert("Telemóvel não começa com 9.");
        return false;
    };

    var select = $('#sites')[0];

    if(select.value == 0){
        alert("É necessário escolher um opção.");
        return false;
    }

    alert("Seu orçamento foi enviado com sucesso!");
    return true;

}

function modal(gpic){
    $('#modal')[0].style.display == 'block' ? $('#modal')[0].style.display = 'none' : $('#modal')[0].style.display = 'block';
    $('#modalpic')[0].src = gpic.src;
    $('#modaltext').html($(gpic).data("descricao"));
}

function orcar() {

    var valor = 0;

    var select = $('#sites')[0];

    select.value == 1 ? valor = valor + 300 : null;
    select.value == 2 ? valor = valor + 600 : null;
    select.value == 3 ? valor = valor + 200 : null;
    select.value == 4 ? valor = valor + 400 : null;
    
    $('#qs')[0].checked == true ? valor = valor + 400 : null;
    $('#oe')[0].checked == true ? valor = valor + 400 : null;
    $('#gf')[0].checked == true ? valor = valor + 400 : null;
    $('#ec')[0].checked == true ? valor = valor + 400 : null;
    $('#gi')[0].checked == true ? valor = valor + 400 : null;
    $('#no')[0].checked == true ? valor = valor + 400 : null;
    $('#rs')[0].checked == true ? valor = valor + 400 : null;

    prazo.value == 2 ? valor = valor - (valor * 0.05) : null;
    prazo.value == 3 ? valor = valor - (valor * 0.1) : null;
    prazo.value == 4 ? valor = valor - (valor * 0.15) : null;
    prazo.value >= 5 ? valor = valor - (valor * 0.2) : null;

    $('#res')[0].value = valor + "€";
    $('#resul')[0].value = valor;

}