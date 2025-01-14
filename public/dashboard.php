<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/app.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = 'login.html';
                return;
            }

            // Validar el JWT al iniciar sesión
            $.ajax({
                url: 'http://localhost:8000/api/auth.php',  // Verificar JWT
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ token: token }),
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.message === 'Token inválido o expirado') {
                        localStorage.removeItem('token');
                        window.location.href = 'login.html';
                    } else {
                        $('#user-info').text("Bienvenido, " + data.email);
                        loadClients();  // Cargar clientes al iniciar sesión
                    }
                },
                error: function() {
                    $('#user-info').text("Error en la conexión.");
                }
            });

            // Función para cargar los clientes
            function loadClients() {
                $.ajax({
                    url: 'http://localhost:8000/api/clients.php?action=get',  // Endpoint para obtener los clientes
                    type: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        var clients = data.clients;
                        var html = '';
                        if (Array.isArray(clients)) {
                            clients.forEach(function(client) {
                                html += `
                                    <tr>
                                        <td>${client.id}</td>
                                        <td>${client.name}</td>
                                        <td>${client.lastname}</td>
                                        <td>${client.email}</td>
                                        <td>${client.tel}</td>
                                        <td>${client.address}</td>
                                        <td>
                                            <button onclick="showEditModal(${client.id})">Editar</button>
                                            <button onclick="deleteClient(${client.id})">Eliminar</button>
                                        </td>
                                    </tr>
                                `;
                            });
                            $('#clients-table tbody').html(html);
                        } else {
                            console.error("La respuesta no contiene un array de clientes.");
                        }
                    },
                    error: function() {
                        alert("Error al cargar los clientes.");
                    }
                });
            }

            // Función para crear un cliente
            $('#create-client-form').submit(function(e) {
                e.preventDefault();
                const name = $('#create-name').val();
                const lastname = $('#create-lastname').val();
                const email = $('#create-email').val();
                const tel = $('#create-tel').val();
                const address = $('#create-address').val();

                $.ajax({
                    url: 'http://localhost:8000/api/clients.php?action=create',  // Endpoint para crear un cliente
                    type: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    data: JSON.stringify({ name: name, lastname: lastname, email: email, tel: tel, address: address }),
                    success: function(response) {
                        alert("Cliente creado");
                        loadClients();  // Recargar la lista de clientes
                    },
                    error: function() {
                        alert("Error al crear el cliente.");
                    }
                });
            });

            // Función para mostrar modal para editar un cliente
            window.showEditModal = function(id) {
                $.ajax({
                    url: 'http://localhost:8000/api/clients.php?action=get',  // Endpoint para obtener todos los clientes
                    type: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        var client = data.clients.find(c => c.id === id);
                        if (client) {
                            $('#edit-id').val(client.id);
                            $('#edit-name').val(client.name);
                            $('#edit-lastname').val(client.lastname);
                            $('#edit-email').val(client.email);
                            $('#edit-tel').val(client.tel);
                            $('#edit-address').val(client.address);
                            $('#edit-client-modal').addClass('show');
                        }
                    },
                    error: function() {
                        alert("Error al cargar los datos del cliente.");
                    }
                });
            };

            // Función para editar un cliente
            $('#edit-client-form').submit(function(e) {
                e.preventDefault();
                const id = $('#edit-id').val();
                const name = $('#edit-name').val();
                const lastname = $('#edit-lastname').val();
                const email = $('#edit-email').val();
                const tel = $('#edit-tel').val();
                const address = $('#edit-address').val();

                $.ajax({
                    url: 'http://localhost:8000/api/clients.php?action=update',  // Endpoint para actualizar el cliente
                    type: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    data: JSON.stringify({ id: id, name: name, lastname: lastname, email: email, tel: tel, address: address }),
                    success: function(response) {
                        alert("Cliente actualizado");
                        loadClients();  // Recargar la lista de clientes
                        $('#edit-client-modal').removeClass('show');  // Cerrar el modal
                    },
                    error: function() {
                        alert("Error al actualizar el cliente.");
                    }
                });
            });

            // Función para eliminar un cliente
            window.deleteClient = function(id) {
                if (confirm('¿Estás seguro de eliminar este cliente?')) {
                    $.ajax({
                        url: 'http://localhost:8000/api/clients.php?action=delete',  // Endpoint para eliminar el cliente
                        type: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        data: JSON.stringify({ id: id }),
                        success: function(response) {
                            alert("Cliente eliminado");
                            loadClients();  // Recargar la lista de clientes
                        },
                        error: function() {
                            alert("Error al eliminar el cliente.");
                        }
                    });
                }
            };
        });
    </script>
</head>
<body>
    <h1>Dashboard</h1>
    <p id="user-info"></p>
    <button onclick="logout()">Cerrar sesión</button>

    <!-- Formulario para crear cliente -->
    <h2>Crear Cliente</h2>
    <form id="create-client-form">
        <input type="text" id="create-name" placeholder="Nombre" required><br>
        <input type="text" id="create-lastname" placeholder="Apellido" required><br>
        <input type="email" id="create-email" placeholder="Correo electrónico" required><br>
        <input type="text" id="create-tel" placeholder="Teléfono" required><br>
        <input type="text" id="create-address" placeholder="Dirección" required><br>
        <button type="submit">Crear</button>
    </form>

    <!-- Tabla de clientes -->
    <h2>Clientes</h2>
    <table id="clients-table" border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo electrónico</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los clientes se cargarán aquí -->
        </tbody>
    </table>

    <!-- Modal para editar cliente -->
    <div id="edit-client-modal" style="visibility:hidden;">
        <h2>Editar Cliente</h2>
        <form id="edit-client-form">
            <input type="hidden" id="edit-id">
            <input type="text" id="edit-name" placeholder="Nombre" required><br>
            <input type="text" id="edit-lastname" placeholder="Apellido" required><br>
            <input type="email" id="edit-email" placeholder="E-Mail" required><br>
            <input type="text" id="edit-tel" placeholder="Teléfono" required><br>
            <input type="text" id="edit-address" placeholder="Dirección" required><br>

            <button type="submit">Actualizar</button>
            <button type="button" onclick="$('#edit-client-modal').removeClass('show')">Cerrar</button>
        </form>
    </div>

    <script>
        function logout() {
            localStorage.removeItem('token');
            window.location.href = 'login.html';
        }
    </script>
</body>
</html>
