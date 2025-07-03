$(document).ready(function () {

    consultar();
    registrarEntrada();
    capaValidar();

    $("#enviar").on("click", async function () {

        $('#enviar').prop('disabled', false);

        let confirmacion = false;

        switch ($(this).text()) {

            case "Registrar":

                if (validarenvio()) {

                    confirmacion = await confirmarAccion("Se registrará una Interconexión", "¿Está seguro?", "question");

                    if (confirmacion) {

                        var datos = new FormData();
                        datos.append('registrar', 'registrar');
                        datos.append('codigo_switch', $("#codigo_switch").val());
                        datos.append('puerto_switch', $("#puerto_switch").val());
                        datos.append('codigo_patch_panel', $("#codigo_patch_panel").val());
                        datos.append('puerto_patch_panel', $("#puerto_patch_panel").val());

                        enviaAjax(datos);

                    }
                }

            break;

            case "Modificar":

                if (validarenvio()) {

                    confirmacion = await confirmarAccion("Se modificará la Interconexión", "¿Está seguro?", "question");

                    if (confirmacion) {

                        var datos = new FormData();
                        datos.append('modificar', 'modificar');
                        datos.append('id_interconexion', $("#id_interconexion").val());
                        datos.append('codigo_switch', $("#codigo_switch").val());
                        datos.append('puerto_switch', $("#puerto_switch").val());
                        datos.append('codigo_patch_panel', $("#codigo_patch_panel").val());
                        datos.append('puerto_patch_panel', $("#puerto_patch_panel").val());

                        enviaAjax(datos);

                    }

                }

            break;

            case "Eliminar":

                confirmacion = await confirmarAccion("Se eliminará la Interconexión", "¿Está seguro?", "warning");

                if (confirmacion) {

                    var datos = new FormData();
                    datos.append('eliminar', 'eliminar');
                    datos.append('id_interconexion', $("#id_interconexion").val());

                    enviaAjax(datos);

                }

            break;

            default:

                mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());

        }

        if (!validarenvio()) {
            $('#enviar').prop('disabled', false);
        } else {
            $('#enviar').prop('disabled', true);
        };

        if (!confirmacion) {
            $('#enviar').prop('disabled', false);
        }

    });

    $("#btn-registrar").on("click", function () { 

        limpia();
        
        $("#modalTitleId").text("Registrar Interconexión");
        $("#enviar").text("Registrar");
        $("#enviar").prop('disabled', false);
        $("#enviar").attr("title", "Registrar Interconexión");
        $("#modal1").modal("show");

    }); 

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

            console.log(respuesta);

            try {

                var lee = JSON.parse(respuesta);

                if (lee.resultado == "registrar") {

                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "consultar") {

                    crearDataTable(lee.datos);

                } else if (lee.resultado == "modificar") {

                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "eliminar") {
                    
                    $("#modal1").modal("hide");
                    mensajes("success", 10000, lee.mensaje, null);
                    consultar();

                } else if (lee.resultado == "entrada") {

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

        complete: function () {},
    
    });
}

function consultar() {
    var datos = new FormData();
    datos.append('consultar', 'consultar');
    enviaAjax(datos);
}

function registrarEntrada() {
    var datos = new FormData();
    datos.append('entrada', 'entrada');
    enviaAjax(datos);
}

