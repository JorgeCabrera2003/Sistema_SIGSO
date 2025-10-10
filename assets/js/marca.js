// marca.js - Versión Mejorada

// Elementos del formulario para Marca
const elementosMarca = {
	nombre: $('#nombre'),
	id_marca: $('#id_marca')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoMarca(formularioValido) {
	const accion = $("#enviar").text();

	if (accion === "Eliminar") {
		// Para eliminar solo validamos el ID
		const idValido = $("#id_marca").length && $("#id_marca").hasClass("is-valid");
		$('#enviar').prop('disabled', !idValido);
	} else {
		// Para registrar y modificar validamos todos los campos requeridos
		$('#enviar').prop('disabled', !formularioValido);
	}
}

$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();

	// Inicializar sistema de validación con callback
	SistemaValidacion.inicializar(elementosMarca, manejarCambioEstadoMarca);

	// Validar estado inicial del formulario
	manejarCambioEstadoMarca(false);

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {
			case "Registrar":
				if (SistemaValidacion.validarFormulario(elementosMarca)) {
					confirmacion = await confirmarAccion("Se registrará una Marca", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						enviarFormulario('registrar');
						envio = true;
					}
				} else {
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
				}
				break;

			case "Modificar":
				if (SistemaValidacion.validarFormulario(elementosMarca)) {
					confirmacion = await confirmarAccion("Se modificará una Marca", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						enviarFormulario('modificar');
						envio = true;
					}
				} else {
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
				}
				break;

			case "Eliminar":
				// Validar solo el ID para eliminar
				if ($("#id_marca").length && $("#id_marca").val().trim() !== "") {
					confirmacion = await confirmarAccion("Se eliminará una Marca", "¿Está seguro de realizar la acción?", "warning");
					if (confirmacion) {
						enviarFormulario('eliminar');
						envio = true;
					}
				} else {
					mensajes("error", 10000, "Error de Validación", "El ID de la marca no es válido.");
				}
				break;

			default:
				mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());
		}

		if (envio) {
			$('#enviar').prop('disabled', true);
		}

		if (!confirmacion) {
			$('#enviar').prop('disabled', false);
		}
	});

	$("#btn-registrar").on("click", function () {
		limpia();
		$("#idMarca").remove();
		$("#modalTitleId").text("Registrar Marca");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");

		// Deshabilitar botón inicialmente
		$('#enviar').prop('disabled', true);
	});

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});

	// Forzar validación inicial cuando se abre el modal
	$('#modal1').on('shown.bs.modal', function () {
		setTimeout(() => {
			SistemaValidacion.validarFormulario(elementosMarca);
		}, 100);
	});
});

function consultarEliminadas() {
	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');
	enviaAjax(datos);
}

