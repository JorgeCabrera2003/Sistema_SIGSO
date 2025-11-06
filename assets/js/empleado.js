// Elementos del formulario para Empleado
const elementosEmpleado = {
	particle: $('#particle'),
	cedula: $('#cedula'),
	nombre: $('#nombre'),
	apellido: $('#apellido'),
	telefono: $('#telefono'),
	correo: $('#correo'),
	cargo: $('#cargo'),
	ente: $('#ente'),
	dependencia: $('#dependencia'),
	unidad: $('#unidad')
};

// Función para manejar el cambio de estado del formulario
function manejarCambioEstadoEmpleado(formularioValido) {
	const accion = $("#enviar").text();

	if (accion === "Eliminar") {
		// CORRECCIÓN: El botón de eliminar siempre está habilitado
		$('#enviar').prop('disabled', false);
	} else {
		// Para registrar y modificar validamos todos los campos requeridos
		$('#enviar').prop('disabled', !formularioValido);
	}
}

// Función para habilitar campos progresivamente
function habilitarCampoProgresivo(campoActual, siguienteCampo = null) {
	const $campoActual = $(campoActual);
	const esValido = $campoActual.hasClass('is-valid');

	if (siguienteCampo) {
		const $siguienteCampo = $(siguienteCampo);
		if (esValido) {
			$siguienteCampo.prop('disabled', false);
			$siguienteCampo.prop('readOnly', false);
		} else {
			$siguienteCampo.prop('disabled', true);
			$siguienteCampo.prop('readOnly', true);
			$siguienteCampo.val(''); // Limpiar campo si se invalida el anterior

			// Deshabilitar todos los campos siguientes en cadena
			deshabilitarCamposSiguientes(siguienteCampo);
		}
	}
}

// Función para deshabilitar campos siguientes en cadena
function deshabilitarCamposSiguientes(campoInicio) {
	// Solo mantener dependencia -> unidad (y ente -> dependencia) como dependencia progresiva
	const camposSiguientes = {
		'#ente': '#dependencia',
		'#dependencia': '#unidad'
	};

	let campoActual = campoInicio;
	while (camposSiguientes[campoActual]) {
		const $campoSiguiente = $(camposSiguientes[campoActual]);
		$campoSiguiente.prop('disabled', true);
		$campoSiguiente.prop('readOnly', true);
		$campoSiguiente.val('');
		$campoSiguiente.removeClass('is-valid is-invalid');

		// Limpiar feedback
		const id = $campoSiguiente.attr('id');
		const $feedback = $(`#s${id}`);
		if ($feedback.length) {
			$feedback.removeClass("invalid-feedback valid-feedback").text("");
		}

		campoActual = camposSiguientes[campoActual];
	}
}

// Función mejorada para capitalizar texto (nombres y apellidos completos)
function capitalizarTextoCompleto(texto) {
	if (!texto) return '';

	return texto
		.toLowerCase()
		.split(' ')
		.map(palabra => {
			if (palabra.length > 0) {
				return palabra.charAt(0).toUpperCase() + palabra.slice(1);
			}
			return palabra;
		})
		.join(' ');
}

// Función mejorada para formatear teléfono con guión automático
function formatearTelefonoConGuion($campo) {
	let valor = $campo.val().replace(/[^0-9]/g, '');

	// Limitar a 11 dígitos numéricos
	if (valor.length > 11) valor = valor.substring(0, 11);

	if (valor.length > 4) {
		valor = valor.substring(0, 4) + '-' + valor.substring(4);
	}

	$campo.val(valor);
}

$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarDatosIniciales();

	// Inicializar sistema de validación con callback
	if (typeof SistemaValidacion !== 'undefined') {
		SistemaValidacion.inicializar(elementosEmpleado, manejarCambioEstadoEmpleado);
	}

	// Validar estado inicial del formulario
	manejarCambioEstadoEmpleado(false);

	// Mantener campos personales activos por defecto; solo dependencia y unidad permanecen deshabilitados
	setTimeout(() => {
		$('#cedula').prop('disabled', false).prop('readOnly', false);
		$('#nombre').prop('disabled', false).prop('readOnly', false);
		$('#apellido').prop('disabled', false).prop('readOnly', false);
		$('#telefono').prop('disabled', false).prop('readOnly', false);
		$('#correo').prop('disabled', false).prop('readOnly', false);
		$('#cargo').prop('disabled', false);
		$('#dependencia').prop('disabled', true);
		$('#unidad').prop('disabled', true);
	}, 100);

	// Mostrar modal para registrar
	$("#btn-registrar").on("click", function () {
		limpia();
		$("#modalTitleId").text("Registrar Empleado");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");

		// Deshabilitar botón inicialmente
		$('#enviar').prop('disabled', true);

		// Limpiar validación visual al abrir el modal
		setTimeout(() => {
			limpiarValidacionVisual();

			// Mantener campos personales activos; solo dependencia/unidad deshabilitadas
			$('#cedula').prop('disabled', false).prop('readOnly', false);
			$('#nombre').prop('disabled', false).prop('readOnly', false);
			$('#apellido').prop('disabled', false).prop('readOnly', false);
			$('#telefono').prop('disabled', false).prop('readOnly', false);
			$('#correo').prop('disabled', false).prop('readOnly', false);
			$('#cargo').prop('disabled', false);
			$('#dependencia').prop('disabled', true);
			$('#unidad').prop('disabled', true);
		}, 100);
	});

	// Mostrar modal de empleados eliminados
	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminados").modal("show");
	});

	// Limpiar campos al cerrar el modal
	$('#modal1').on('hidden.bs.modal', function () {
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.limpiarValidacion(elementosEmpleado);
		}
		limpiarValidacionVisual();
	});

	// Forzar validación inicial cuando se abre el modal
	$('#modal1').on('shown.bs.modal', function () {
		setTimeout(() => {
			const accion = $("#enviar").text();

			// Para Modificar y Eliminar, validar inmediatamente
			if (accion === "Modificar" || accion === "Eliminar") {
				// Marcar todos los campos como interactuados para que muestren validación
				$.each(elementosEmpleado, function (key, elemento) {
					if (elemento && elemento.length) {
						elemento.data('touched', true);
					}
				});

				// Validar formulario completo (mostrará errores visuales)
				if (typeof SistemaValidacion !== 'undefined') {
					SistemaValidacion.validarFormulario(elementosEmpleado);
				}

				// En modificar/eliminar, habilitar todos los campos
				if (accion === "Modificar") {
					$('#cedula').prop('disabled', false).prop('readOnly', false);
					$('#nombre').prop('disabled', false).prop('readOnly', false);
					$('#apellido').prop('disabled', false).prop('readOnly', false);
					$('#telefono').prop('disabled', false).prop('readOnly', false);
					$('#correo').prop('disabled', false).prop('readOnly', false);
					$('#cargo').prop('disabled', false);
					$('#dependencia').prop('disabled', false);
					$('#unidad').prop('disabled', false);
				}
			}
		}, 100);
	});

	// Enviar formulario
	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {
			case "Registrar":
				if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosEmpleado)) {
					confirmacion = await confirmarAccion("Se registrará un Empleado", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						enviarFormulario('registrar');
						envio = true;
					}
				} else {
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
				}
				break;

			case "Modificar":
				if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosEmpleado)) {
					confirmacion = await confirmarAccion("Se modificará un Empleado", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						enviarFormulario('modificar');
						envio = true;
					}
				} else {
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
				}
				break;

			case "Eliminar":
				// CORRECCIÓN: El botón eliminar siempre está habilitado, solo pedir confirmación
				confirmacion = await confirmarAccion("Se eliminará un Empleado", "¿Está seguro de realizar la acción?", "warning");
				if (confirmacion) {
					enviarFormulario('eliminar');
					envio = true;
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
});

