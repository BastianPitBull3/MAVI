$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: '/', // Cambia esto según tu ruta de API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ email, password }),
            success: function (response) {
                // Guardar el token en localStorage y redirigir
                if (response.token) {
                    localStorage.setItem('jwt_token', response.token);
                    window.location.href = '/dashboard';
                } else {
                    $('#loginMessage').text(response.message).css('color', 'red');
                }
            },
            error: function () {
                $('#loginMessage').text('Error al iniciar sesión. Inténtalo de nuevo.').css('color', 'red');
            }
        });
    });
});