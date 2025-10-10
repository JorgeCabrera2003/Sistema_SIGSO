$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	consultarTipoServicio();

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;

		switch ($("#senviar").text()) {

			case "Registrar":
				if (validarenvio()) {
					var datos = new FormData();
					datos.append('registrar', 'registrar');
					datos.append('nombre', $("#nombre").val());
					datos.append('id_tipoServicio', $("#tipo_servicio").val());
					enviaAjax(datos);
					envio = true;
					confirmacion = true;
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará un Categoría", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_categoria', $("#id_categoria").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('id_tipoServicio', $("#tipo_servicio").val());
						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/, $("#id_categoria"), $("#sid_categoria"), "") == 1) {
					confirmacion = await confirmarAccion("Se eliminará un Categoría", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_categoria', $("#id_categoria").val());
						enviaAjax(datos);
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
		$("#id_categoria").parent().parent().remove();
		$("#nombre").parent().parent().show();
		$("#modalTitleId").text("Registrar Categoría");
		$("#senviar").text("Registrar");
		$("#modal1").modal("show");
	});

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});
});

function consultarTipoServicio() {
	var datos = new FormData();
	datos.append('consultar_tipoServicio', 'consultar_tipoServicio');

	enviaAjax(datos);
}

async function enviaAjax(datos) {
	return $.ajax({
		async: true,
		url: "",
		type: "POST",
		contentType: false,
		data: datos,
		processData: false,
		cache: false,
		beforeSend: function () {
			$("#spinner").addClass("spinner-border spinner-border-sm");
		},
		timeout: 10000,
		success: function (respuesta) {
			try {
				$("#spinner").removeClass("spinner-border spinner-border-sm");
				var lee = JSON.parse(respuesta);
				if (lee.resultado == "registrar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "consultar") {
					crearDataTable(lee.datos);

				} else if (lee.resultado == "consultar_eliminadas") {
					iniciarTablaEliminadas(lee.datos);

				} else if (lee.resultado == "modificar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "eliminar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "consultar_tipoServicio") {
					selectTipoServicio(lee.datos);

				} else if (lee.resultado == "entrada") {

				} else if (lee.resultado == "error") {
					console.log(lee.mensaje)
					mensajes("error", null, "Ups, a ocurrido un error...", null);
				}
			} catch (e) {
				mensajes("error", null, "Ups, a ocurrido un error...", null);
				console.log("error", null, "Error en JSON Tipo: " + e.name + "\n" +
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
			"El nombre de la categoria debe tener de 3 a 45 carácteres"
		);
	});

	$("#tipo_servicio").on("change", function () {
		if ($(this).val() == "default") {
			estadoSelect(this, "#stipo_servicio", "Debe seleccionar una opción", 0);
		} else {
			estadoSelect(this, "#stipo_servicio", "", 1);
		}
	});

}

function validarenvio() {
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre de la categoria debe tener de 3 a 45 carácteres");
		return false;
	} else if ($("#rol").val() == "default") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar una opción");
		return false;
	}
	return true;
}


async function crearDataTable(arreglo) {

	json = await ConsultarPermisos();
	json = JSON.parse(json);
	console.log(json.permisos.categoria);

	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}
	console.log(arreglo);
	$('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id_categoria' },
			{ data: 'nombre_categoria' },
			{
				data: 'servicio',
				render: function (data) {
					var texto;
					if (data == null) {
						texto = "Ninguno";
					} else {
						texto = data;
					};
					return texto;
				}
			},
			{
				data: null, render: function () {
					const html = [];

					if (Array.isArray(json) || Object.keys(json).length == 0 || json == null) {
					} else {
						if (json.permisos.categoria.modificar.estado == '1') {
							html.push(`<button onclick="rellenar(this, 0)" title="Modificar" class="btn btn-update">
                    					<i class="fa-solid fa-pen-to-square"></i>
                						</button>`);
						}

						if (json.permisos.categoria.eliminar.estado == '1') {
							html.push(`<button onclick="rellenar(this, 1)" title="Eliminar" class="btn btn-danger">
											<i class="fa-solid fa-trash"></i>
										</button>`);
						}
					}
					return html.join();
				}
			}],
		language: {
			url: idiomaTabla,
		}
	});
}

async function iniciarTablaEliminadas(arreglo) {
	json = await ConsultarPermisos();
	json = JSON.parse(json);
	console.log(json.permisos.categoria);

	if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
		$('#tablaEliminadas').DataTable().destroy();
	}

	$('#tablaEliminadas').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id_categoria' },
			{ data: 'nombre_categoria' },
			{
				data: 'servicio',
				render: function (data) {
					var texto;
					if (data == null) {
						texto = "Ninguno";
					} else {
						texto = data;
					};
					return texto;
				}
			},
			{
				data: null,
				render: function () {
					const html = [];

					if (Array.isArray(json) || Object.keys(json).length == 0 || json == null) {

					} else {
						if (json.permisos.categoria.reactivar.estado == '1') {
							html.push(`<button onclick="restaurarTipoBien(this)" class="btn btn-success restaurar">
                            <i class="fa-solid fa-recycle"></i>
                            </button>`);
						}
					}
					return html.join();
				}
			}
		],
		language: {
			url: idiomaTabla,
		}
	});
}

function selectTipoServicio(arreglo) {
	$("#tipo_servicio").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#tipo_servicio").attr('disabled', false);
		$("#tipo_servicio").append(
			new Option('Seleccione un Tipo de Servicio', 'default')
		);
		$("#tipo_servicio").append(
			new Option('No asignar Servicio', 'none')
		);
		arreglo.forEach(item => {
			$("#tipo_servicio").append(
				new Option(item.nombre_tipo_servicio, item.id_tipo_servicio)
			);
		});
	} else {
		$("#tipo_servicio").append(
			new Option('No hay Áreas Disponibles', 'none')
		);
		$("#tipo_servicio").attr('disabled', true);
	}
}

function limpia() {
	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");
	$("#nombre").prop('readOnly', false);
	$('#enviar').prop('disabled', false);
}

function rellenar(pos, accion) {
	linea = $(pos).closest('tr');

	if (!$("#id_categoria").length) {
		$("#Fila1").prepend(`<div class="col-4">
            <div class="form-floating mb-3 mt-4">
                <input placeholder="" class="form-control" name="id_categoria" type="text" id="id_categoria" readonly>
                <span id="sid_categoria"></span>
                <label for="id_categoria" class="form-label">ID Categoría</label>
            </div>`);
	}

	$("#id_categoria").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());
	buscarSelect("#tipo_servicio", "none", "value");
	buscarSelect("#tipo_servicio", $(linea).find("td:eq(2)").text(), "text");

	$("#id_categoria").prop('readOnly', true);
	if (accion == 0) {
		$("#modalTitleId").text("Modificar Categoría")
		$("#senviar").text("Modificar");
	}
	else {
		$("#nombre").prop('readOnly', true);
		$("#modalTitleId").text("Eliminar Categoría")
		$("#senviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function consultarEliminadas() {
	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');
	enviaAjax(datos);
}

function restaurarTipoBien(boton) {
	var linea = $(boton).closest('tr');
	var id = $(linea).find('td:eq(0)').text();

	Swal.fire({
		title: '¿Reactivar Categoría?',
		text: "¿Está seguro que desea reactivar esta categoría?",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, reactivar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			var datos = new FormData();
			datos.append('reactivar', 'reactivar');
			datos.append('id_categoria', id);

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
							mensajes("success", null, "Tipo de bien restaurado", lee.mensaje);
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
					mensajes("error", null, "Error", "No se pudo restaurar el categoria");
				}
			});
		}
	});
}