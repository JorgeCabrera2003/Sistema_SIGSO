$(document).ready(function () {
	consultar();
	filtrarBien();
	cargarDependencia();
	registrarEntrada();
	capaValidar();

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;
		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion('Se registrará un nuevo Equipo', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('tipo_equipo', $("#tipo_equipo").val());
						datos.append('serial', $("#serial").val());
						datos.append('codigo_bien', $("#codigo_bien").val());
						datos.append('id_unidad', $("#id_unidad").val());
						enviaAjax(datos);
					} else {
						envio = false;
					}
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion('Se registrará un nuevo Equipo', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_equipo', $("#id_equipo").val());
						datos.append('tipo_equipo', $("#tipo_equipo").val());
						datos.append('serial', $("#serial").val());
						datos.append('codigo_bien', $("#codigo_bien").val());
						datos.append('id_unidad', $("#id_unidad").val());
						enviaAjax(datos);
					} else {
						envio = false;
					}
				}
				break;
			case "Eliminar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion('Se registrará un nuevo Equipo', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_equipo', $("#id_equipo").val());
						enviaAjax(datos);
					} else {
						envio = false;
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

	$("#btn-registrar").on("click", function () {
		limpia();
		$("#id_equipo").parent().parent().remove();
		$("#modalTitleId").text("Registrar Equipo");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	});

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});
});

function cargarDependencia() {
	var datos = new FormData();
	datos.append('cargar_dependencia', 'cargar_dependencia');
	enviaAjax(datos);
};

async function cargarUnidad(parametro = 0) {
	var datos = new FormData();
	datos.append('cargar_unidad', 'cargar_unidad');
	datos.append('id_dependencia', parametro);
	await enviaAjax(datos);
	return true;
};

function filtrarBien() {
	var datos = new FormData();
	datos.append('filtrar_bien', 'filtrar_bien')
	enviaAjax(datos);
};

async function enviaAjax(datos) {

	return await $.ajax({
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
				if (lee.resultado == "registrar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "consultar") {
					crearDataTable(lee.datos);

				} else if (lee.resultado == "filtrar_bien") {
					selectBien(lee.datos);

				} else if (lee.resultado == "consultar_dependencia") {
					selectDependencia(lee.datos);

				} else if (lee.resultado == "consultar_unidad") {
					selectUnidad(lee.datos);

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
				console.log("error", null, "Error en JSON Tipo: " + e.name + "\n" +
					"Mensaje: " + e.message + "\n" +
					"Posición: " + e.lineNumber);
			} console.log(lee);

		},
		error: function (request, status, err) {
			if (status == "timeout") {
				mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
			} else {
				mensajes("error", null, "Ocurrió un error", "ERROR: <br/>" + request + status + err);
			}
		},
		complete: function () {

		},
	});

}

function capaValidar() {
	$("#tipo_equipo").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#tipo_equipo").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $(this), $("#stipo_equipo"),
			"El tipo de equipo debe tener de 3 a 45 carácteres"
		);
	});

	$("#serial").on("keypress", function (e) {
		validarKeyPress(/^[0-9a-zA-ZáéíóúüñÑçÇ.-]*$/, e);
	});
	$("#serial").on("keyup", function () {
		validarKeyUp(
			/^[0-9a-zA-ZáéíóúüñÑçÇ.-]{3,45}$/, $(this), $("#sserial"),
			"El serial debe tener de 3 a 45 carácteres"
		);
	});

	$("#codigo_bien").on("change", function () {
		if ($(this).val() == "default") {
			estadoSelect(this, "scodigo_bien", "Debe seleccionar un código de bien", 0)

		} else {
			estadoSelect(this, "scodigo_bien", "", 1)
		}
	});

	$("#id_unidad").on("change", function () {
		if ($(this).val() == "default" || $(this).val() == "") {
			estadoSelect(this, "sid_unidad", "Debe seleccionar una unidad", 0);

		} else {
			estadoSelect(this, "sid_unidad", "", 1);
		}
	});

	$("#id_dependencia").on("change", function () {
		if ($(this).val() == "default") {
			estadoSelect(this, "sid_dependencia", "Debe seleccionar una dependencia", 0);
			estadoSelect("#id_unidad", "sid_unidad", "", 0);
			cargarUnidad(0);
		} else {
			estadoSelect(this, "sid_dependencia", "", 1);
			cargarUnidad($(this).val());
		}
	});

}

function validarenvio() {
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#tipo_equipo"), $("#stipo_equipo"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El tipo de equipo debe tener de 3 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[0-9a-zA-ZáéíóúüñÑçÇ.-]{3,45}$/, $("#serial"), $("#sserial"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El serial debe tener de 3 a 45 carácteres");
		return false;

	} else if ($("#codigo_bien").val() == "default") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un código de bien");
		return false;

	} else if ($("#id_dependencia").val() == "default") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un una dependencia");
		return false;

	} else if ($("#id_unidad").val() == "default") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar una unidad");
		return false;
	}
	return true;
}


