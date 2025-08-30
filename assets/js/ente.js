$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	ConsultarPermisos();

	$("#enviar").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará un Ente", "¿Está seguro de realizar la acción?", "question");

					if (confirmacion) {

						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('nombre', $("#nombre").val());
						datos.append('responsable', $("#responsable").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('direccion', $("#direccion").val());
						datos.append('tipo_ente', $("#tipo_ente").val());
						enviaAjax(datos);
						envio = true;
					}

				} else {
					envio = false;
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará un Ente", "¿Está seguro de realizar la acción?", "question");

					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_ente', $("#id_ente").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('responsable', $("#responsable").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('direccion', $("#direccion").val());
						datos.append('tipo_ente', $("#tipo_ente").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{1,11}$/, $("#id_ente"), $("#sid_ente"), "") === 1) {
					confirmacion = await confirmarAccion("Se eliminará un Ente", "¿Está seguro de realizar la acción?", "warning");

					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_ente', $("#id_ente").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
				}
				break;

			default:
				mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());;
		}

		if (envio) {
			$('#enviar').prop('disabled', true);
		} else {
			$('#enviar').prop('disabled', false);
		}

		if (!confirmacion) {
			$('#enviar').prop('disabled', false);
		} else {
			$('#enviar').prop('disabled', true);
		}

	});

	$("#btn-registrar").on("click", function () { //<---- Evento del Boton Registrar
		limpia();
		$("#idEnte").remove();
		$("#modalTitleId").text("Registrar Ente");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});
});

function consultarEliminadas() {
	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');
	enviaAjax(datos);
}

function vistaPermiso(permisos = null) {

	if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {

		$('.modificar').remove();
		$('.eliminar').remove();

	} else {

		if (permisos['ente']['modificar']['estado'] == '0') {
			$('.modificar').remove();
		}

		if (permisos['ente']['eliminar']['estado'] == '0') {
			$('.eliminar').remove();
		}
	}
};

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
					consultar();

				} else if (lee.resultado == "consultar") {
					crearDataTable(lee.datos);

				} else if (lee.resultado == "consultar_eliminados") {
					iniciarTablaEliminadas(lee.datos);

				} else if (lee.resultado == "modificar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "eliminar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
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


function capaValidar() {
	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#nombre").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{4,90}$/, $(this), $("#snombre"),
			"El nombre del ente debe tener de 4 a 90 carácteres"
		);
	});

	$("#responsable").on("keypress", function (e) {
		validarKeyPress(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#responsable").on("keyup", function () {
		validarKeyUp(
			/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{4,65}$/, $(this), $("#sresponsable"),
			"El nombre del responsable debe tener de 4 a 65 carácteres"
		);
	});

	$("#telefono").on("keypress", function (e) {
		validarKeyPress(/^[0-9-]*$/, e);
	});
	$("#telefono").on("keyup", function () {
		validarKeyUp(
			/^[0-9]{4}[-]{1}[0-9]{7,8}$/, $(this), $("#stelefono"),
			"El número debe tener el siguiente formato: ****-*******"
		);
	});

	$("#direccion").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#direccion").on("keyup", function () {
		validarKeyUp(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -./#]{10,100}$/, $(this), $("#sdireccion"),
			"La dirección del Ente debe tener de 10 a 100 carácteres"
		);
	});

	$("#tipo_ente").on("change", function () {
		if ($(this).val() == "default" || $(this).val() == "") {
			estadoSelect(this, "stipo_ente", "Debe seleccionar un tipo de ente", 0);
		} else {
			estadoSelect(this, "stipo_ente", "", 1);
		}
	});
}

function validarenvio() {

	if (validarKeyUp(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del Ente debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{4,65}$/, $("#responsable"), $("#sresponsable"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del responsable debe tener de 4 a 65 carácteres");
		return false;

	} else if (/^[0-9]{4}[-]{1}[0-9]{7,8}$/, $("#telefono"), $("#stelefono"), "") {
		mensajes("error", 10000, "Verifica", "El número debe tener el siguiente formato: ****-*******");
		return false;

	} else if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -./#]{10,100}$/, $("#direccion"), $("#sdireccion"), "") == 0) {
		mensajes("error", 10000, "Verifica", "La dirección del Ente debe tener de 10 a 100 carácteres");
		return false;
	} else if ($("#tipo_ente").val() == "default" || $("#tipo_ente").val() == "") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un tipo de ente");
		return false;
	}
	return true;
}