// Función para limpiar la validación visual
function limpiarValidacionVisual() {
	$.each(elementosEmpleado, function (key, elemento) {
		if (elemento && elemento.length) {
			elemento.removeClass("is-valid is-invalid");
			const id = elemento.attr('id');
			const $feedback = $(`#s${id}`);
			if ($feedback.length) {
				$feedback.removeClass("invalid-feedback valid-feedback").text("");
			}
		}
	});
}

// Función para consultar empleados eliminados
async function consultarEliminadas() {
	console.log("Consultando empleados eliminados...");

	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');

	try {
		const respuesta = await enviaAjax(datos, true);
		console.log("Datos recibidos de empleados eliminados:", respuesta);

		if (respuesta.resultado === "consultar_eliminadas") {
			console.log("Número de registros:", respuesta.datos.length);

			// Crear la tabla primero
			TablaEliminados(respuesta.datos);

			// Esperar un momento para que se renderice la tabla
			setTimeout(() => {
				// Mostrar el modal
				console.log("Mostrando modal de eliminados");
				$('#modalEliminados').modal('show');

				// Forzar redibujado de la tabla
				if ($.fn.DataTable.isDataTable('#tablaEliminados')) {
					$('#tablaEliminados').DataTable().draw();
				}
			}, 100);

		} else if (respuesta.resultado === "error") {
			mensajes("error", null, "Error", respuesta.mensaje);
		} else {
			console.error("Resultado inesperado:", respuesta.resultado);
			mensajes("error", null, "Error", "Respuesta inesperada del servidor");
		}
	} catch (error) {
		console.error("Error en consultarEliminadas:", error);
		if (error.mensaje) {
			mensajes("error", null, "Error", error.mensaje);
		}
	}
}

// Función para reactivar empleado
async function reactivarEmpleado(boton) {
	const confirmacion = await confirmarAccion("¿Reactivar Empleado?", "¿Está seguro que desea reactivar este empleado?", "question");

	if (confirmacion) {
		const linea = $(boton).closest('tr');
		const tabla = $('#tablaEliminados').DataTable();
		const datosFila = tabla.row(linea).data();
		const cedula = datosFila.cedula;

		var datos = new FormData();
		datos.append('reactivar', 'reactivar');
		datos.append('cedula', cedula);

		// Usar la versión con promesa para reactivar
		try {
			const respuesta = await enviaAjax(datos, true);
			if (respuesta.resultado === "reactivar") {
				mensajes("success", null, "Empleado restaurado", respuesta.mensaje);
				consultarEliminadas(); // Recargar la tabla de eliminados
				consultar(); // Recargar la tabla principal
			} else if (respuesta.resultado === "error") {
				mensajes("error", null, "Error", respuesta.mensaje);
			}
		} catch (error) {
			console.error("Error reactivando empleado:", error);
			if (error.mensaje) {
				mensajes("error", null, "Error", error.mensaje);
			}
		}
	}
}

