var map = L.map('map').setView([40.00005, -8.41048], 7);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var Coimbra = new Object();
Coimbra.latitude = 40.22174;
Coimbra.longitude = -8.43555;

var Porto = new Object();
Porto.latitude = 41.15647;
Porto.longitude = -8.61011;

var Lisboa = new Object();
Lisboa.latitude = 38.73355;
Lisboa.longitude = -9.14119;

var MDC = L.marker([Coimbra.latitude, Coimbra.longitude]).addTo(map);
var MDP = L.marker([Porto.latitude, Porto.longitude]).addTo(map);
var MDL = L.marker([Lisboa.latitude, Lisboa.longitude]).addTo(map);

MDC.bindPopup("Master D Coimbra");
MDP.bindPopup("Master D Porto");
MDL.bindPopup("Master D Lisboa");

function success(pos) {
    var lat, lon;
    var latitude = pos.coords.latitude;
    var longitude = pos.coords.longitude;

    Coimbra.distancia = Math.hypot(latitude - Coimbra.latitude, longitude - Coimbra.longitude);
    Porto.distancia = Math.hypot(latitude - Porto.latitude, longitude - Porto.longitude);
    Lisboa.distancia = Math.hypot(latitude - Lisboa.latitude, longitude - Lisboa.longitude);

    var distancias = [Coimbra.distancia, Porto.distancia, Lisboa.distancia];

    switch (distancias.indexOf(Math.min(...distancias))) {
        case 0:
            lat = Coimbra.latitude;
            lon = Coimbra.longitude;
            break;
        case 1:
            lat = Porto.latitude;
            lon = Porto.longitude;
            break;
        case 2:
            lat = Lisboa.latitude;
            lon = Lisboa.longitude;
            break;
        default:
            break;
    }

    L.Routing.control({
        waypoints: [
          L.latLng(latitude, longitude),
          L.latLng(lat, lon)
        ]
    }).addTo(map);

}

navigator.geolocation.getCurrentPosition(success);

function valicon(){
    var telemovel = document.contacto.telemovel.value;

    if (telemovel.length != 9){
        alert("O número de telemóvel deverá conter 9 dígitos.");
        return false;
    };

    if(telemovel[0] != 9) {
        alert("Telemóvel não começa com 9.");
        return false;
    };

    var email = document.contacto.email.value;
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!re.test(String(email).toLowerCase())){
        alert("Email inválido");
        return false;
    };

    var data = parseInt(document.contacto.data.value.replace(/[^0-9]/g, ''), 10);

    var now = new Date;
    var ano = now.getFullYear().toString();
    var mes = (now.getMonth() + 1).toString();
    var dia = now.getDate().toString();
    mes.length == 1 ? mes = "0" + mes : null;
    dia.length == 1 ? dia = "0" + dia : null;
    var hoje = parseInt(ano + mes + dia, 10);

    if (isNaN(data) == true) {
        alert("Insira uma data para reunião.");
        return false;
    }

    if (data <= hoje) {
        alert("Data indisponível, por favor insira uma data futura.");
        return false;
    }

    alert("Seu formulário de contacto foi enviado com sucesso!");
    return true;

}

const imgs = document.querySelectorAll('#galeria img');
imgs.forEach(img => {
    img.onload = () => {
        if (img.naturalHeight > img.naturalWidth) {
            img.style.height = img.offsetWidth + "px";
            img.style.objectFit = "cover";
        }
    };
});