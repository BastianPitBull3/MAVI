// Cargar clientes al cargar la página
$(document).ready(function () {
    fetchClients();
});

function fetchClients() {
    $.ajax({
        url: 'http://localhost:80/CRUD-API/app/Controllers/Api/ClientApiController.php?action=get',
        method: 'GET',
        success: function (response) {
            let rows = '';
            if(response.clients){
                response.clients.forEach(client => {
                    rows += `
                        <tr>
                            <td>${client.id}</td>
                            <td>${client.name}</td>
                            <td>${client.lastname}</td>
                            <td>${client.email}</td>
                            <td>${client.address}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editClient(${client.id}, '${client.name}', '${client.lastname}', '${client.email}', '${client.address}')">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteClient(${client.id})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
            }
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

function editClient(id, name, lastname, email, address) {
    $('#clientId').val(id);
    $('#name').val(name);
    $('#lastname').val(lastname);
    $('#email').val(email);
    $('#address').val(address);
    $('#clientModalLabel').text('Editar Cliente');
    $('#clientModal').modal('show');
}

function deleteClient(id) {
    if (confirm('¿Estás seguro de eliminar este cliente?')) {
        $.ajax({
            url: `http://localhost:80/CRUD-API/app/Controllers/Api/ClientApiController.php?action=delete`,
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

    const clientId = $('#clientId').val(); // Obtén el ID del cliente (si existe)
    const clientData = {
        id: clientId || null, // Incluye el ID solo si está disponible
        name: $('#name').val(),
        lastname: $('#lastname').val(),
        email: $('#email').val(),
        address: $('#address').val()
    };

    const url = clientId 
        ? 'http://localhost:80/CRUD-API/app/Controllers/Api/ClientApiController.php?action=update'
        : 'http://localhost:80/CRUD-API/app/Controllers/Api/ClientApiController.php?action=create';

    const method = clientId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(clientData),
        success: function (response) {
            if (response && response.message) {
                alert(response.message); // Muestra el mensaje del servidor
            } else {
                alert('Operación realizada, pero no se recibió un mensaje.');
            }
            $('#clientModal').modal('hide');
            fetchClients(); // Actualiza la lista de clientes
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            alert('Error al guardar el cliente.');
        }
    });
});