function enviarFormulario(accion) {
	const formData = new FormData();
	formData.append(accion, accion);

	// Obtener valores
	const particle = $("#particle").val();
	const cedulaNumeros = $("#cedula").val().replace(/[^0-9]/g, ''); // Solo números

	// CORRECCIÓN: Diferente formato según la acción
	let cedulaCompleta;
	if (accion === 'eliminar') {
		// Para eliminar: particle + cedulaNumeros (cedula completa)
		cedulaCompleta = particle + cedulaNumeros;
	} else {
		// Para registrar y modificar: solo cedulaNumeros (solo números)
		cedulaCompleta = cedulaNumeros;
	}

	// CORRECCIÓN: Aplicar capitalización completa a nombre y apellido
	const nombre = capitalizarTextoCompleto($("#nombre").val());
	const apellido = capitalizarTextoCompleto($("#apellido").val());

	// Campos del empleado
	formData.append('particle', particle);
	formData.append('cedula', cedulaCompleta);
	formData.append('nombre', nombre);
	formData.append('apellido', apellido);
	formData.append('telefono', $("#telefono").val());
	formData.append('correo', $("#correo").val());
	formData.append('unidad', $("#unidad").val());
	formData.append('cargo', $("#cargo").val());
	formData.append('ente', $("#ente").val());
	formData.append('dependencia', $("#dependencia").val());

	console.log("Enviando datos:", {
		accion: accion,
		particle: particle,
		cedula: cedulaCompleta,
		nombre: nombre,
		apellido: apellido,
		telefono: $("#telefono").val(),
		correo: $("#correo").val(),
		unidad: $("#unidad").val(),
		cargo: $("#cargo").val(),
		ente: $("#ente").val(),
		dependencia: $("#dependencia").val()
	});

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
				var json = JSON.parse(respuesta);
				console.log("Respuesta del servidor:", json);

				if (json.resultado === accion) {
					mensajes("success", null, "Éxito", json.mensaje);
					consultar();
					$("#modal1").modal("hide");
				} else if (json.resultado === "error") {
					mensajes("error", null, "Error", json.mensaje);
				} else {
					mensajes("error", null, "Error", "Respuesta inesperada del servidor");
				}
			} catch (e) {
				console.error("Error parseando respuesta JSON:", e);
				console.log("Respuesta cruda:", respuesta);
				mensajes("error", null, "Error", "Error procesando la respuesta del servidor");
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error("Error AJAX:", textStatus, errorThrown);
			mensajes("error", null, "Error", "Error de conexión con el servidor");
		},
		complete: function () {
			$('#enviar').prop('disabled', false).text(accion.charAt(0).toUpperCase() + accion.slice(1));
		}
	});
}

// Función para cargar datos iniciales
async function cargarDatosIniciales() {
	console.log("Cargando datos iniciales...");

	try {
		// Cargar entes
		await cargarEnte();

		// Cargar cargos
		await cargarCargo();

		// Inicializar selects como deshabilitados
		$('#dependencia').prop('disabled', true);
		$('#unidad').prop('disabled', true);

		console.log("Datos iniciales cargados correctamente");
	} catch (error) {
		console.error("Error cargando datos iniciales:", error);
	}
}

// Función para cargar entes
async function cargarEnte() {
	console.log("Cargando entes...");

	var datos = new FormData();
	datos.append('cargar_ente', 'cargar_ente');

	try {
		const respuesta = await enviaAjax(datos, true);
		console.log("Respuesta de entes:", respuesta);

		if (respuesta.resultado === "consultar") {
			const selectEnte = $('#ente');
			selectEnte.empty().append('<option value="" selected disabled hidden>Seleccione un ente</option>');

			respuesta.datos.forEach(ente => {
				selectEnte.append(`<option value="${ente.id}">${ente.nombre}</option>`);
			});

			console.log("Entes cargados:", respuesta.datos.length);
		} else {
			console.error("Error cargando entes:", respuesta);
		}
	} catch (error) {
		console.error("Error en cargarEnte:", error);
	}
}

// Función para cargar cargos
async function cargarCargo() {
	console.log("Cargando cargos...");

	var datos = new FormData();
	datos.append('cargar_cargo', 'cargar_cargo');

	try {
		const respuesta = await enviaAjax(datos, true);
		console.log("Respuesta de cargos:", respuesta);

		if (respuesta.resultado === "consultar") {
			const selectCargo = $('#cargo');
			selectCargo.empty().append('<option value="" selected disabled hidden>Seleccione un cargo</option>');

			respuesta.datos.forEach(cargo => {
				selectCargo.append(`<option value="${cargo.id_cargo}">${cargo.nombre_cargo}</option>`);
			});

			console.log("Cargos cargados:", respuesta.datos.length);
		} else {
			console.error("Error cargando cargos:", respuesta);
		}
	} catch (error) {
		console.error("Error en cargarCargo:", error);
	}
}

// Función para cargar dependencias por ente
async function cargarDependencia(idEnte) {
	console.log("Cargando dependencias para ente:", idEnte);

	var datos = new FormData();
	datos.append('cargar_dependencia', 'cargar_dependencia');
	datos.append('id_ente', idEnte);

	try {
		const respuesta = await enviaAjax(datos, true);
		console.log("Respuesta de dependencias:", respuesta);

		if (respuesta.resultado === "consultar_por_ente") {
			const selectDependencia = $('#dependencia');
			selectDependencia.empty().append('<option value="" selected disabled hidden>Seleccione una dependencia</option>');

			respuesta.datos.forEach(dep => {
				selectDependencia.append(`<option value="${dep.id}">${dep.nombre}</option>`);
			});

			// Habilitar el select de dependencia
			selectDependencia.prop('disabled', false);

			// Limpiar y deshabilitar unidad cuando cambia la dependencia
			const selectUnidad = $('#unidad');
			selectUnidad.empty().append('<option value="" selected disabled hidden>Seleccione una unidad</option>');
			selectUnidad.prop('disabled', true);

			console.log("Dependencias cargadas:", respuesta.datos.length);
		} else {
			console.error("Error cargando dependencias:", respuesta);
			// Deshabilitar dependencia y unidad si hay error
			$('#dependencia').prop('disabled', true);
			$('#unidad').prop('disabled', true);
		}
	} catch (error) {
		console.error("Error en cargarDependencia:", error);
		// Deshabilitar dependencia y unidad si hay error
		$('#dependencia').prop('disabled', true);
		$('#unidad').prop('disabled', true);
	}
}

