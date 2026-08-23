document.querySelectorAll('.imagem').forEach(input => {
  input.addEventListener('change', function () {
    const file = this.files[0];
    // A imagem de preview está no mesmo container do input (mesmo pai)
    const preview = this.parentElement.querySelector('.preview');

    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
        preview.style.margin = "1vh 0"
      };
      reader.readAsDataURL(file);
    } else {
      preview.src = '';
      preview.style.display = 'none';
    }
  });
});

$(document).ready(function () {
    var clickedButtonName = null;
    var clickedButtonValue = null;

    // Detecta o botão clicado e guarda nome e valor
    $(".ajax-form input[type=submit], .ajax-form button[type=submit]").on("click", function () {
        clickedButtonName = $(this).attr("name");
        clickedButtonValue = $(this).val();
    });

    $(".ajax-form").on("submit", function (e) {
        e.preventDefault();

        var form = $(this);
        var url = form.data("url");
        var formData = new FormData(this);

        // Adiciona o botão clicado ao formData para enviar junto no POST
        if (clickedButtonName !== null) {
            formData.append(clickedButtonName, clickedButtonValue);
        }

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function (resposta) {
                location.reload();
            },
            error: function (xhr) {
                alert("Erro ao enviar: " + xhr.statusText);
            }
        });
    });
});