function capaValidar() {

    $("#codigo_switch").on("change", function () {
        let codigo_switch = $(this).val();
        if (codigo_switch && codigo_switch !== "default") {
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    get_puertos_switch: true,
                    codigo_switch: codigo_switch
                },
                dataType: "json",
                success: function (puertos) {
                    let $select = $("#puerto_switch");
                    $select.empty();
                    $select.append('<option selected value="default" disabled>Selecciona un Puerto</option>');
                    if (puertos.length > 0) {
                        puertos.forEach(function (puerto) {
                            $select.append('<option value="' + puerto + '">' + puerto + '</option>');
                        });
                    }
                }
            });
        } else {
            $("#puerto_switch").empty().append('<option selected value="default" disabled>Selecciona un Puerto</option>');
        }
    });

    $("#puerto_switch").on("input", function () {
        let val = $(this).val();
        if (!/^\d{1,2}$/.test(val) || parseInt(val) < 1) {
            $(this).addClass("is-invalid").removeClass("is-valid");
            $("#spuerto_switch").text("Debe ser un número entre 1 y 99");
        } else {
            $(this).addClass("is-valid").removeClass("is-invalid");
            $("#spuerto_switch").text("");
        }
    });

    $("#codigo_patch_panel").on("change", function () {
        let codigo_patch_panel = $(this).val();
        if (codigo_patch_panel && codigo_patch_panel !== "default") {
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    get_puertos_patch_panel: true,
                    codigo_patch_panel: codigo_patch_panel
                },
                dataType: "json",
                success: function (puertos) {
                    let $select = $("#puerto_patch_panel");
                    $select.empty();
                    $select.append('<option selected value="default" disabled>Selecciona un Puerto</option>');
                    if (puertos.length > 0) {
                        puertos.forEach(function (puerto) {
                            $select.append('<option value="' + puerto + '">' + puerto + '</option>');
                        });
                    }
                }
            });
        } else {
            $("#puerto_patch_panel").empty().append('<option selected value="default" disabled>Selecciona un Puerto</option>');
        }
    });

    $("#puerto_patch_panel").on("input", function () {
        let val = $(this).val();
        if (!/^\d{1,2}$/.test(val) || parseInt(val) < 1) {
            $(this).addClass("is-invalid").removeClass("is-valid");
            $("#spuerto_patch_panel").text("Debe ser un número entre 1 y 99");
        } else {
            let cantidad = parseInt($("#codigo_patch_panel option:selected").data("cantidad"));
            if (parseInt(val) > cantidad) {
                Swal.fire({
                    icon: 'error',
                    title: 'Puerto inválido',
                    text: 'El número de puerto no puede ser mayor que la cantidad de puertos del Patch Panel seleccionado (' + cantidad + ')'
                });
                $(this).addClass("is-invalid").removeClass("is-valid");
                $("#spuerto_patch_panel").text("Puerto fuera de rango");
            } else {
                $(this).addClass("is-valid").removeClass("is-invalid");
                $("#spuerto_patch_panel").text("");
            }
        }
    });
}

function validarenvio() {
    let valido = true;

    if ($("#codigo_switch").val() == "default") {
        $("#codigo_switch").addClass("is-invalid").removeClass("is-valid");
        $("#scodigo_switch").text("Debe seleccionar un switch");
        valido = false;
    }

    let puerto_switch = $("#puerto_switch").val();
    if (!/^\d{1,2}$/.test(puerto_switch) || parseInt(puerto_switch) < 1) {
        $("#puerto_switch").addClass("is-invalid").removeClass("is-valid");
        $("#spuerto_switch").text("Debe ser un número entre 1 y 99");
        valido = false;
    } else {
        $("#puerto_switch").addClass("is-valid").removeClass("is-invalid");
        $("#spuerto_switch").text("");
    }

    if ($("#codigo_patch_panel").val() == "default") {
        $("#codigo_patch_panel").addClass("is-invalid").removeClass("is-valid");
        $("#scodigo_patch_panel").text("Debe seleccionar un patch panel");
        valido = false;
    }

    let puerto_patch = $("#puerto_patch_panel").val();
    if (!/^\d{1,2}$/.test(puerto_patch) || parseInt(puerto_patch) < 1) {
        $("#puerto_patch_panel").addClass("is-invalid").removeClass("is-valid");
        $("#spuerto_patch_panel").text("Debe ser un número entre 1 y 99");
        valido = false;
    } else {
        let cantidad = parseInt($("#codigo_patch_panel option:selected").data("cantidad"));
        if (parseInt(puerto_patch) > cantidad) {
            Swal.fire({
                icon: 'error',
                title: 'Puerto inválido',
                text: 'El número de puerto no puede ser mayor que la cantidad de puertos del Patch Panel seleccionado (' + cantidad + ')'
            });
            $("#puerto_patch_panel").addClass("is-invalid").removeClass("is-valid");
            $("#spuerto_patch_panel").text("Puerto fuera de rango");
            valido = false;
        } else {
            $("#puerto_patch_panel").addClass("is-valid").removeClass("is-invalid");
            $("#spuerto_patch_panel").text("");
        }
    }

    return valido;
}

function crearDataTable(arreglo) {

    console.log(arreglo);

    if ($.fn.DataTable.isDataTable('#tabla1')) {
        $('#tabla1').DataTable().destroy();
    }

    $('#tabla1').DataTable({

        data: arreglo,

        columns: [
            { data: 'id_interconexion' },
            { data: 'codigo_switch' },
            { data: 'puerto_switch' },
            { data: 'codigo_patch_panel' },
            { data: 'puerto_patch_panel' },
            {
                data: null, render: function () {
                    const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update" title="Modificar Interconexión"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button onclick="rellenar(this, 1)" class="btn btn-danger" title="Eliminar Interconexión"><i class="fa-solid fa-trash"></i></button>`;
                    return botones;
                }
            }],
        order: [
            [1, 'asc'],
            [2, 'asc']
        ],
        language: {
            url: idiomaTabla,
        }
    });
}

