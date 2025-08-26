$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarDependencia();

	$("#enviar").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará una Unidad", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('nombre', $("#nombre").val());
						datos.append('id_dependencia', $("#id_dependencia").val());
						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará una Unidad", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_unidad', $("#id_unidad").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('id_dependencia', $("#id_dependencia").val());
						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{1,11}$/, $("#id_unidad"), $("#sid_unidad"), "") == 1) {
					confirmacion = await confirmarAccion("Se eliminará una Unidad", "¿Está seguro de realizar la acción?", "warning");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_unidad', $("#id_unidad").val());
						enviaAjax(datos);
						envio = true;
					}
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
		$("#idUnidad").remove();
		$("#modalTitleId").text("Registrar Unidad");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar
});

function cargarDependencia() {
	var datos = new FormData();
	datos.append('cargar_dependencia', 'cargar_dependencia');
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
		beforeSend: function () {},
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

				} else if (lee.resultado == "consultar_dependencia") {
					selectDependencia(lee.datos);

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
		complete: function () {},
	});
}


function capaValidar() {
	// --- VALIDAR SELECT ---
	$('#id_dependencia').on('change', function () {
		if ($(this).val() === 'default') {
			estadoSelect(this, '#sid_dependencia',
				"Debe seleccionar una dependencia válida", 0);
			$("#nombre").prop("disabled", true); // sigue bloqueado
		} else {
			estadoSelect(this, '#sid_dependencia', "", 1);
			$("#nombre").prop("disabled", false); // desbloquea nombre
		}
	});

	// --- VALIDAR NOMBRE ---
	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
	});

	$("#nombre").on("keyup", function () {
		let valido = validarKeyUp(
			/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{4,45}$/, $(this), $("#snombre"),
			"El nombre debe tener entre 4 y 45 caracteres. " +
			"No se permiten símbolos extraños."
		);

		if (valido) {
			$("#enviar").prop("disabled", false); // habilita enviar
		} else {
			$("#enviar").prop("disabled", true); // bloquea enviar
		}
	});
}

function vistaPermiso(permisos = null) {

	if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {

		$('.modificar').remove();
		$('.eliminar').remove();

	} else {

		if (permisos['unidad']['modificar']['estado'] == '0') {
			$('.modificar').remove();
		}

		if (permisos['unidad']['eliminar']['estado'] == '0') {
			$('.eliminar').remove();
		}
	}
};

function validarKeyUp(expresion, input, span, mensaje) {
	let valor = $(input).val();
	let errores = [];

	if (valor.length < 4) {
		errores.push("Debe tener al menos 4 caracteres.");
	}
	if (valor.length > 45) {
		errores.push("No puede superar los 45 caracteres.");
	}
	if (!expresion.test(valor)) {
		errores.push("Solo se permiten letras, números y guiones.");
	}

	if (errores.length > 0) {
		$(input).removeClass("is-valid").addClass("is-invalid");
		$(span).html(errores.join("<br>"));
		return 0;
	} else {
		$(input).removeClass("is-invalid").addClass("is-valid");
		$(span).html("");
		return 1;
	}
}


function validarenvio() {

	if (validarKeyUp(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre de la Unidad debe tener de 4 a 45 carácteres");
		return false;

	} else if ($('#id_dependencia').val() === 'default') {
		mensajes("error", 10000, "Verifica", "Seleccione una dependencia");
		return false;
	}
	return true;
}

function selectDependencia(arreglo) {
	$("#id_dependencia").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {

		$("#id_dependencia").append(
			new Option('Seleccione una Dependencia', 'default')
		);
		arreglo.forEach(item => {
			$("#id_dependencia").append(
				new Option(item.ente + " - " + item.nombre, item.id)
			);
		});
	} else {
		$("#id_dependencia").append(
			new Option('No Hay Dependencia', 'default')
		);
	}
}

function crearDataTable(arreglo) {
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}

	$('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{data: 'id_unidad'},
			{data: 'dependencia'},
			{data: 'nombre_unidad'},
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar">
					<i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar" title="Eliminar">
					<i class="fa-solid fa-trash"></i></button>`;
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
	$("#nombre").removeClass("is-valid is-invalid").val("").prop("disabled", true);
	$("#id_dependencia").removeClass("is-valid is-invalid").val("default").prop("disabled", false);
	$("#snombre, #sid_dependencia").html("");
	$('#enviar').prop('disabled', true);
}


function rellenar(pos, accion) {
	limpia();
	linea = $(pos).closest('tr');

	$("#idUnidad").remove();
	$("#Fila1").prepend(`<div class="col-4" id="idUnidad">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_unidad" type="text" id="id_unidad" readOnly>
              <span id="sid_unidad"></span>
              <label for="id_unidad" class="form-label">ID de la Unidad</label>
            </div>`);

	$("#id_unidad").val($(linea).find("td:eq(0)").text());
	buscarSelect("#id_dependencia", $(linea).find("td:eq(1)").text(), "text");
	$("#nombre").val($(linea).find("td:eq(2)").text());

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Unidad");
		$("#enviar").text("Modificar");
	}
	else {
		$("#nombre").prop('readOnly', true);
		$("#id_dependencia").prop('disabled', true);
		$("#modalTitleId").text("Eliminar Unidad");
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}