// Función para cargar unidades por dependencia
async function cargarUnidad(idDependencia) {
	console.log("Cargando unidades para dependencia:", idDependencia);

	var datos = new FormData();
	datos.append('cargar_unidad', 'cargar_unidad');
	datos.append('id_dependencia', idDependencia);

	try {
		const respuesta = await enviaAjax(datos, true);
		console.log("Respuesta de unidades:", respuesta);

		if (respuesta.resultado === "consultar_por_dependencia") {
			const selectUnidad = $('#unidad');
			selectUnidad.empty().append('<option value="" selected disabled hidden>Seleccione una unidad</option>');

			respuesta.datos.forEach(unidad => {
				selectUnidad.append(`<option value="${unidad.id_unidad}">${unidad.nombre_unidad}</option>`);
			});

			// Habilitar el select de unidad
			selectUnidad.prop('disabled', false);

			console.log("Unidades cargadas:", respuesta.datos.length);
		} else {
			console.error("Error cargando unidades:", respuesta);
			// Deshabilitar unidad si hay error
			$('#unidad').prop('disabled', true);
		}
	} catch (error) {
		console.error("Error en cargarUnidad:", error);
		// Deshabilitar unidad si hay error
		$('#unidad').prop('disabled', true);
	}
}

// Evento cuando cambia el ente
$(document).on('change', '#ente', function () {
	const idEnte = $(this).val();
	if (idEnte) {
		cargarDependencia(idEnte);
	} else {
		// Si no hay ente seleccionado, deshabilitar y limpiar dependencia y unidad
		$('#dependencia').empty().append('<option value="" selected disabled hidden>Seleccione una dependencia</option>').prop('disabled', true);
		$('#unidad').empty().append('<option value="" selected disabled hidden>Seleccione una unidad</option>').prop('disabled', true);
	}
});

// Evento cuando cambia la dependencia
$(document).on('change', '#dependencia', function () {
	const idDependencia = $(this).val();
	if (idDependencia) {
		cargarUnidad(idDependencia);
	} else {
		// Si no hay dependencia seleccionada, deshabilitar y limpiar unidad
		$('#unidad').empty().append('<option value="" selected disabled hidden>Seleccione una unidad</option>').prop('disabled', true);
	}
});

// Función para consultar empleados activos
function consultar() {
	var datos = new FormData();
	datos.append('consultar', 'consultar');

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
				var json = JSON.parse(respuesta);
				console.log("Datos recibidos:", json);

				if (json.resultado === "consultar") {
					Tabla(json.datos);
				} else if (json.resultado === "error") {
					mensajes("error", null, "Error", json.mensaje);
				}
			} catch (e) {
				console.error("Error parseando JSON:", e);
				console.log("Respuesta cruda:", respuesta);
				mensajes("error", null, "Error", "Error procesando la respuesta del servidor");
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error("Error AJAX:", textStatus, errorThrown);
			mensajes("error", null, "Error", "Error de conexión con el servidor");
		}
	});
}

// Función para registrar entrada al módulo
function registrarEntrada() {
	var datos = new FormData();
	datos.append('entrada', 'entrada');

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
				var json = JSON.parse(respuesta);
				console.log("Entrada registrada:", json);
			} catch (e) {
				console.error("Error parseando JSON de entrada:", e);
			}
		}
	});
}

// Configuración de idioma en español para DataTables
const lenguajeEspanol = {
	"emptyTable": "No hay datos disponibles en la tabla",
	"info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
	"infoEmpty": "Mostrando 0 a 0 de 0 registros",
	"infoFiltered": "(filtrado de _MAX_ registros totales)",
	"infoPostFix": "",
	"thousands": ",",
	"lengthMenu": "Mostrar _MENU_ registros",
	"loadingRecords": "Cargando...",
	"processing": "Procesando...",
	"search": "Buscar:",
	"zeroRecords": "No se encontraron registros coincidentes",
	"paginate": {
		"first": "Primero",
		"last": "Último",
		"next": "Siguiente",
		"previous": "Anterior"
	},
	"aria": {
		"sortAscending": ": activar para ordenar columna ascendente",
		"sortDescending": ": activar para ordenar columna descendente"
	}
};

// Función para crear la tabla de empleados eliminados
function TablaEliminados(datos) {
	console.log("Creando tabla de eliminados con datos:", datos);

	// Destruir tabla existente si existe
	if ($.fn.DataTable.isDataTable('#tablaEliminados')) {
		$('#tablaEliminados').DataTable().destroy();
	}

	// Limpiar el contenedor
	$('#tablaEliminados').empty();

	// Crear tabla
	var tabla = $('#tablaEliminados').DataTable({
		responsive: true,
		autoWidth: false,
		deferRender: true,
		data: datos,
		columns: [
			{data: 'cedula', title: 'Cédula'},
			{data: 'nombre', title: 'Nombre'},
			{data: 'apellido', title: 'Apellido'},
			{data: 'telefono', title: 'Teléfono'},
			{data: 'correo', title: 'Correo'},
			{
				data: null,
				title: 'Acciones',
				render: function (data, type, row) {
					return `
                        <button class="btn btn-success btn-sm" onclick="reactivarEmpleado(this)" title="Reactivar Empleado">
                            <i class="fas fa-undo"></i> Reactivar
                        </button>
                    `;
				}
			}
		],
		language: lenguajeEspanol,
		dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6 text-end"f>>' +
			'<"row"<"col-sm-12"tr>>' +
			'<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
		buttons: [
			{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i> Excel',
				className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)'
				}
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i> PDF',
				className: 'btn btn-danger',
				exportOptions: {
					columns: ':not(:last-child)'
				}
			},
			{
				extend: 'print',
				text: '<i class="fas fa-print"></i> Imprimir',
				className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)'
				}
			}
		],
		initComplete: function () {
			console.log("Tabla de eliminados inicializada correctamente");
		},
		drawCallback: function () {
			console.log("Tabla de eliminados redibujada");
		}
	});

	console.log("Tabla de eliminados creada con", datos.length, "registros");
	return tabla;
}

