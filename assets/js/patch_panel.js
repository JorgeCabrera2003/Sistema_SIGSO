$(document).ready(function () {

	consultar();
	registrarEntrada();
	capaValidar();
	ConsultarPermisos();

	$("#enviar").on("click", async function () {

		
		$('#enviar').prop('disabled', false);

		let confirmacion = false;

		switch ($(this).text()) {

			case "Registrar":

				if (validarenvio()) {

					confirmacion = await confirmarAccion("Se registrará un Patch Panel", "¿Está seguro?", "question");

					if (confirmacion) {

						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('codigo_bien', $("#codigo_bien").val());
						datos.append('tipo_patch_panel', $("#tipo_patch_panel").val());
						datos.append('cantidad_puertos', $("#cantidad_puertos").val());
						datos.append('serial_patch_panel', $("#serial_patch_panel").val());

						enviaAjax(datos);

					}
				}

			break;

			case "Modificar":

				if (validarenvio()) {

					confirmacion = await confirmarAccion("Se modificará el Patch Panel", "¿Está seguro?", "question");

					if (confirmacion) {

						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('codigo_bien', $("#codigo_bien").val());
						datos.append('tipo_patch_panel', $("#tipo_patch_panel").val());
						datos.append('cantidad_puertos', $("#cantidad_puertos").val());
						datos.append('serial_patch_panel', $("#serial_patch_panel").val());

						enviaAjax(datos);

					}

				}

			break;

			case "Eliminar":

				if (validarenvio()) {

					confirmacion = await confirmarAccion("Se eliminará el Patch Panel", "¿Está seguro?", "warning");

					if (confirmacion) {

						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('codigo_bien', $("#codigo_bien").val());

						enviaAjax(datos);

					}

				}

			break;

			default:

				mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text()); //;

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
		actualizarSelectBien();
		$("#modalTitleId").text("Registrar Patch Panel");
		$("#enviar").text("Registrar");
		$("#enviar").prop('disabled', false);
		$("#enviar").attr("title", "Registrar Patch Panel");
		$("#modal1").modal("show");

	}); 

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});

	$('#codigo_bien').select2({
		dropdownParent: $('#modal1')
	});
});

