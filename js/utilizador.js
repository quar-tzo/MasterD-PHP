function altdados(){
    var telemovel = document.dados.telefone.value;

    if (telemovel.length != 9){
        alert("O número de telemóvel deverá conter 9 dígitos.");
        return false;
    };

    if(telemovel[0] != 9) {
        alert("Telemóvel não começa com 9.");
        return false;
    };

    var nome = document.dados.nome.value;

    if(nome.trim() == "") {
        alert("O nome não pode ser vazio.");
        return false;
    };

    var nome = document.dados.apelido.value;

    if(nome.trim() == "") {
        alert("O apelido não pode ser vazio.");
        return false;
    };

    return true;

}

function altemailsenha(){
    var palavra_passe = document.emailsenha.newpassword.value;

    if (palavra_passe !== "" && palavra_passe.trim().length < 8){
        alert("A palavra-passe teve ter no minímo 8 caracteres.");
        return false;
    };

    return true;

}

function consulta(){
    var mensagem = document.novaconsulta.mensagem.value;

    if(mensagem.trim() == "") {
        alert("A mensagem não pode ser vazia.");
        return false;
    };

    var data = parseInt(document.novaconsulta.data.value.replace(/[^0-9]/g, ''), 10);

    var now = new Date;
    var ano = now.getFullYear().toString();
    var mes = (now.getMonth() + 1).toString();
    var dia = now.getDate().toString();
    mes.length == 1 ? mes = "0" + mes : null;
    dia.length == 1 ? dia = "0" + dia : null;
    var hoje = parseInt(ano + mes + dia, 10);

    if (isNaN(data) == true) {
        alert("Insira uma data para consulta.");
        return false;
    }

    if (data <= hoje) {
        alert("Data indisponível, por favor insira uma data futura.");
        return false;
    }

    alert("Sua consulta foi registrada com sucesso!");
    return true;

}

function edtconsulta(form) {
    var mensagem = form.mensagem.value;

    if (mensagem.trim() === "") {
        alert("A mensagem não pode ser vazia.");
        return false;
    }

    var dataInput = form.data.value;

    if (!dataInput) {
        alert("Insira uma data para consulta.");
        return false;
    }

    var dataConsulta = new Date(dataInput);

    var hojeMais3 = new Date();
    hojeMais3.setDate(hojeMais3.getDate() + 3);

    if (!form.data.disabled && dataConsulta < hojeMais3) {
        alert("Data indisponível, por favor insira uma data futura (mínimo 3 dias à frente).");
        return false;
    }

    return true;
}