// Función para crear la tabla principal
function Tabla(datos) {
	console.log("Creando tabla principal con datos:", datos);

	// Destruir tabla existente si existe
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}

	// Limpiar el contenedor
	$('#tabla1').empty();

	// Crear tabla
	var tabla = $('#tabla1').DataTable({
		responsive: true,
		autoWidth: false,
		deferRender: true,
		data: datos,
		columns: [
			{data: 'cedula', title: 'Cédula'},
			{data: 'nombre', title: 'Nombre'},
			{data: 'apellido', title: 'Apellido'},
			{data: 'telefono', title: 'Teléfono'},
			{data: 'correo', title: 'Correo'},
			{data: 'dependencia', title: 'Dependencia'},
			{data: 'unidad', title: 'Unidad'},
			{data: 'cargo', title: 'Cargo'},
			{
				data: null,
				title: 'Acciones',
				render: function (data, type, row) {
					return `
                        <button class="btn btn-warning btn-sm" onclick="rellenar(this, 0)" title="Modificar Empleado">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="rellenar(this, 1)" title="Eliminar Empleado">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
				}
			}
		],
		language: lenguajeEspanol,
		dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6 text-end"f>>' +
			'<"row"<"col-sm-12"tr>>' +
			'<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
		buttons: [
			{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i> Excel',
				className: 'btn btn-success',
				exportOptions: {
					columns: ':not(:last-child)'
				}
			},
			{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i> PDF',
				className: 'btn btn-danger',
				exportOptions: {
					columns: ':not(:last-child)'
				}
			},
			{
				extend: 'print',
				text: '<i class="fas fa-print"></i> Imprimir',
				className: 'btn btn-info',
				exportOptions: {
					columns: ':not(:last-child)'
				}
			}
		],
		initComplete: function () {
			console.log("Tabla principal inicializada correctamente");
		}
	});

	console.log("Tabla principal creada con", datos.length, "registros");
	return tabla;
}

// Función para rellenar formulario con datos existentes - MEJORADA
async function rellenar(pos, accion) {
	limpia();

	const linea = $(pos).closest('tr');
	const tabla = $('#tabla1').DataTable();
	const datosFila = tabla.row(linea).data();

	console.log("Datos de la fila seleccionada:", datosFila);

	// Separar particle y cédula
	const cedulaCompleta = datosFila.cedula;
	const particle = cedulaCompleta.substring(0, 2);
	const cedula = cedulaCompleta.substring(2);

	// Llenar campos básicos
	$("#particle").val(particle).trigger('change');
	$("#cedula").val(cedula);
	$("#nombre").val(capitalizarTextoCompleto(datosFila.nombre));
	$("#apellido").val(capitalizarTextoCompleto(datosFila.apellido));
	$("#telefono").val(datosFila.telefono);
	$("#correo").val(datosFila.correo);

	// Buscar cargo por texto
	buscarSelect('#cargo', datosFila.cargo, 'text');

	// SOLUCIÓN MEJORADA: Buscar ente, dependencia y unidad por texto
	// Primero buscar el ente
	const enteEncontrado = buscarSelectPorTexto('#ente', datosFila.dependencia.split(' - ')[0]); // Tomar solo el nombre del ente

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Empleado");
		$("#enviar").text("Modificar");

		// En modificar, habilitar todos los campos
		setTimeout(() => {
			$('#cedula').prop('disabled', false).prop('readOnly', false);
			$('#nombre').prop('disabled', false).prop('readOnly', false);
			$('#apellido').prop('disabled', false).prop('readOnly', false);
			$('#telefono').prop('disabled', false).prop('readOnly', false);
			$('#correo').prop('disabled', false).prop('readOnly', false);
			$('#cargo').prop('disabled', false);
			$('#dependencia').prop('disabled', false);
			$('#unidad').prop('disabled', false);

			// Si se encontró el ente, cargar dependencias y buscar la correcta
			if (enteEncontrado) {
				const idEnte = $('#ente').val();
				if (idEnte && idEnte !== "default") {
					// Cargar dependencias para este ente
					cargarDependencia(idEnte).then(() => {
						// Esperar a que se carguen las dependencias
						setTimeout(() => {
							// Buscar dependencia por texto mejorado
							buscarSelectPorTextoMejorado('#dependencia', datosFila.dependencia, datosFila.unidad);
						}, 500);
					});
				}
			}

		}, 100);
	} else {
		// En eliminar, hacer campos de solo lectura
		$("#cedula").prop('readOnly', true);
		$("#nombre").prop('readOnly', true);
		$("#apellido").prop('readOnly', true);
		$("#telefono").prop('readOnly', true);
		$("#correo").prop('readOnly', true);
		$("#particle").prop('disabled', true);
		$("#cargo").prop('disabled', true);
		$("#ente").prop('disabled', true);
		$("#dependencia").prop('disabled', true);
		$("#unidad").prop('disabled', true);

		$("#modalTitleId").text("Eliminar Empleado");
		$("#enviar").text("Eliminar");

		// Si se encontró el ente, cargar dependencias y buscar la correcta
		if (enteEncontrado) {
			const idEnte = $('#ente').val();
			if (idEnte && idEnte !== "default") {
				// Cargar dependencias para este ente
				cargarDependencia(idEnte).then(() => {
					// Esperar a que se carguen las dependencias
					setTimeout(() => {
						// Buscar dependencia por texto mejorado
						buscarSelectPorTextoMejorado('#dependencia', datosFila.dependencia, datosFila.unidad);
					}, 500);
				});
			}
		}
	}

	// CORRECCIÓN: El botón eliminar siempre está habilitado
	if (accion == 1) {
		$('#enviar').prop('disabled', false);
	}

	$("#modal1").modal("show");

	// Marcar todos los campos como interactuados para mostrar validación inmediata
	setTimeout(() => {
		$.each(elementosEmpleado, function (key, elemento) {
			if (elemento && elemento.length) {
				elemento.data('touched', true);
			}
		});

		// Validar formulario completo para mostrar estados visuales
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarFormulario(elementosEmpleado);
		}
	}, 100);
}

// NUEVA FUNCIÓN: Buscar en select por texto (MEJORADA)
function buscarSelectPorTexto(id, textoBuscado) {
	const select = $(id);
	if (!select.length) return false;

	console.log(`Buscando por texto en ${id}:`, textoBuscado);

	let encontrado = false;
	const textoBuscadoLimpio = textoBuscado.trim().toLowerCase();

	select.find('option').each(function () {
		const option = $(this);
		const optionText = option.text().trim().toLowerCase();
		const optionValue = option.val();

		// Si es el option por defecto, saltar
		if (optionValue === "default") return true;

		console.log(`Comparando texto: "${optionText}" con "${textoBuscadoLimpio}"`);

		// Buscar coincidencias más flexibles
		if (optionText === textoBuscadoLimpio ||
			optionText.includes(textoBuscadoLimpio) ||
			textoBuscadoLimpio.includes(optionText) ||
			optionText.replace(/\s+/g, ' ') === textoBuscadoLimpio.replace(/\s+/g, ' ')) {

			select.val(optionValue).trigger('change');
			encontrado = true;
			console.log(`Encontrado por texto en ${id}:`, optionValue);
			return false; // Salir del bucle
		}
	});

	if (!encontrado) {
		console.log(`No encontrado por texto en ${id}, usando default`);
		select.val('default').trigger('change');
	}

	return encontrado;
}

// NUEVA FUNCIÓN: Buscar dependencia y unidad de forma coordinada
function buscarSelectPorTextoMejorado(idDependencia, textoDependencia, textoUnidad) {
	const selectDependencia = $(idDependencia);
	if (!selectDependencia.length) return;

	console.log(`Buscando dependencia mejorado:`, {textoDependencia, textoUnidad});

	let dependenciaEncontrada = false;
	const textoDependenciaLimpio = textoDependencia.trim().toLowerCase();

	// Primero buscar la dependencia
	selectDependencia.find('option').each(function () {
		const option = $(this);
		const optionText = option.text().trim().toLowerCase();
		const optionValue = option.val();

		if (optionValue === "default") return true;

		console.log(`Comparando dependencia: "${optionText}" con "${textoDependenciaLimpio}"`);

		// Buscar coincidencias en la dependencia
		if (optionText === textoDependenciaLimpio ||
			textoDependenciaLimpio.includes(optionText) ||
			optionText.includes(textoDependenciaLimpio.split(' - ')[1] || textoDependenciaLimpio)) {

			selectDependencia.val(optionValue).trigger('change');
			dependenciaEncontrada = true;
			console.log(`Dependencia encontrada:`, optionValue);

			// Una vez encontrada la dependencia, cargar y buscar la unidad
			setTimeout(() => {
				const idDependencia = optionValue;
				if (idDependencia && idDependencia !== "default") {
					cargarUnidad(idDependencia).then(() => {
						setTimeout(() => {
							buscarSelectPorTexto('#unidad', textoUnidad);
						}, 500);
					});
				}
			}, 300);

			return false; // Salir del bucle
		}
	});

	if (!dependenciaEncontrada) {
		console.log(`Dependencia no encontrada, usando default`);
		selectDependencia.val('default').trigger('change');
	}
}

// Función para buscar opción en select - MEJORADA
function buscarSelect(id, valor, tipo) {
	const select = $(id);
	if (!select.length) return;

	let encontrado = false;
	const valorBuscado = tipo === 'text' ? valor.toString().trim() : valor;

	console.log(`Buscando en ${id}:`, {valorBuscado, tipo});

	select.find('option').each(function () {
		const option = $(this);
		const optionValue = tipo === 'text' ? option.text().trim() : option.val();
		const optionText = option.text().trim();

		console.log(`Comparando: "${optionText}" con "${valorBuscado}"`);

		if (tipo === 'text') {
			if (optionText === valorBuscado) {
				select.val(option.val()).trigger('change');
				encontrado = true;
				console.log(`Encontrado en ${id}:`, option.val());
				return false; // Salir del bucle
			}
		} else {
			if (optionValue === valorBuscado) {
				select.val(option.val()).trigger('change');
				encontrado = true;
				console.log(`Encontrado en ${id}:`, option.val());
				return false; // Salir del bucle
			}
		}
	});

	if (!encontrado) {
		console.log(`No encontrado en ${id}, usando placeholder`);
		select.val('').trigger('change');

		// Si es ente, limpiar dependencia y unidad
		if (id === '#ente') {
			$('#dependencia').val('').trigger('change');
			$('#unidad').val('').trigger('change');
		}
		// Si es dependencia, limpiar unidad
		else if (id === '#dependencia') {
			$('#unidad').val('').trigger('change');
		}
	}
}

// Función para capitalizar texto (mantener compatibilidad)
function capitalizarTexto(texto) {
	if (!texto) return '';
	return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
}

// Función para limpiar formulario
function limpia() {
	// Limpiar campos del formulario
	$("#particle").val('default').trigger('change').prop('disabled', false);
	$("#cedula").val('').prop('readOnly', false);
	$("#nombre").val('').prop('readOnly', false);
	$("#apellido").val('').prop('readOnly', false);
	$("#telefono").val('').prop('readOnly', false);
	$("#correo").val('').prop('readOnly', false);
	$("#cargo").val('default').trigger('change').prop('disabled', false);
	$("#ente").val('default').trigger('change').prop('disabled', false);
	$("#dependencia").val('default').trigger('change').prop('disabled', true);
	$("#unidad").val('default').trigger('change').prop('disabled', true);

	// Limpiar validación
	if (typeof SistemaValidacion !== 'undefined') {
		SistemaValidacion.limpiarValidacion(elementosEmpleado);
	}

	// Limpiar validación visual
	limpiarValidacionVisual();

	// Restaurar placeholder de cédula
	$("#cedula").attr("placeholder", "00000000");

	// Mantener campos personales activos; sólo dependencia y unidad deben permanecer deshabilitadas
	setTimeout(() => {
		$('#dependencia').prop('disabled', true);
		$('#unidad').prop('disabled', true);
	}, 100);
}

// Función para enviar AJAX con promesa
function enviaAjax(datos, usarPromesa = false) {
	if (usarPromesa) {
		return new Promise((resolve, reject) => {
			$.ajax({
				async: true,
				url: "",
				type: "POST",
				contentType: false,
				data: datos,
				processData: false,
				cache: false,
				timeout: 30000,
				success: function (respuesta) {
					try {
						// Verificar si la respuesta es HTML en lugar de JSON
						if (respuesta.trim().startsWith('<!DOCTYPE') ||
							respuesta.trim().startsWith('<html') ||
							respuesta.includes('<!DOCTYPE html>')) {

							console.error("El servidor devolvió HTML en lugar de JSON");
							console.log("Respuesta HTML recibida:", respuesta.substring(0, 500));

							const errorObj = {
								resultado: "error",
								mensaje: "Error del servidor: Se recibió una página HTML en lugar de JSON. Verifique que la sesión esté activa y que no haya errores de PHP."
							};
							reject(errorObj);
							return;
						}

						const json = JSON.parse(respuesta);
						resolve(json);
					} catch (e) {
						console.error("Error parseando JSON:", e);
						console.log("Respuesta cruda:", respuesta.substring(0, 500));

						const errorObj = {
							resultado: "error",
							mensaje: "Error procesando la respuesta del servidor: " + e.message
						};
						reject(errorObj);
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error("Error AJAX:", textStatus, errorThrown);
					const errorObj = {
						resultado: "error",
						mensaje: "Error de conexión: " + textStatus
					};
					reject(errorObj);
				}
			});
		});
	} else {
		// Versión original sin promesa (para mantener compatibilidad)
		$.ajax({
			async: true,
			url: "",
			type: "POST",
			contentType: false,
			data: datos,
			processData: false,
			cache: false,
			timeout: 30000,
			success: function (respuesta) {
				try {
					const json = JSON.parse(respuesta);
					console.log("Respuesta recibida:", json);

					// Procesar diferentes tipos de respuesta
					switch (json.resultado) {
						case "consultar":
							Tabla(json.datos);
							break;
						case "registrar":
						case "modificar":
						case "eliminar":
							mensajes("success", null, "Éxito", json.mensaje);
							consultar();
							$("#modal1").modal("hide");
							break;
						case "error":
							mensajes("error", null, "Error", json.mensaje);
							break;
						default:
							console.warn("Tipo de respuesta no manejado:", json.resultado);
					}
				} catch (e) {
					console.error("Error parseando JSON:", e);
					mensajes("error", null, "Error", "Error procesando la respuesta del servidor");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.error("Error AJAX:", textStatus, errorThrown);
				mensajes("error", null, "Error", "Error de conexión con el servidor");
			}
		});
	}
}

// Capa de validación de campos - CORREGIDA
function capaValidar() {
	// Validación para particle (nacionalidad) - habilita cédula
	$("#particle").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);
		}
	});

	// Validación con formato en tiempo real para cédula (solo números)
	$("#cedula").on("keypress", function (e) {
		validarKeyPress(/^[0-9\b]*$/, e);
	});

	// Validación de cédula
	$("#cedula").on("input", function () {
		// Limpiar cualquier carácter no numérico
		let valor = $(this).val().replace(/[^0-9]/g, '');
		$(this).val(valor);

		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
		}

		// Actualizar placeholder según estado de validación
		if (patrones.cedulaNumeros.test(valor)) {
			$(this).attr("placeholder", "");
		} else {
			$(this).attr("placeholder", "00000000");
		}
	});

	// Validación con formato en tiempo real para nombre (solo letras y espacios) - habilita apellido
	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ \b]*$/, e);
	});

	// Aplicar capitalización en tiempo real para nombre - habilita apellido
	$("#nombre").on("input", function () {
		const valor = $(this).val();
		if (valor && valor.length === 1) {
			$(this).val(valor.toUpperCase());
		}

		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
		}
	});

	// CORRECCIÓN: Aplicar capitalización COMPLETA al perder el foco (para nombres completos)
	$("#nombre").on("blur", function () {
		const valor = $(this).val();
		if (valor) {
			// Aplicar capitalización completa a todos los nombres
			$(this).val(capitalizarTextoCompleto(valor));
		}
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
		}
	});

	// Validación con formato en tiempo real para apellido (solo letras y espacios) - habilita teléfono
	$("#apellido").on("keypress", function (e) {
		validarKeyPress(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ \b]*$/, e);
	});

	// Aplicar capitalización en tiempo real para apellido - habilita teléfono
	$("#apellido").on("input", function () {
		const valor = $(this).val();
		if (valor && valor.length === 1) {
			$(this).val(valor.toUpperCase());
		}

		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
		}
	});

	// CORRECCIÓN: Aplicar capitalización COMPLETA al perder el foco (para apellidos completos)
	$("#apellido").on("blur", function () {
		const valor = $(this).val();
		if (valor) {
			// Aplicar capitalización completa a todos los apellidos
			$(this).val(capitalizarTextoCompleto(valor));
		}
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
		}
	});

	// Validación con formato en tiempo real para teléfono (solo números; guión se inserta automáticamente) - habilita correo
	$("#telefono").on("keypress", function (e) {
		validarKeyPress(/^[0-9\b]*$/, e);
	});

	// Manejar pegado en teléfono: sanitizar y formatear
	$("#telefono").on("paste", function (e) {
		e.preventDefault();
		const texto = (e.originalEvent || e).clipboardData.getData('text') || '';
		const soloNumeros = texto.replace(/[^0-9]/g, '').substring(0, 11);
		$(this).val(soloNumeros);
		formatearTelefonoConGuion($(this));
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
		}
	});

	// Formato automático para teléfono en input (inserta guion) - habilita correo
	$("#telefono").on("input", function () {
		formatearTelefonoConGuion($(this));
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
			// Nota: no se habilitan campos personales progresivamente; solo dependencias lo hacen
		}
	});

	// Validación con formato en tiempo real para correo - habilita cargo
	$("#correo").on("keypress", function (e) {
		validarKeyPress(/^[-0-9a-z_.@\b]*$/, e);
	});

	// Validación en tiempo real para correo - habilita cargo
	$("#correo").on("input", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
			// No habilitar cargo de forma progresiva; los campos personales están disponibles
		}
	});

	// Validación para cargo - habilita ente
	$("#cargo").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);
			// El ente no depende del cargo en esta versión; dejarlo libre
		}
	});

	// Validación para ente - habilita dependencia
	$("#ente").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);

			// Habilitar/deshabilitar dependencia según validación
			habilitarCampoProgresivo(this, '#dependencia');

			// Limpiar validación de dependencia y unidad cuando cambia el ente
			$("#dependencia").removeClass("is-valid is-invalid");
			$("#unidad").removeClass("is-valid is-invalid");
			$("#sdependencia").removeClass("invalid-feedback valid-feedback").text("");
			$("#sunidad").removeClass("invalid-feedback valid-feedback").text("");
		}
	});

	// Validación para dependencia - habilita unidad
	$("#dependencia").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);

			// Habilitar/deshabilitar unidad según validación
			habilitarCampoProgresivo(this, '#unidad');

			// Limpiar validación de unidad cuando cambia la dependencia
			$("#unidad").removeClass("is-valid is-invalid");
			$("#sunidad").removeClass("invalid-feedback valid-feedback").text("");
		}
	});

	// Validación para unidad
	$("#unidad").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);
		}
	});
}

// Función para validar entrada de teclado
function validarKeyPress(patron, e) {
	const char = String.fromCharCode(e.which);
	if (!patron.test(char)) {
		e.preventDefault();
	}
}

// AGREGAR PATRONES ESPECÍFICOS PARA EMPLEADO AL OBJETO PATRONES EXISTENTE
// Solo si el objeto patrones ya existe (definido en utils.js)
if (typeof patrones !== 'undefined') {
	// Cédula: 7 u 8 dígitos (acepta ambas longitudes)
	patrones.cedulaNumeros = /^\d{7,8}$/;
	// Nombre y apellido: entre 3 y 45 letras/espacios
	patrones.nombrePersona = /^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{3,45}$/;
	patrones.apellidoPersona = /^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{3,45}$/;
	// Correo: mantener validación existente (puede mejorarse más adelante)
	patrones.correoEmpleado = /^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[a-z]{2,4}$/i;
	// Teléfono: debe empezar en 0, 4 dígitos antes del guion y 7 después -> 11 dígitos numéricos
	patrones.telefonoEmpleado = /^0\d{3}-\d{7}$/;
}

// Extender SistemaValidacion para campos de empleado
if (typeof SistemaValidacion !== 'undefined') {
	// Guardar la función original de validarCampo
	const originalValidarCampo = SistemaValidacion.validarCampo;

	// Sobrescribir la función para incluir validaciones específicas de empleado
	SistemaValidacion.validarCampo = function () {
		const $campo = $(this);
		const valor = $campo.val() ? $campo.val().trim() : '';
		const id = this.id;
		let esValido = true;
		let mensajeError = '';

		// Solo mostrar errores si el campo ya fue interactuado
		const fueInteractuado = $campo.data('touched') || $campo.is(':focus');

		// Validaciones específicas para campos de empleado
		switch (id) {
			case 'cedula':
				esValido = patrones.cedulaNumeros.test(valor);
				mensajeError = 'La cédula debe tener 7 u 8 dígitos numéricos';
				break;

			case 'nombre':
				esValido = patrones.nombrePersona.test(valor);
				mensajeError = 'El nombre debe tener entre 3 y 45 caracteres (solo letras y espacios)';
				break;

			case 'apellido':
				esValido = patrones.apellidoPersona.test(valor);
				mensajeError = 'El apellido debe tener entre 3 y 45 caracteres (solo letras y espacios)';
				break;

			case 'telefono':
				esValido = patrones.telefonoEmpleado.test(valor);
				mensajeError = 'El teléfono debe tener formato 0412-1234567';
				break;

			case 'correo':
				esValido = patrones.correoEmpleado.test(valor);
				mensajeError = 'El formato del correo electrónico es: usuario@servidor.com';
				break;

			case 'particle':
			case 'cargo':
			case 'ente':
			case 'dependencia':
			case 'unidad':
				esValido = valor !== "default" && valor !== "" && valor !== null;
				mensajeError = 'Debe seleccionar una opción válida';
				break;

			default:
				// Si no es un campo específico de empleado, usar la validación original
				return originalValidarCampo.call(this);
		}

		// Aplicar estilos de validación SOLO si el campo fue interactuado
		if (fueInteractuado) {
			SistemaValidacion.aplicarEstilos($campo, esValido, mensajeError);
		} else {
			// Limpiar estilos visuales si no ha sido interactuado
			SistemaValidacion.limpiarEstilosCampo($campo);
		}

		// Verificar estado general del formulario después de cada validación
		if (SistemaValidacion.callbackCambioEstado) {
			const formularioValido = SistemaValidacion.verificarEstadoFormulario();
			SistemaValidacion.callbackCambioEstado(formularioValido);
		}

		return esValido;
	};
}