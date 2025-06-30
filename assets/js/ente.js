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
});

function vistaPermiso(permisos = null) {

if(Array.isArray(permisos) || Object.keys(permisos).length === 0 || permisos === null){

	$('.modificar').remove();
	$('.eliminar').remove();

} else {

	if (permisos['ente']['modificar']['estado'] === '0') {
		$('.modificar').remove();
	}

	if (permisos['ente']['eliminar']['estado'] === '0') {
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
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#nombre").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,90}$/, $(this), $("#snombre"),
			"El nombre del ente debe tener de 4 a 90 carácteres"
		);
	});

	$("#responsable").on("keypress", function (e) {
		validarKeyPress(/^[a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#responsable").on("keyup", function () {
		validarKeyUp(
			/^[a-zA-ZáéíóúüñÑçÇ -.]{4,65}$/, $(this), $("#sresponsable"),
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
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#direccion").on("keyup", function () {
		validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -./#]{10,100}$/, $(this), $("#sdireccion"),
			"La dirección del Ente debe tener de 10 a 100 carácteres"
		);
	});
}

function validarenvio() {

	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del Ente debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[a-zA-ZáéíóúüñÑçÇ -.]{4,65}$/, $("#responsable"), $("#sresponsable"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del responsable debe tener de 4 a 65 carácteres");
		return false;

	} else if (/^[0-9]{4}[-]{1}[0-9]{7,8}$/, $("#telefono"), $("#stelefono"), "") {
		mensajes("error", 10000, "Verifica", "El número debe tener el siguiente formato: ****-*******");
		return false;

	} else if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -./#]{10,100}$/, $("#direccion"), $("#sdireccion"), "") == 0) {
		mensajes("error", 10000, "Verifica", "La dirección del Ente debe tener de 10 a 100 carácteres");
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
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`;
					return botones;
				}
			}],
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

	$("#nombre").prop("readOnly", false);
	$("#responsable").prop("readOnly", false);
	$("#telefono").prop("readOnly", false);
	$("#direccion").prop("readOnly", false);

	$('#enviar').prop('disabled', false);
}


function rellenar(pos, accion) {
	limpia();

	linea = $(pos).closest('tr');

	$("#idEnte").remove();
	$("#Fila1").prepend(`<div class="col-4" id="idEnte">
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


	if (accion == 0) {
		$("#modalTitleId").text("Modificar Ente")
		$("#enviar").text("Modificar");
	}
	else {
		$("#nombre").prop("readOnly", true);
		$("#responsable").prop("readOnly", true);
		$("#telefono").prop("readOnly", true);
		$("#direccion").prop("readOnly", true);
		$("#modalTitleId").text("Eliminar Ente")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}