function enviarFormulario(accion) {
	const formData = new FormData();
	formData.append(accion, accion);
	formData.append('nombre', $("#nombre").val());

	// Solo agregar ID para modificar y eliminar
	if (accion !== 'registrar' && $("#id_marca").length) {
		formData.append('id_marca', $("#id_marca").val());
	}

	$.ajax({
		async: true,
		url: "",
		type: "POST",
		contentType: false,
		data: formData,
		processData: false,
		cache: false,
		beforeSend: function () {
			$('#enviar').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...');
		},
		success: function (respuesta) {
			try {
				var lee = JSON.parse(respuesta);
				if (lee.resultado === accion) {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();
				} else if (lee.resultado === "error") {
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
		complete: function () {
			// Restaurar el texto del botón según la acción
			let buttonText = 'Registrar';
			if (accion === 'modificar') {
				buttonText = 'Modificar';
			} else if (accion === 'eliminar') {
				buttonText = 'Eliminar';
			}
			$('#enviar').prop('disabled', false).text(buttonText);
		},
	});
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
		timeout: 10000,
		success: function (respuesta) {
			try {
				var lee = JSON.parse(respuesta);
				console.log(lee);

				switch (lee.resultado) {
					case "registrar":
					case "modificar":
					case "eliminar":
						$("#modal1").modal("hide");
						mensajes("success", 10000, lee.mensaje, null);
						consultar();
						break;

					case "consultar":
						crearDataTable(lee.datos);
						break;

					case "consultar_eliminados":
						iniciarTablaEliminadas(lee.datos);
						break;

					case "entrada":
						// No action needed
						break;

					case "permisos_modulo":
						vistaPermiso(lee.permisos);
						break;

					case "error":
						mensajes("error", null, lee.mensaje, null);
						break;
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
	// Validación con formato en tiempo real para nombre
	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
	});

	// Aplicar capitalización en tiempo real
	$("#nombre").on("input", function () {
		const valor = $(this).val();
		if (valor.length === 1) {
			$(this).val(valor.toUpperCase());
		}
	});

	// Aplicar capitalización completa al perder el foco
	$("#nombre").on("blur", function () {
		SistemaValidacion.autoCapitalizar($(this));
	});
}

function vistaPermiso(permisos = null) {
	if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
		$('.modificar').remove();
		$('.eliminar').remove();
		$('.restaurar').remove();
	} else {
		if (permisos['marca']['modificar']['estado'] == '0') {
			$('.modificar').remove();
		}

		if (permisos['marca']['eliminar']['estado'] == '0') {
			$('.eliminar').remove();
		}

		if (permisos['marca']['restaurar'] && permisos['marca']['restaurar']['estado'] == '0') {
			$('.restaurar').remove();
		}
	}
}

function crearDataTable(arreglo) {
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}

	$('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{
				data: 'id_marca',
				visible: false // Ocultar ID ya que es interno
			},
			{
				data: 'nombre_marca',
				render: function (data) {
					return capitalizarTexto(data || '');
				}
			},
			{
				data: null,
				render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button onclick="rellenar(this, 1)" class="btn btn-danger eliminar" title="Eliminar">
                        <i class="fa-solid fa-trash"></i>
                    </button>`;
					return botones;
				},
				orderable: false
			}
		],
		order: [[1, 'asc']], // Ordenar por nombre
		language: {
			url: idiomaTabla,
		},
		responsive: true,
		pageLength: 10
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
			{
				data: 'id_marca',
				visible: false
			},
			{
				data: 'nombre_marca',
				render: function (data) {
					return capitalizarTexto(data || '');
				}
			},
			{
				data: null,
				render: function () {
					return `<button onclick="reactivarMarca(this)" class="btn btn-success reactivar" title="Reactivar">
                        <i class="fa-solid fa-recycle"></i>
                    </button>`;
				},
				orderable: false
			}
		],
		order: [[1, 'asc']],
		language: {
			url: idiomaTabla,
		},
		responsive: true,
		pageLength: 10
	});

	ConsultarPermisos();
}
async function reactivarMarca(boton) {
	const confirmacion = await confirmarAccion("¿Reactivar Marca?", "¿Está seguro que desea reactivar esta marca?", "question");

	if (confirmacion) {
		const linea = $(boton).closest('tr');
		const tabla = $('#tablaEliminadas').DataTable();
		const datosFila = tabla.row(linea).data();
		const id = datosFila.id_marca;

		var datos = new FormData();
		datos.append('reactivar', 'reactivar'); // Cambiar de 'restaurar' a 'reactivar'
		datos.append('id_marca', id);

		$.ajax({
			url: "",
			type: "POST",
			data: datos,
			processData: false,
			contentType: false,
			beforeSend: function () {
				$(boton).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
			},
			success: function (respuesta) {
				try {
					var lee = JSON.parse(respuesta);
					if (lee.estado == 1) {
						mensajes("success", null, "Marca reactivada", lee.mensaje);
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
				mensajes("error", null, "Error", "No se pudo reactivar la marca");
			},
			complete: function () {
				$(boton).prop('disabled', false).html('<i class="fa-solid fa-recycle"></i>');
			}
		});
	}
}

function limpia() {
	SistemaValidacion.limpiarValidacion(elementosMarca);

	$("#nombre").val("").prop("readOnly", false);
	$("#id_marca").val("").prop("readOnly", false);

	// Deshabilitar el botón al limpiar
	$('#enviar').prop('disabled', true);
}

function rellenar(pos, accion) {
	limpia();

	const linea = $(pos).closest('tr');
	const tabla = $('#tabla1').DataTable();
	const datosFila = tabla.row(linea).data();

	// Crear campo ID si no existe (IMPORTANTE para eliminar)
	if (!$("#idMarca").length) {
		$("#Fila1").prepend(`<div class="col-4" id="idMarca">
            <div class="form-floating mb-3 mt-4">
                <input placeholder="" class="form-control" name="id_marca" type="text" id="id_marca" readOnly>
                <span id="sid_marca"></span>
                <label for="id_marca" class="form-label">ID de la Marca</label>
            </div>
        </div>`);

		// Actualizar elementosMarca para incluir el nuevo campo
		elementosMarca.id_marca = $('#id_marca');
	}

	// Usar los datos directamente de DataTable
	$("#id_marca").val(datosFila.id_marca);
	$("#nombre").val(capitalizarTexto(datosFila.nombre_marca));

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Marca");
		$("#enviar").text("Modificar");
	} else {
		$("#nombre").prop('readOnly', true);
		$("#modalTitleId").text("Eliminar Marca");
		$("#enviar").text("Eliminar");
	}

	// Habilitar el botón inmediatamente para Modificar/Eliminar
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function ConsultarPermisos() {
	var datos = new FormData();
	datos.append('permisos', 'permisos');
	$.ajax({
		async: true,
		url: "",
		type: "POST",
		contentType: false,
		data: datos,
		processData: false,
		cache: false,
		success: function (respuesta) {
			try {
				var lee = JSON.parse(respuesta);
				if (lee.resultado == "permisos_modulo") {
					vistaPermiso(lee.permisos);
				}
			} catch (e) {
				console.error("Error al cargar permisos:", e);
			}
		}
	});
}