function selectBien(arreglo) {
	$("#codigo_bien").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {

		$("#codigo_bien").append(
			new Option('Seleccione un Bien', 'default')
		);
		arreglo.forEach(item => {
			$("#codigo_bien").append(
				new Option(item.nombre_bien, item.codigo_bien)
			);
		});
	} else {
		$("#codigo_bien").append(
			new Option('No Hay Bienes', 'default')
		);
	}
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

async function selectUnidad(arreglo) {
	$("#id_unidad").removeClass("is-valid is-invalid");
	$("#id_unidad").val("");
	$("#id_unidad").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {

		$("#id_unidad").append(
			new Option('Seleccione una Unidad', 'default')
		);
		arreglo.forEach(item => {
			$("#id_unidad").append(
				new Option(item.nombre_unidad, item.id_unidad)
			);
		});
	} else {
		$("#id_unidad").append(
			new Option('No Hay unidad', 'default')
		);
		estadoSelect("#id_unidad", "#sid_unidad", "", 0);
	}
	return true;
}

function vistaPermiso(permisos = null) {

	if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
		$('.modificar').remove();
		$('.eliminar').remove();
		$('.restaurar').remove();
	} else {
		if (permisos['equipo']['modificar']['estado'] == '0') {
			$('.modificar').remove();
		}
		if (permisos['equipo']['eliminar']['estado'] == '0') {
			$('.eliminar').remove();
		}
		if (permisos['equipo']['restaurar']['estado'] == '0') {
			$('.restaurar').remove();
		}
	}
};

function crearDataTable(arreglo) {
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}

	$('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id_equipo' },
			{ data: 'tipo_equipo' },
			{ data: 'serial' },
			{ data: 'codigo_bien' },
			{ data: 'dependencia' },
			{ data: 'nombre_unidad' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger"><i class="fa-solid fa-trash eliminar"></i></button>`;
					return botones;
				}
			}],
		language: {
			url: idiomaTabla,
		}
	});
}

function limpia() {
	filtrarBien();
	$("#tipo_equipo").removeClass("is-valid is-invalid");
	$("#tipo_equipo").val("");

	$("#serial").removeClass("is-valid is-invalid");
	$("#serial").val("");

	$("#codigo_bien").removeClass("is-valid is-invalid");

	$("#id_dependencia").removeClass("is-valid is-invalid");
	$("#id_dependencia").val("default");

	$("#id_unidad").removeClass("is-valid is-invalid");
	$("#id_unidad").empty();
	$("#id_unidad").append(
		new Option('Seleccione una Dependencia primero', 'default')
	);

	$('#enviar').prop('disabled', false);
}

async function rellenar(pos, accion) {
	var espera;
	linea = $(pos).closest('tr');

	if (!$("#id_equipo").length) {
		$("#Fila1").prepend(`<div class="col-4">
            <div class="form-floating mb-3 mt-4">
                <input placeholder="" class="form-control" name="id_equipo" type="text" id="id_equipo" readonly>
                <span id="sid_equipo"></span>
                <label for="id_equipo" class="form-label">ID Equipo</label>
            </div>`);
	}
	$("#codigo_bien").find('option[value="default"]').remove()
	$("#id_equipo").val($(linea).find("td:eq(0)").text());
	$("#tipo_equipo").val($(linea).find("td:eq(1)").text());
	$("#serial").val($(linea).find("td:eq(2)").text());
	$("#codigo_bien").append(new Option($(linea).find("td:eq(3)").text() + ' (Código Actual)', $(linea).find("td:eq(3)").text()));
	buscarSelect("#id_dependencia", $(linea).find("td:eq(4)").text(), "text");

	espera = await cargarUnidad($("#id_dependencia").val());

	if (espera) {
		buscarSelect("#id_unidad", $(linea).find("td:eq(5)").text(), "text");
	}

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Equipo")
		$("#enviar").text("Modificar");
	}
	else {
		$("#modalTitleId").text("Eliminar Equipo")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function consultarEliminadas() {
	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');

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
				if (lee.resultado == "consultar_eliminadas") {
					if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
						$('#tablaEliminadas').DataTable().destroy();
					}

					$('#tablaEliminadas').DataTable({
						data: lee.datos,
						columns: [
							{ data: 'id_equipo' },
							{ data: 'tipo_equipo' },
							{ data: 'serial' },
							{ data: 'codigo_bien' },
							{ data: 'nombre_unidad' },

							{
								data: null,
								render: function () {
									return `<button onclick="restaurarEquipo(this)" class="btn btn-success restaurar">
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
			} catch (e) {
				console.error("Error procesando datos:", e);
			}
		},
		error: function (request, status, err) {
			mensajes("error", null, "Error al cargar equipos eliminados", "Intente nuevamente");
		}
	});
}

function restaurarEquipo(boton) {
	var linea = $(boton).closest('tr');
	var id = $(linea).find('td:eq(0)').text();

	Swal.fire({
		title: '¿Restaurar Equipo?',
		text: "¿Está seguro que desea restaurar este equipo?",
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
			datos.append('id_equipo', id);

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
							mensajes("success", null, "Equipo restaurado", lee.mensaje);
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
					mensajes("error", null, "Error", "No se pudo restaurar el equipo");
				}
			});
		}
	});
}