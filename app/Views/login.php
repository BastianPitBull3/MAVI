<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #6c63ff, #c3cfe2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        .btn-primary {
            background: #6c63ff;
            border: none;
        }
        .btn-primary:hover {
            background: #4b47d0;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
        <div id="loginMessage" class="mt-3 text-center"></div>
    </div>

    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

                const email = $('#email').val();
                const password = $('#password').val();

                $.ajax({
                    url: '/api/login', // Cambia esto según tu ruta de API
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
    </script>
</body>
</html>
