$(document).ready(function () {
	consultar();

	$("#btn-cargar").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		confirmacion = await confirmarAccion("Se cargarán (o recargarán) los módulos", "¿Está seguro de realizar la acción?", "question");
		if (confirmacion) {
			var datos = new FormData();
			datos.append('cargar', 'cargar');
			enviaAjax(datos);
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

	$("#btn-comprobar").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		confirmacion = await confirmarAccion("Se registrará una comprobación de los módulos del sistema", "¿Está seguro de realizar la acción?", "question");
		if (confirmacion) {
			var datos = new FormData();
			datos.append('comprobar', 'comprobar');
			enviaAjax(datos);
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
		$("#idRol").remove();
		$("#modalTitleId").text("Registrar Rol");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar
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

				} else if (lee.resultado == "comprobar") {
					selectDependencia(lee.datos);

				} else if (lee.resultado == "cargar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

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
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#nombre").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $(this), $("#snombre"),
			"El nombre del rol debe tener de 4 a 45 carácteres"
		);
	});
}

function validarenvio() {
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del rol debe tener de 4 a 45 carácteres");
		return false;

	}
	return true;
}

function crearDataTable(arreglo) {
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}

	$('#tabla1').DataTable({
		data: arreglo,
		order: [[0, "asc"]],
		columns: [
			{ data: 'id_modulo' },
			{ data: 'nombre_modulo' },
		],
		language: {
			url: idiomaTabla,
		}
	});
}

function limpia() {
	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");

	$("#id_rol").removeClass("is-valid is-invalid");
	$("#id_rol").val("default");

	$("#nombre").prop('readOnly', false);
	$("#id_rol").prop('disabled', false);
	$('#enviar').prop('disabled', false);
}


function rellenar(pos, accion) {
	limpia();
	linea = $(pos).closest('tr');

	$("#idRol").remove();
	$("#Fila1").prepend(`<div class="col-md-4" id="idRol">
            <div class="form-floating">
              <input placeholder="" class="form-control" name="id_rol" type="text" id="id_rol" readOnly>
              <span id="sid_rol"></span>
              <label for="id_rol" class="form-label">ID de la Rol</label>
            </div>`);

	$("#id_rol").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());

	if (accion == 0) {
		$("#id_rol").prop('readOnly', true);
		$("#id_rol").val($(linea).find("td:eq(0)").text());
		$("#nombre").val($(linea).find("td:eq(1)").text());

		$("#modalTitleId").text("Modificar Rol");
		$("#enviar").text("Modificar");
		$("#modal1").modal("show");
	} else {
		$("#nombre").prop('readOnly', true);
		$("#id_rol").prop('disabled', true);
		$("#modalTitleId").text("Eliminar Rol");
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
}