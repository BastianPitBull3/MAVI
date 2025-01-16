$(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
        event.preventDefault(); 

        const email = $('#email').val();
        console.log(email);
        const password = $('#password').val();
        console.log(password);

        $.ajax({
            url: 'http://localhost:80/CRUD-API/app/Controllers/Api/AuthController.php', 
            type: 'POST',
            contentType: 'application/json', 
            data: JSON.stringify({ email, password }), 
            success: function(response) {
                if (response.success) {
                    $('#loginMessage').html('<div class="alert alert-success">Inicio de sesión exitoso. Redirigiendo...</div>');
                    setTimeout(function() {
                        window.location.href = "../app/Views/dashboard.php";
                    }, 2000);
                } else {
                    $('#loginMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(jqXHR) {
                console.error("Error:", jqXHR);
                let errorMessage = "Ocurrió un error al procesar la solicitud.";
                if (jqXHR.status === 404) {
                    errorMessage = "Usuario no encontrado.";
                }
                $('#loginMessage').html('<div class="alert alert-danger">' + errorMessage + '</div>');
            }
        });
    });
});