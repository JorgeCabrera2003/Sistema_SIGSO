$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();

	$("#enviar").on("click", function () {
		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					var datos = new FormData();
					datos.append('registrar', 'registrar');
					datos.append('id_piso', $("#id_piso").val());
					datos.append('nombre', $("#nombre").val());
					enviaAjax(datos);
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					var datos = new FormData();
					datos.append('modificar', 'modificar');
					datos.append('id_oficina', $("#id_oficina").val());
					datos.append('id_piso', $("#id_piso").val());
					datos.append('nombre', $("#nombre").val());
					enviaAjax(datos);
				}
				break;
			case "Eliminar":
				if (validarenvio()) {
					var datos = new FormData();
					datos.append('eliminar', 'eliminar');
					datos.append('id_oficina', $("#id_oficina").val());
					enviaAjax(datos);
				}
				break;

			default:
				mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());;
		}
		$('#enviar').prop('disabled', true);
	});

	$("#btn-registrar").on("click", function () {
		limpia();
		$("#id_oficina").parent().parent().remove();
		$("#nombre").parent().parent().show();
		$("#id_piso").parent().parent().show();
		$("#modalTitleId").text("Registrar Oficina");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	});
	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
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
				console.log(respuesta);
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
			"El nombre de la oficina debe tener de 3 a 45 carácteres"
		);
	});

	$("#id_piso").on("change", function () {
		if ($(this).val() == "") {
			$(this).addClass("is-invalid");
			$(this).removeClass("is-valid");
			$("#sid_piso").addClass("invalid-feedback");
			$("#sid_piso").removeClass("valid-feedback");
			$("#sid_piso").text("Debe seleccionar un piso");
		} else {
			$(this).addClass("is-valid");
			$(this).removeClass("is-invalid");
			$("#sid_piso").addClass("valid-feedback");
			$("#sid_piso").removeClass("invalid-feedback");
			$("#sid_piso").text("");
		}
	});
}

function validarenvio() {
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre de la oficina debe tener de 3 a 45 carácteres");
		return false;

	} else if ($("#id_piso").val() == "") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un piso");
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
		columns: [
			{ data: 'id_oficina' },
			{ data: 'nombre_oficina' },
			{
				// Columna combinada que mostrará nombre_oficina y nro_piso
				data: null,
				render: function (row) {
					return `${row.tipo_piso} ${row.nro_piso}`;
				}
			},
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

	$("#id_piso").removeClass("is-valid is-invalid");
	$("#id_piso").val("");

	$('#enviar').prop('disabled', false);
}

function rellenar(pos, accion) {
	linea = $(pos).closest('tr');

	if (!$("#id_oficina").length) {
		$("#Fila1").prepend(`<div class="col-4">
            <div class="form-floating mb-3 mt-4">
                <input placeholder="" class="form-control" name="id_oficina" type="text" id="id_oficina" readonly>
                <span id="sid_oficina"></span>
                <label for="id_oficina" class="form-label">ID Oficina</label>
            </div>`);
	}

	$("#id_oficina").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());
	buscarSelect("#id_piso", $(linea).find("td:eq(2)").text(), "text")

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Oficina")
		$("#enviar").text("Modificar");
	}
	else {
		$("#modalTitleId").text("Eliminar Oficina")
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
							{ data: 'id_oficina' },
							{ data: 'nombre_oficina' },
							{ data: 'nro_piso' },
							{
								data: null,
								render: function () {
									return `<button onclick="restaurarOficina(this)" class="btn btn-success">
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
			mensajes("error", null, "Error al cargar oficinas eliminadas", "Intente nuevamente");
		}
	});
}


function restaurarOficina(boton) {
	var linea = $(boton).closest('tr');
	var id = $(linea).find('td:eq(0)').text();

	Swal.fire({
		title: '¿Restaurar Oficina?',
		text: "¿Está seguro que desea restaurar esta oficina?",
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
			datos.append('id_oficina', id);

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
							mensajes("success", null, "Oficina restaurada", lee.mensaje);
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
					mensajes("error", null, "Error", "No se pudo restaurar la oficina");
				}
			});
		}
	});
}