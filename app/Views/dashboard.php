<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #3b82f6, #93c5fd);
            min-height: 100vh;
        }
        .container {
            padding: 2rem 0;
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background: #3b82f6;
            border: none;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        .modal-header {
            background-color: #3b82f6;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-white">Gestión de Clientes</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#clientModal" onclick="openClientModal()">Agregar Cliente</button>
        </div>
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientTable">
                    <!-- Aquí se cargan los clientes -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Crear/Editar Cliente -->
    <div class="modal fade" id="clientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="clientForm">
                        <input type="hidden" id="clientId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" placeholder="Nombre del cliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="lastname" placeholder="Apellido del cliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Correo electrónico" required>
                        </div>
                        <div class="mb-3">
                            <label for="tel" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="tel" placeholder="Teléfono del cliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="address" placeholder="Dirección del cliente" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cargar clientes al cargar la página
        $(document).ready(function () {
            fetchClients();
        });

        function fetchClients() {
            $.ajax({
                url: '/api/clients', // Cambia esto según tu ruta de la API
                method: 'GET',
                success: function (response) {
                    let rows = '';
                    response.clients.forEach(client => {
                        rows += `
                            <tr>
                                <td>${client.id}</td>
                                <td>${client.name}</td>
                                <td>${client.lastname}</td>
                                <td>${client.email}</td>
                                <td>${client.tel}</td>
                                <td>${client.address}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editClient(${client.id}, '${client.name}', '${client.lastname}', '${client.email}', '${client.tel}', '${client.address}')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteClient(${client.id})">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#clientTable').html(rows);
                },
                error: function () {
                    alert('Error al cargar los clientes.');
                }
            });
        }

        function openClientModal() {
            $('#clientId').val('');
            $('#clientForm')[0].reset();
            $('#clientModalLabel').text('Agregar Cliente');
        }

        function editClient(id, name, lastname, email, tel, address) {
            $('#clientId').val(id);
            $('#name').val(name);
            $('#lastname').val(lastname);
            $('#email').val(email);
            $('#tel').val(tel);
            $('#address').val(address);
            $('#clientModalLabel').text('Editar Cliente');
            $('#clientModal').modal('show');
        }

        function deleteClient(id) {
            if (confirm('¿Estás seguro de eliminar este cliente?')) {
                $.ajax({
                    url: `/api/clients/delete`,
                    method: 'DELETE',
                    contentType: 'application/json',
                    data: JSON.stringify({ id }),
                    success: function (response) {
                        alert(response.message);
                        fetchClients();
                    },
                    error: function () {
                        alert('Error al eliminar el cliente.');
                    }
                });
            }
        }

        $('#clientForm').on('submit', function (e) {
            e.preventDefault();

            const clientData = {
                id: $('#clientId').val(),
                name: $('#name').val(),
                lastname: $('#lastname').val(),
                email: $('#email').val(),
                tel: $('#tel').val(),
                address: $('#address').val()
            };

            const url = clientData.id ? '/api/clients/update' : '/api/clients/create';
            const method = clientData.id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(clientData),
                success: function (response) {
                    alert(response.message);
                    $('#clientModal').modal('hide');
                    fetchClients();
                },
                error: function () {
                    alert('Error al guardar el cliente.');
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