function limpia() {

    $("#id_interconexion").val(""); 
    $("#codigo_switch").val("default");
    $("#puerto_switch").val("");
    $("#codigo_patch_panel").val("default");
    $("#puerto_patch_panel").val("");

    $('#codigo_switch').prop('disabled', false);
    $('#puerto_switch').prop('disabled', false);
    $('#codigo_patch_panel').prop('disabled', false);
    $('#puerto_patch_panel').prop('disabled', false);

    $("#codigo_switch").removeClass("is-valid is-invalid");
    $("#puerto_switch").removeClass("is-valid is-invalid");
    $("#codigo_patch_panel").removeClass("is-valid is-invalid");
    $("#puerto_patch_panel").removeClass("is-valid is-invalid");

	$("#puerto_switch").empty().append('<option selected value="default" disabled>Selecciona un Puerto</option>');
	$("#puerto_patch_panel").empty().append('<option selected value="default" disabled>Selecciona un Puerto</option>');

	$("#puerto_patch_panel").empty().append('<option selected value="default" disabled>Selecciona un Puerto</option>');
	
}

function rellenar(pos, accion) {

    limpia();
    
    let linea = $(pos).closest('tr');

    $("#id_interconexion").val($(linea).find("td:eq(0)").text().trim());
    $("#codigo_switch").val($(linea).find("td:eq(1)").text().trim());
    $("#codigo_patch_panel").val($(linea).find("td:eq(3)").text().trim());

    // Rellenar puertos del switch dinámicamente y seleccionar el correcto
    let codigo_switch = $("#codigo_switch").val();
    let puerto_switch = $(linea).find("td:eq(2)").text().trim();
    if (codigo_switch && codigo_switch !== "default") {
        $.ajax({
            url: "",
            type: "POST",
            data: {
                get_puertos_switch: true,
                codigo_switch: codigo_switch
            },
            dataType: "json",
            success: function (puertos) {
                let $select = $("#puerto_switch");
                $select.empty();
                $select.append('<option selected value="default" disabled>Selecciona un Puerto</option>');
                if (puertos.length > 0) {
                    puertos.forEach(function (puerto) {
                        $select.append('<option value="' + puerto + '">' + puerto + '</option>');
                    });
                }
                // Si el puerto del registro no está disponible (por estar ocupado por sí mismo), lo agregamos
                if ($select.find('option[value="' + puerto_switch + '"]').length === 0 && puerto_switch !== "") {
                    $select.append('<option value="' + puerto_switch + '">' + puerto_switch + '</option>');
                }
                $select.val(puerto_switch);
            }
        });
    }

    // Rellenar puertos del patch panel dinámicamente y seleccionar el correcto
    let codigo_patch_panel = $("#codigo_patch_panel").val();
    let puerto_patch_panel = $(linea).find("td:eq(4)").text().trim();
    if (codigo_patch_panel && codigo_patch_panel !== "default") {
        $.ajax({
            url: "",
            type: "POST",
            data: {
                get_puertos_patch_panel: true,
                codigo_patch_panel: codigo_patch_panel,
                puerto_actual: puerto_patch_panel // <-- para permitir el puerto actual al modificar
            },
            dataType: "json",
            success: function (puertos) {
                let $select = $("#puerto_patch_panel");
                $select.empty();
                $select.append('<option selected value="default" disabled>Selecciona un Puerto</option>');
                if (puertos.length > 0) {
                    puertos.forEach(function (puerto) {
                        $select.append('<option value="' + puerto + '">' + puerto + '</option>');
                    });
                }
                $select.val(puerto_patch_panel);
            }
        });
    }

    if (accion == 0) {

        $('#codigo_switch').prop('disabled', false);
        $('#puerto_switch').prop('disabled', false);
        $('#codigo_patch_panel').prop('disabled', false);
        $('#puerto_patch_panel').prop('disabled', false);
        $("#modalTitleId").text("Modificar Interconexión");
        $("#enviar").attr("title", "Modificar Interconexión");
        $("#enviar").text("Modificar");

    }
    else {

        $('#codigo_switch').prop('disabled', true);
        $('#puerto_switch').prop('disabled', true);
        $('#codigo_patch_panel').prop('disabled', true);
        $('#puerto_patch_panel').prop('disabled', true);
        $("#modalTitleId").text("Eliminar Interconexión");
        $("#enviar").attr("title", "Eliminar Interconexión");
        $("#enviar").text("Eliminar");
        
    }

    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");

}
