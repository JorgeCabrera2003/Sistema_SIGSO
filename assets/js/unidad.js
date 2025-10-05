// Elementos del formulario para Unidad
const elementosUnidad = {
  nombre: $('#nombre'),
  id_dependencia: $('#id_dependencia'),
  id_unidad: $('#id_unidad')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoUnidad(formularioValido) {
  const accion = $("#enviar").text();
  
  if (accion === "Eliminar") {
    // Para eliminar solo validamos el ID
    const idValido = $("#id_unidad").length && $("#id_unidad").hasClass("is-valid");
    $('#enviar').prop('disabled', !idValido);
  } else {
    // Para registrar y modificar validamos todos los campos
    $('#enviar').prop('disabled', !formularioValido);
  }
}

$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarDependencia();

	// Inicializar sistema de validación con callback
	SistemaValidacion.inicializar(elementosUnidad, manejarCambioEstadoUnidad);
	
	// Validar estado inicial del formulario
	manejarCambioEstadoUnidad(false);

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
				} else {
					envio = false;
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
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
				} else {
					envio = false;
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
				}
				break;

			case "Eliminar":
				// Validar solo el ID para eliminar
				if ($("#id_unidad").length && SistemaValidacion.validarCampo.call(document.getElementById('id_unidad'))) {
					confirmacion = await confirmarAccion("Se eliminará una Unidad", "¿Está seguro de realizar la acción?", "warning");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_unidad', $("#id_unidad").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
					mensajes("error", 10000, "Error de Validación", "El ID de la unidad no es válido.");
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
		$("#idUnidad").remove();
		$("#modalTitleId").text("Registrar Unidad");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
		// El botón se habilita automáticamente mediante el callback cuando los campos sean válidos
	});

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});

	// Forzar validación inicial cuando se abre el modal
	$('#modal1').on('shown.bs.modal', function () {
		setTimeout(() => {
			SistemaValidacion.validarFormulario(elementosUnidad);
		}, 100);
	});
});

function consultarEliminadas() {
	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');
	enviaAjax(datos);
}

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
		beforeSend: function () { },
		timeout: 10000,
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
	// Validación con formato en tiempo real para nombre
	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ -.\b]*$/, e);
	});

	// Aplicar capitalización en tiempo real
	$("#nombre").on("input", function() {
		const valor = $(this).val();
		if (valor.length === 1) {
			$(this).val(valor.toUpperCase());
		}
	});

	// Habilitar/deshabilitar nombre según dependencia seleccionada
	$('#id_dependencia').on('change', function () {
		if ($(this).val() === 'default') {
			$("#nombre").prop("disabled", true);
		} else {
			$("#nombre").prop("disabled", false);
		}
		// Validar automáticamente después de cambiar
		setTimeout(() => SistemaValidacion.validarCampo.call(this), 100);
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
}

function validarenvio() {
	return SistemaValidacion.validarFormulario(elementosUnidad);
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
			{ data: 'id_unidad', visible: false },
			{ data: 'dependencia' },
			{ data: 'nombre_unidad' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar">
					<i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar" title="Eliminar">
					<i class="fa-solid fa-trash"></i></button>`;
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
			{ data: 'id_unidad', visible: false },
			{ data: 'dependencia' },
			{ data: 'nombre_unidad' },
			{
				data: null,
				render: function () {
					return `<button onclick="reactivarUnidad(this)" class="btn btn-success reactivar">
                            <i class="fa-solid fa-recycle"></i>
                            </button>`;
				}
			}
		],
		language: {
			url: idiomaTabla,
		}
	});
	ConsultarPermisos();
}

function limpia() {
	SistemaValidacion.limpiarValidacion(elementosUnidad);
	
	$("#nombre").val("").prop("disabled", true);
	$("#id_dependencia").val("default").prop("disabled", false);
	$("#nombre").prop("readOnly", false);
	$("#id_dependencia").prop("disabled", false);

	// Deshabilitar el botón al limpiar (se habilitará automáticamente cuando los campos sean válidos)
	$('#enviar').prop('disabled', true);
}

function rellenar(pos, accion) {
	limpia();
	const linea = $(pos).closest('tr');
	const tabla = $('#tabla1').DataTable();
	const datosFila = tabla.row(linea).data();

	$("#idUnidad").remove();
	$("#Fila1").prepend(`<div class="col-4" id="idUnidad">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_unidad" type="text" id="id_unidad" readOnly>
              <span id="sid_unidad"></span>
              <label for="id_unidad" class="form-label">ID de la Unidad</label>
            </div>`);

	// Actualizar elementosUnidad para incluir el nuevo campo
	elementosUnidad.id_unidad = $('#id_unidad');

	// Usar los datos directamente de DataTable (más confiable)
	$("#id_unidad").val(datosFila.id_unidad);
	buscarSelect("#id_dependencia", datosFila.dependencia, "text");
	$("#nombre").val(capitalizarTexto(datosFila.nombre_unidad));

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Unidad");
		$("#enviar").text("Modificar");
	} else {
		$("#nombre").prop('readOnly', true);
		$("#id_dependencia").prop('disabled', true);
		$("#modalTitleId").text("Eliminar Unidad");
		$("#enviar").text("Eliminar");
	}
	
	// Habilitar el botón inmediatamente para Modificar/Eliminar ya que los datos vienen pre-validados
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function reactivarUnidad(boton) {
	const linea = $(boton).closest('tr');
	const tabla = $('#tablaEliminadas').DataTable();
	const datosFila = tabla.row(linea).data();
	const id = datosFila.id_unidad;

	Swal.fire({
		title: '¿Reactivar Unidad?',
		text: "¿Está seguro que desea reactivar esta unidad?",
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
			datos.append('id_unidad', id);

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
							mensajes("success", null, "Unidad restaurada", lee.mensaje);
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
					mensajes("error", null, "Error", "No se pudo reactivar la unidad");
				}
			});
		}
	});
}