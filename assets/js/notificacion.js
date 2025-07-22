$(document).ready(function () {
    registrarEntrada();
    consultar();
    actualizarContador();
    setInterval(actualizarContador, 30000); // Actualizar contador cada 30 segundos
});

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () { },
        timeout: 10000,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                console.log(lee);
                if (lee.resultado == "consultar") {
                    iniciarTabla(lee.datos);
                } else if (lee.resultado == "entrada") {
                    // No action needed
                } else if (lee.resultado == "actualizar") {
                    consultar();
                    actualizarContador();
                } else if (lee.resultado == "contar") {
                    actualizarBadge(lee.total);
                } else if (lee.resultado == "error") {
                    mensajes("error", null, lee.mensaje, null);
                }
            } catch (e) {
                mensajes("error", null, "Error en JSON Tipo: " + e.name + "\n" +
                    "Mensaje: " + e.message + "\n" +
                    "Posición: " + e.lineNumber);
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
            } else {
                mensajes("error", null, "Ocurrió un error", "ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { },
    });
}

var tabla;

function iniciarTabla(arreglo) {
    if (tabla == null) {
        crearDataTable(arreglo);
    } else {
        tabla.destroy();
        crearDataTable(arreglo);
    }
}

function crearDataTable(arreglo) {
    tabla = $('#tabla1').DataTable({
        data: arreglo,
        order: [[0, "desc"]],
        columns: [
            { data: 'id' },
            { data: 'modulo' },
            { 
                data: 'mensaje',
                render: function(data, type, row) {
                    if (row.estado == 'Nuevo') {
                        return '<strong>' + data + '</strong>';
                    }
                    return data;
                }
            },
            { data: 'fecha' },
            { data: 'hora' },
            { 
                data: 'estado',
                render: function(data, type, row) {
                    var badgeClass = data == 'Nuevo' ? 'bg-primary' : 'bg-secondary';
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            }
        ],
        language: {
            url: idiomaTabla,
        },
        createdRow: function(row, data, dataIndex) {
            if (data.estado == 'Nuevo') {
                $(row).addClass('nueva-notificacion');
            }
            $(row).attr('data-id', data.id);
            $(row).css('cursor', 'pointer');
        }
    });

    $('#tabla1 tbody').on('click', 'tr', function() {
        var id = $(this).data('id');
        var estado = $(this).find('td:eq(5)').text().trim();
        var modulo = $(this).find('td:eq(1)').text().trim();
        
        if (estado == 'Nuevo') {
            var formData = new FormData();
            formData.append('marcar_leido', true);
            formData.append('id', id);
            enviaAjax(formData);
        }
        if (modulo != 'Notificaciones') {
            window.location="?page=" + modulo.toLowerCase();
        }
    });
}

function consultar() {
    var formData = new FormData();
    formData.append('consultar', true);
    enviaAjax(formData);
}

function registrarEntrada() {
    var formData = new FormData();
    formData.append('entrada', true);
    enviaAjax(formData);
}

function actualizarContador() {
    var formData = new FormData();
    formData.append('contar_nuevas', true);
    enviaAjax(formData);
}

function actualizarBadge(total) {
    var badge = $('.badge-notificacion');
    if (total > 0) {
        console.log("hola");
        badge.text(total).show();
    } else {
        badge.hide();
    }
}