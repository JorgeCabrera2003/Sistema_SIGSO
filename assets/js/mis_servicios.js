$(document).ready(function () {
    // Verificar que userCedula esté definido
    if (typeof userCedula === 'undefined') {
        console.error('userCedula no está definido. Asegúrate de establecer esta variable globalmente.');
        userCedula = ''; // Asignar valor por defecto para evitar errores
    }

    consultar();
    registrarEntrada();
    capaValidar();

    // Inicializar select2 para equipos con dropdownParent igual que en bien.js
    $("#select_equipos").select2({
        dropdownParent: $('#modal1'),
        width: '100%',
        placeholder: 'Seleccione un equipo',
        allowClear: true
    });

    // Al abrir el modal, cargar los equipos del solicitante
    $("#btn-solicitud").on("click", function () {
        limpia();
        $("#modalTitleId").text("Crear Solicitud");
        $("#solicitar").text("Enviar");
        cargarEquiposSolicitante();
        $("#modal1").modal("show");
    });

    // Función robusta para cargar equipos asociados al empleado
    async function cargarEquiposSolicitante() {
        const $select = $('#select_equipos');
        $select.empty().append('<option value="">Cargando equipos...</option>');
        $select.val('').trigger('change');

        try {
            const response = await $.ajax({
                type: 'POST',
                url: '?page=mis_servicios',
                data: {
                    peticion: 'consultar_equipos_empleado',
                    cedula_empleado: userCedula
                },
                dataType: 'json'
            });

            $select.empty();
            $select.append('<option value="">Seleccione un equipo (opcional)</option>');

            if (response && Array.isArray(response.datos) && response.datos.length > 0) {
                response.datos.forEach(equipo => {
                    let texto = equipo.tipo_equipo || 'Equipo';
                    if (equipo.serial) texto += ` (${equipo.serial})`;
                    if (equipo.descripcion) texto += ` - ${equipo.descripcion}`;
                    $select.append(
                        new Option(texto, equipo.id_equipo)
                    );
                });
            } else {
                $select.append('<option value="">No tiene equipos asignados</option>');
            }

            // Re-inicializa select2 para refrescar opciones (igual que en bien.js)
            $select.select2({
                dropdownParent: $('#modal1'),
                width: '100%',
                placeholder: 'Seleccione un equipo',
                allowClear: true
            });
            $select.val('').trigger('change');
        } catch (error) {
            console.error('Error al cargar equipos:', error);
            $select.empty().append('<option value="">Error al cargar equipos</option>');
            $select.val('').trigger('change');
            mensajes("error", null, "Error al cargar equipos", "Intente nuevamente más tarde");
        }
    }

    $("#solicitar").on("click", async function () {
        $('#solicitar').prop('disabled', true);

        if ($(this).text() === "Enviar") {
            if (validarenvio()) {
                const confirmacion = await confirmarAccion(
                    "Se enviará su Solicitud",
                    "¿Está seguro de enviar esta solicitud?",
                    "question"
                );

                if (confirmacion) {
                    const datos = new FormData();
                    datos.append('solicitud', '');
                    datos.append('motivo', $("#motivo").val());

                    // Agregar el id del equipo seleccionado si existe
                    const idEquipo = $("#select_equipos").val();
                    if (idEquipo && idEquipo !== "") {
                        datos.append('id_equipo', idEquipo);
                    }

                    enviaAjax(datos);
                }
            }
        }

        $('#solicitar').prop('disabled', false);
    });

    // Función auxiliar para obtener el ID del equipo por código de bien
    async function obtenerIdEquipoPorCodigoBien(codigoBien) {
        try {
            const response = await $.ajax({
                type: 'POST',
                url: '',
                data: {
                    peticion: 'obtener_id_por_bien',
                    codigo_bien: codigoBien
                },
                dataType: 'json'
            });

            if (response.id_equipo) {
                return response.id_equipo;
            }
            return null;
        } catch (error) {
            console.error('Error al obtener ID de equipo:', error);
            return null;
        }
    }

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
                console.log(respuesta);
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.resultado == "registrar") {
                        $("#modal1").modal("hide");
                        mensajes("success", 10000, "Se envió la solicitud exitosamente", null);
                        consultar();
                    } else if (lee.resultado == "consultar") {
                        iniciarTabla(lee.datos);
                    } else if (lee.resultado == "entrada") {
                        // No hacer nada específico
                    } else if (lee.resultado == "error") {
                        mensajes("error", null, lee.mensaje, null);
                    }
                } catch (e) {
                    mensajes("error", null, "Error en JSON Tipo: " + e.name + "\n" +
                        "Mensaje: " + e.message + "\n" +
                        "Posición: " + e.lineNumber + ":" + e.columnNumber + "\n" +
                        "Stack: " + e.stack, null);
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

    function capaValidar() {
        $("#motivo").on("keypress", function (e) {
            validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
        });

        $("#motivo").on("keyup", function () {
            validarKeyUp(
                /^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/,
                $(this),
                $("#smotivo"),
                "El motivo debe tener entre 3 y 30 caracteres"
            );
        });
    }

    function validarenvio() {
        if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/, $("#motivo"),
            $("#smotivo"), "El motivo debe de tener 3 letras minimo") == 0) {
            mensajes("error", 10000, "Verifica", "El motivo debe tener entre 3 y 30 caracteres");
            return false;
        }
        return true;
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
        console.log(arreglo);
        tabla = $('#tabla1').DataTable({
            data: arreglo,
            columns: [
                { data: 'ID' },
                { data: 'Motivo' },
                { data: 'Inicio' },
                {
                    data: 'Estatus',
                    render: function (data) {
                        if (data == "Enviado") {
                            return `<span class="badge bg-primary">${data}</span>`;
                        } else if (data == "Pendiente") {
                            return `<span class="badge bg-warning text-dark">${data}</span>`;
                        } else if (data == "Finalizado") {
                            return `<span class="badge bg-success">${data}</span>`;
                        } else {
                            return `<span class="badge bg-info">${data}</span>`;
                        }
                    }
                },
                { data: 'Resultado' }
            ],
            language: {
                url: idiomaTabla,
            }
        });
    }

    function limpia() {
        $("#motivo").removeClass("is-valid is-invalid");
        $("#motivo").val("");
        $('#select_equipos').val('').trigger('change');
    }

    function consultar() {
        var datos = new FormData();
        datos.append('consultar', '');
        enviaAjax(datos);
    }

    function registrarEntrada() {
        var datos = new FormData();
        datos.append('entrada', '');
        enviaAjax(datos);
    }
});