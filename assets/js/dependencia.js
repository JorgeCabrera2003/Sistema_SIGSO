$(document).ready(function () {
	consultar();
	consultarEnte();
	registrarEntrada();
	capaValidar();

	$("#enviar").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará una Dependencia", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('nombre', $("#nombre").val());
						datos.append('ente', $("#ente").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará un Dependencia", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_dependencia', $("#id_dependencia").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('ente', $("#ente").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{1,11}$/, $("#id_dependencia"), $("#sid_dependencia"), "") === 1) {
					confirmacion = await confirmarAccion("Se eliminará un Dependencia", "¿Está seguro de realizar la acción?", "warning");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_dependencia', $("#id_dependencia").val());
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
		$("#idDependencia").remove();
		$("#modalTitleId").text("Registrar Dependencia");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar
});

function consultarEnte() {
	var datos = new FormData();
	datos.append('cargar_ente', 'cargar_ente');
	enviaAjax(datos);
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

				} else if (lee.resultado == "cargar_ente") {
					selectEnte(lee.datos);

				} else if (lee.resultado == "entrada") {

				} else if (lee.resultado == "error") {
					$("#modal1").modal("hide");
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
			/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{4,45}$/, $(this), $("#snombre"),
			"El nombre del Dependencia debe tener de 4 a 45 carácteres"
		);
	});

	$('#ente').on('change', function () {

		if ($(this).val() === 'default') {

			estadoSelect(this, '#sente', "Seleccione un Ente", 0);
		} else {
			estadoSelect(this, '#sente', "", 1);
		}
	});
}

function validarenvio() {

	if (validarKeyUp(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre de la dependencia debe tener de 4 a 45 carácteres");
		return false;

	} else if (($('#ente').val() === 'default')) {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un Ente");
		return false;

	}
	return true;
}

function selectEnte(arreglo) {
	$("#ente").empty();

	$("#ente").append(
		new Option('Seleccione un Ente', 'default')
	);
	arreglo.forEach(item => {
		$("#ente").append(
			new Option(item.nombre, item.id)
		);
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
			{ data: 'id' },
			{ data: 'nombre' },
			{ data: 'ente' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>`;
					return botones;
				}
			}],
		language: {
			url: idiomaTabla,
		}
	});

}


function limpia() {
	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");

	$("#ente").removeClass("is-valid is-invalid");
	$("#sente").val("");
	$("#sente").text("");

	$("#nombre").prop("readOnly", false);
	$("#ente option:first-child").prop('selected', true);
	$('#ente').prop('disabled', false);

	$('#enviar').prop('disabled', false);
}


function rellenar(pos, accion) {
	limpia();

	linea = $(pos).closest('tr');

	$("#idDependencia").remove();
	$("#Fila1").prepend(`<div class="col-4" id="idDependencia">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_dependencia" type="text" id="id_dependencia" readOnly>
              <span id="sid_dependencia"></span>
              <label for="id_dependencia" class="form-label">ID de la Dependencia</label>
            </div>`);


	$("#id_dependencia").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());
	buscarSelect("#ente", $(linea).find("td:eq(2)").text(), "text");


	if (accion == 0) {
		$("#modalTitleId").text("Modificar Dependencia")
		$("#enviar").text("Modificar");
	}
	else {
		$("#id_dependencia").prop("readOnly", true);
		$("#nombre").prop("readOnly", true);
		$('#ente').prop('disabled', true);
		$("#modalTitleId").text("Eliminar Dependencia")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}