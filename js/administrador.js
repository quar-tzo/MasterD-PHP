function editauser(form) {
    var nome = form.nome.value;

    if (nome.trim() == "") {
        alert("O nome não pode ser vazia.");
        return false;
    }

    var apelido = form.apelido.value;

    if (apelido.trim() == "") {
        alert("O apelido não pode ser vazia.");
        return false;
    }

    var telefone = form.telefone.value;

    if (telefone.length != 9){
        alert("O número de telemóvel deverá conter 9 dígitos.");
        return false;
    };

    if(telefone[0] != 9) {
        alert("Telemóvel não começa com 9.");
        return false;
    };

    return true;
}

function editauser(form) {
    var mensagem = form.conteudo.value;

    if(mensagem.trim() == "") {
        alert("A mensagem não pode ser vazia.");
        return false;
    };

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