function crearDataTable(arreglo) {

	console.log(arreglo);
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}
	$('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id' },
			{ data: 'nombre' },
			{ data: 'nombre_responsable' },
			{ data: 'telefono' },
			{ data: 'direccion' },
			{ data: 'tipo_ente' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`;
					return botones;
				}
			}],
		order: [
			[1, 'asc']
		],
		language: {
			url: idiomaTabla,
		}
	});
	ConsultarPermisos();
}

function iniciarTablaEliminadas(arreglo) {
	if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
		$('#tablaEliminadas').DataTable().destroy();
	}

	$('#tablaEliminadas').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id' },
			{ data: 'nombre' },
			{ data: 'nombre_responsable' },
			{ data: 'telefono' },
			{ data: 'direccion' },
			{ data: 'tipo_ente' },
			{
				data: null,
				render: function () {
					return `<button onclick="restaurarEnte(this)" class="btn btn-success restaurar">
                            <i class="fa-solid fa-recycle"></i>
                            </button>`;
				}
			}
		],
		order: [
			[1, 'asc']
		],
		language: {
			url: idiomaTabla,
		}
	});
	ConsultarPermisos();
}

function limpia() {
	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");

	$("#responsable").removeClass("is-valid is-invalid");
	$("#responsable").val("");

	$("#telefono").removeClass("is-valid is-invalid");
	$("#telefono").val("");

	$("#direccion").removeClass("is-valid is-invalid");
	$("#direccion").val("");

	$("#tipo_ente").removeClass('is-valid is-invalid');
	$("#tipo_ente").val("default");

	$("#nombre").prop("readOnly", false);
	$("#responsable").prop("readOnly", false);
	$("#telefono").prop("readOnly", false);
	$("#direccion").prop("readOnly", false);
	$("#tipo_ente").prop("disable", false);

	$('#enviar').prop('disabled', false);
}

function rellenar(pos, accion) {
	limpia();

	linea = $(pos).closest('tr');

	$("#idEnte").remove();
	$("#Fila1").prepend(`<div class="col-md-4" id="idEnte">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_ente" type="text" id="id_ente" readOnly>
              <span id="sid_ente"></span>
              <label for="id_ente" class="form-label">ID del Ente</label>
            </div>`);


	$("#id_ente").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());
	$("#responsable").val($(linea).find("td:eq(2)").text());
	$("#telefono").val($(linea).find("td:eq(3)").text());
	$("#direccion").val($(linea).find("td:eq(4)").text());
	buscarSelect("#tipo_ente", $(linea).find("td:eq(5)").text(), "text");

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Ente")
		$("#enviar").text("Modificar");
	} else {
		$("#nombre").prop("readOnly", true);
		$("#responsable").prop("readOnly", true);
		$("#telefono").prop("readOnly", true);
		$("#direccion").prop("readOnly", true);
		$("#tipo_ente").prop("disable", true);
		$("#modalTitleId").text("Eliminar Ente")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function restaurarEnte(boton) {
	var linea = $(boton).closest('tr');
	var id = $(linea).find('td:eq(0)').text();

	Swal.fire({
		title: '¿Restaurar Ente?',
		text: "¿Está seguro que desea restaurar este ente?",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, restaurar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			var datos = new FormData();
			datos.append('restaurar', 'restaurar');
			datos.append('id_ente', id);

			$.ajax({
				url: "",
				type: "POST",
				data: datos,
				processData: false,
				contentType: false,
				success: function (respuesta) {
					try {
						var lee = JSON.parse(respuesta);
						if (lee.estado == 1) {
							mensajes("success", null, "Ente restaurado", lee.mensaje);
							consultarEliminadas();
							consultar();
						} else {
							mensajes("error", null, "Error", lee.mensaje);
						}
					} catch (e) {
						mensajes("error", null, "Error", "Error procesando la respuesta");
					}
				},
				error: function () {
					mensajes("error", null, "Error", "No se pudo restaurar el ente");
				}
			});
		}
	});
}