function vistaPermiso(permisos = null) {

    if (!permisos || Object.keys(permisos).length == 0) {

        $('.modificar').remove();
        $('.eliminar').remove();

    } else {

        if (permisos['patch_panel']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['patch_panel']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }

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
		timeout: 10000, //tiempo maximo de espera por la respuesta del servidor

		success: function (respuesta) {

			console.log(respuesta);

			try {

				var lee = JSON.parse(respuesta);

				if (lee.resultado == "registrar") {

					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					actualizarSelectBien();
					consultar();

				} else if (lee.resultado == "consultar") {

					crearDataTable(lee.datos);

				} else if (lee.resultado == "modificar") {

					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					actualizarSelectBien();
					consultar();

				} else if (lee.resultado == "eliminar") {
					
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					actualizarSelectBien();
					consultar();

				} else if (lee.resultado == "consultar_eliminadas") {

                    TablaEliminados(lee.datos);

                } else if (lee.resultado == "reactivar") {

                    mensajes("success", 10000, lee.mensaje, null);
                    consultarEliminadas();
					actualizarSelectBien();
                    consultar();

                } else if (lee.resultado == "entrada") {


				} else if (lee.resultado == "permisos_modulo") {
					vistaPermiso(lee.permisos);

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

function crearDataTable(arreglo) {

	console.log(arreglo);

	if ($.fn.DataTable.isDataTable('#tabla1')) {

		$('#tabla1').DataTable().destroy();

	}

	$('#tabla1').DataTable({

		data: arreglo,

		columns: [
			{ data: 'codigo_bien' },
			{ data: 'cantidad_puertos' },
			{ data: 'tipo_patch_panel' },
			{ data: 'serial' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar Patch Panel"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar" title="Eliminar Patch Panel"><i class="fa-solid fa-trash"></i></button>`;
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
	ConsultarPermisos();
}


function capaValidar() {
    
    $("#codigo_bien").on("change", function () {
        if ($(this).val() == "default") {
            estadoSelect(this, "#scodigo_bien", "Debe seleccionar un Código de Bien", 0);
        } else {
            estadoSelect(this, "#scodigo_bien", "", 1);
        }
    });

    
    $("#serial_patch_panel").on("keypress", function (e) {
        validarKeyPress(/^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]*$/, e);
    });
    $("#serial_patch_panel").on("keyup", function () {
        validarKeyUp(
            /^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]{3,45}$/,
            $(this),
            $("#sserial_patch_panel"),
            "El serial debe tener de 3 a 45 caracteres y solo caracteres válidos (/-.,#)"
        );
    });

    
    $("#cantidad_puertos").on("change", function () {
        if (!["8", "12", "16", "24", "32", "48", "96"].includes($(this).val())) {
            estadoSelect(this, "#scantidad_puertos", "Debe seleccionar una cantidad válida", 0);
        } else {
            estadoSelect(this, "#scantidad_puertos", "", 1);
        }
    });

    
    $("#tipo_patch_panel").on("change", function () {
        if (!["Red", "Telefonía"].includes($(this).val())) {
            estadoSelect(this, "#stipo_patch_panel", "Debe seleccionar un tipo válido", 0);
        } else {
            estadoSelect(this, "#stipo_patch_panel", "", 1);
        }
    });
}

function validarSelect() {

	let bool = null;
	let mensaje = "";

	const validar = { bool, mensaje };

	if ($('#codigo_bien').val() === 'default') {

		estadoSelect('#codigo_bien', '#scodigo_bien', "Seleccione un Bien", 0);

		validar.bool = 0;
		validar.mensaje = "Seleccione un Bien";

	} else {

		estadoSelect('#codigo_bien', '#scodigo_bien', "", 1);

		validar.bool = 1;
		validar.mensaje = "";

	}

	return validar;
}

function validarenvio() {
    let valido = true;

    if ($("#codigo_bien").val() == "default") {
        estadoSelect("#codigo_bien", "#scodigo_bien", "Debe seleccionar un Código de Bien", 0);
        mensajes("error", 10000, "Verifica", "Debe seleccionar un Código de Bien");
        valido = false;
    }

    if (
        validarKeyUp(
            /^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]{3,45}$/,
            $("#serial_patch_panel"),
            $("#sserial_patch_panel"),
            "El serial debe tener de 3 a 45 caracteres y solo caracteres válidos (/-.,#)"
        ) == 0
    ) {
        mensajes("error", 10000, "Verifica", "El serial debe tener de 3 a 45 caracteres y solo caracteres válidos (/-.,#)");
        valido = false;
    }

    if (!["8", "12", "16", "24", "32", "48", "96"].includes($("#cantidad_puertos").val())) {
        estadoSelect("#cantidad_puertos", "#scantidad_puertos", "Debe seleccionar una cantidad válida", 0);
        mensajes("error", 10000, "Verifica", "Debe seleccionar una cantidad válida");
        valido = false;
    }

    if (!["Red", "Telefonía"].includes($("#tipo_patch_panel").val())) {
        estadoSelect("#tipo_patch_panel", "#stipo_patch_panel", "Debe seleccionar un tipo válido", 0);
        mensajes("error", 10000, "Verifica", "Debe seleccionar un tipo de patch panel válido");
        valido = false;
    }

    return valido;
}

function actualizarSelectBien() {
	
    $.ajax({
        url: '', 
        type: 'POST',
        data: { consultar_bien: 'consultar_bien' },
        success: function(respuesta) {
            try {
                let datos = JSON.parse(respuesta);
                if (Array.isArray(datos)) {

                    let $select = $("#codigo_bien");
                    $select.empty();
                    $select.append('<option selected value="default" disabled>Seleccione un Código de Bien</option>');
                    datos.forEach(function(bien) {
                        $select.append(`<option value="${bien.codigo_bien}">${bien.codigo_bien} - ${bien.descripcion}</option>`);
                    });
                }
            } catch (e) {

                mensajes("error", 5000, "Error", "No se pudo actualizar el listado de bienes.");

            }
        },
		error: function() {
            mensajes("error", 5000, "Error", "No se pudo conectar con el servidor.");
        }

    });
}


function consultarEliminadas() {

	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');

	enviaAjax(datos);
}

function TablaEliminados(arreglo) {

    if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {

        $('#tablaEliminadas').DataTable().destroy();

    }

    $('#tablaEliminadas').DataTable({

        data: arreglo,
        columns: [
            {
                data: null, render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'codigo_bien' },
			{ data: 'tipo_patch_panel' },
			{ data: 'cantidad_puertos' },
			{ data: 'serial' },
            {
                data: null,
                render: function () {
                    return `<button onclick="reactivarPatchPanel(this)" class="btn btn-success" title="reactivar Patch Panel Eliminado">
                                            <i class="fa-solid fa-recycle"></i>
                                            </button>`;
                }
            }
        ],
        language: {
            url: idiomaTabla,
        }
    });
}

async function reactivarPatchPanel(boton) {

	var confirmacion = false;
	var linea = $(boton).closest('tr');
	var codigo_bien = $(linea).find('td:eq(1)').text();

	confirmacion = await confirmarAccion("¿reactivar Patch Panel?", "¿Está seguro que desea reactivar este Patch Panel?", "question");

    if (confirmacion) {

        var datos = new FormData();
        datos.append('reactivar', 'reactivar');
        datos.append('codigo_bien', codigo_bien);
        enviaAjax(datos);

    }

}


function limpia() {

	$("#codigo_bien").val("default");
	$("#serial_patch_panel").val("");
	$("#cantidad_puertos").val("default");
	$("#tipo_patch_panel").val("default");

	$('#codigo_bien').prop('disabled', false);
	$('#tipo_patch_panel').prop('disabled', false);
	$('#cantidad_puertos').prop('disabled', false);
	$('#serial_patch_panel').prop('disabled', false);

	$("#codigo_bien").removeClass("is-valid is-invalid");
	$("#serial_patch_panel").removeClass("is-valid is-invalid");
	$("#cantidad_puertos").removeClass("is-valid is-invalid");
	$("#tipo_patch_panel").removeClass("is-valid is-invalid");
	
	$("#codigo_bien option.opcion_temporal").remove();

}

function rellenar(pos, accion) {

	limpia();
	
    let linea = $(pos).closest('tr');
    let codigoBien = $(linea).find("td:eq(0)").text().trim();
	

    if ($("#codigo_bien option[value='" + codigoBien + "']").length === 0) {
        $("#codigo_bien").append(
            $("<option>", {
                value: codigoBien,
               	text: codigoBien,
            	class: "opcion_temporal"
            })
        );
    }
    $("#codigo_bien").val(codigoBien);

	$("#cantidad_puertos").val($(linea).find("td:eq(1)").text().trim());
	$("#tipo_patch_panel").val($(linea).find("td:eq(2)").text().trim());

	$("#serial_patch_panel").val($(linea).find("td:eq(3)").text());

	if (accion == 0) {

		$('#codigo_bien').prop('disabled', true);
		$('#tipo_patch_panel').prop('disabled', false);
		$('#cantidad_puertos').prop('disabled', false);
		$('#serial_patch_panel').prop('disabled', false);
		$("#modalTitleId").text("Modificar Patch Panel")
		$("#enviar").attr("title", "Modificar Patch Panel");
		$("#enviar").text("Modificar");
	}
	else {
		$('#codigo_bien').prop('disabled', true);
		$('#tipo_patch_panel').prop('disabled', true);
		$('#cantidad_puertos').prop('disabled', true);
		$('#serial_patch_panel').prop('disabled', true);
		$("#modalTitleId").text("Eliminar Patch Panel")
		$("#enviar").attr("title", "Eliminar Patch Panel");
		$("#enviar").text("Eliminar");
	}

	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");

}
