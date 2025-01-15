$('#loginForm').submit(function(e) {
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();

        // Cambia esta URL al endpoint real donde se hacen las validaciones
        var endpoint = 'http://localhost:8000/api/auth.php';

        $.ajax({
            url: endpoint,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ email: email, password: password }),
            success: function(response) {
                if (response.token) {
                    localStorage.setItem('token', response.token);
                    console.log("Token recibido:", response.token);
                    window.location.href = 'dashboard.php'; // Cambia a tu archivo dashboard si es necesario
                } else {
                    $('#error-message').text(response.message || 'Error desconocido');
                }
            },
            error: function(xhr, status, error) {
                $('#error-message').text("Error en la conexión al servidor: " + (xhr.responseJSON?.message || error));
            }
        });
    });