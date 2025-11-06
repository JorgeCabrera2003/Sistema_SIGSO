$(document).ready(async function () {
	// Elementos del formulario para Usuario
	const elementosUsuario = {
		particle: $('#particle'),
		cedula: $('#cedula'),
		nombre: $('#nombre'),
		apellido: $('#apellido'),
		telefono: $('#telefono'),
		correo: $('#correo'),
		cargo: $('#cargo'),
		ente: $('#ente'),
		dependencia: $('#dependencia'),
		unidad: $('#unidad'),
		nombre_usuario: $('#nombre_usuario'),
		rol: $('#rol'),
		clave: $('#clave'),
		rclave: $('#rclave')
	};

	// Función para manejar el cambio de estado del formulario
	function manejarCambioEstadoUsuario(formularioValido) {
		const accion = $("#enviar").text();
		
		if (accion === "Eliminar") {
			$('#enviar').prop('disabled', false);
		} else {
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
				$siguienteCampo.val('');

				deshabilitarCamposSiguientes(siguienteCampo);
			}
		}
	}

	// Función para deshabilitar campos siguientes en cadena
	function deshabilitarCamposSiguientes(campoInicio) {
		const camposSiguientes = {
			'#particle': '#cedula',
			'#cedula': '#nombre',
			'#nombre': '#apellido',
			'#apellido': '#telefono',
			'#telefono': '#correo',
			'#correo': '#cargo',
			'#cargo': '#ente',
			'#ente': '#dependencia',
			'#dependencia': '#unidad',
			'#unidad': '#nombre_usuario',
			'#nombre_usuario': '#rol'
		};

		let campoActual = campoInicio;
		while (camposSiguientes[campoActual]) {
			const $campoSiguiente = $(camposSiguientes[campoActual]);
			$campoSiguiente.prop('disabled', true);
			$campoSiguiente.prop('readOnly', true);
			$campoSiguiente.val('');
			$campoSiguiente.removeClass('is-valid is-invalid');

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

		if (valor.length > 4) {
			valor = valor.substring(0, 4) + '-' + valor.substring(4, 11);
		}

		$campo.val(valor);
	}

	// Función para limpiar la validación visual
	function limpiarValidacionVisual() {
		$.each(elementosUsuario, function (key, elemento) {
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

	$(".toggle-password").click(function () {
		const target = $(this).data("target");
		const input = $(target);
		const icon = $(this).find("i");

		if (input.attr("type") === "password") {
			input.attr("type", "text");
			icon.removeClass("fa-eye").addClass("fa-eye-slash");
		} else {
			input.attr("type", "password");
			icon.removeClass("fa-eye-slash").addClass("fa-eye");
		}
	});

	datos_sesion = new FormData();
	datos_sesion.append('traer_sesion', 'traer_sesion');
	arraySesion = await enviaAjax(datos_sesion);
	arraySesion = JSON.parse(arraySesion);

	consultar();
	registrarEntrada();
	capaValidar();
	cargarRol();
	cargarCargo();
	cargarEnte();

	// Inicializar sistema de validación con callback
	if (typeof SistemaValidacion !== 'undefined') {
		SistemaValidacion.inicializar(elementosUsuario, manejarCambioEstadoUsuario);
	}

	// Validar estado inicial del formulario
	manejarCambioEstadoUsuario(false);

	// Deshabilitar todos los campos excepto el primero al inicio
	setTimeout(() => {
		$('#cedula').prop('disabled', true).prop('readOnly', true);
		$('#nombre').prop('disabled', true).prop('readOnly', true);
		$('#apellido').prop('disabled', true).prop('readOnly', true);
		$('#telefono').prop('disabled', true).prop('readOnly', true);
		$('#correo').prop('disabled', true).prop('readOnly', true);
		$('#cargo').prop('disabled', true);
		$('#dependencia').prop('disabled', true);
		$('#unidad').prop('disabled', true);
		$('#nombre_usuario').prop('disabled', true).prop('readOnly', true);
		$('#rol').prop('disabled', true);
	}, 100);

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {
			case "Registrar":
				if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosUsuario)) {
					if (validarClaves()) {
						confirmacion = await confirmarAccion("Se registrará un Usuario", "¿Está seguro de realizar la acción?", "question");
						if (confirmacion) {
							var datos = new FormData();
							datos.append('registrar', 'registrar');
							datos.append('nombre_usuario', $("#nombre_usuario").val());
							datos.append('particle', $("#particle").val());
							datos.append('cargo', $("#cargo").val());
							datos.append('unidad', $("#unidad").val());
							
							// CORRECCIÓN: Aplicar capitalización completa a nombre y apellido
							const nombre = capitalizarTextoCompleto($("#nombre").val());
							const apellido = capitalizarTextoCompleto($("#apellido").val());
							
							datos.append('cedula', $("#particle").val() + $("#cedula").val());
							datos.append('nombre', nombre);
							datos.append('apellido', apellido);
							datos.append('telefono', $("#telefono").val());
							datos.append('correo', $("#correo").val());
							datos.append('clave', $("#clave").val());
							datos.append('rclave', $("#rclave").val());
							datos.append('rol', $("#rol").val());
							enviaAjax(datos);
							envio = true;
						}
					}
				} else {
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
				}
				break;
			case "Modificar":
				if (typeof SistemaValidacion !== 'undefined' && SistemaValidacion.validarFormulario(elementosUsuario)) {
					var datos = new FormData();
					var bool_clave
					if (arraySesion.rol == "SUPERUSUARIO") {
						bool_clave = validarClaves();
						datos.append('bool_clave', true);
						datos.append('clave', $("#clave").val());
						datos.append('rclave', $("#rclave").val());
					} else {
						datos.append('bool_clave', false);
						bool_clave = true
					}
					if (bool_clave) {
						confirmacion = await confirmarAccion("Se modificará un Usuario", "¿Está seguro de realizar la acción?", "question");
						if (confirmacion) {
							// CORRECCIÓN: Aplicar capitalización completa a nombre y apellido
							const nombre = capitalizarTextoCompleto($("#nombre").val());
							const apellido = capitalizarTextoCompleto($("#apellido").val());
							
							datos.append('modificar', 'modificar');
							datos.append('nombre_usuario', $("#nombre_usuario").val());
							datos.append('cedula', $("#particle").val() + $("#cedula").val());
							datos.append('particle', $("#particle").val());
							datos.append('cargo', $("#cargo").val());
							datos.append('unidad', $("#unidad").val());
							datos.append('nombre', nombre);
							datos.append('apellido', apellido);
							datos.append('telefono', $("#telefono").val());
							datos.append('correo', $("#correo").val());
							datos.append('rol', $("#rol").val());
							enviaAjax(datos);
							envio = true;
						}
					}
				} else {
					mensajes("error", 10000, "Error de Validación", "Por favor corrija los errores en el formulario antes de enviar.");
				}
				break;
			case "Eliminar":
				// CORRECCIÓN: El botón eliminar siempre está habilitado
				confirmacion = await confirmarAccion("Se elimanrá un Usuario", "¿Está seguro de realizar la acción?", "question");
				if (confirmacion) {
					var datos = new FormData();
					datos.append('eliminar', 'eliminar');
					datos.append('cedula', $("#particle").val() + $("#cedula").val());
					enviaAjax(datos);
					envio = true;
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
		$("#Fila5").removeClass("d-none")
		$("#modalTitleId").text("Registrar Usuario");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");

		// Deshabilitar botón inicialmente
		$('#enviar').prop('disabled', true);

		// Limpiar validación visual al abrir el modal
		setTimeout(() => {
			limpiarValidacionVisual();

			// Deshabilitar campos progresivos al abrir modal
			$('#cedula').prop('disabled', true).prop('readOnly', true);
			$('#nombre').prop('disabled', true).prop('readOnly', true);
			$('#apellido').prop('disabled', true).prop('readOnly', true);
			$('#telefono').prop('disabled', true).prop('readOnly', true);
			$('#correo').prop('disabled', true).prop('readOnly', true);
			$('#cargo').prop('disabled', true);
			$('#dependencia').prop('disabled', true);
			$('#unidad').prop('disabled', true);
			$('#nombre_usuario').prop('disabled', true).prop('readOnly', true);
			$('#rol').prop('disabled', true);
		}, 100);
	}); //<----Fin Evento del Boton Registrar

	// Limpiar campos al cerrar el modal
	$('#modal1').on('hidden.bs.modal', function () {
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.limpiarValidacion(elementosUsuario);
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
				$.each(elementosUsuario, function (key, elemento) {
					if (elemento && elemento.length) {
						elemento.data('touched', true);
					}
				});

				// Validar formulario completo (mostrará errores visuales)
				if (typeof SistemaValidacion !== 'undefined') {
					SistemaValidacion.validarFormulario(elementosUsuario);
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
					$('#nombre_usuario').prop('disabled', false).prop('readOnly', false);
					$('#rol').prop('disabled', false);
				}
			}
		}, 100);
	});
});

function cargarRol() {
	var datos = new FormData();
	datos.append('cargar_rol', 'cargar_rol');
	enviaAjax(datos);
};

function cargarCargo() {
	var datos = new FormData();
	datos.append('cargar_cargo', 'cargar_cargo');
	enviaAjax(datos);
};

function cargarEnte() {
	var datos = new FormData();
	datos.append('cargar_ente', 'cargar_ente');
	enviaAjax(datos);
};

async function cargarDependencia(id) {
	var datos = new FormData();
	datos.append('id_ente', id);
	datos.append('cargar_dependencia', 'cargar_dependencia');
	return await enviaAjax(datos);
};

async function cargarUnidad(id) {
	var datos = new FormData();
	datos.append('id_dependencia', id);
	datos.append('cargar_unidad', 'cargar_unidad');
	return await enviaAjax(datos);
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

				} else if (lee.resultado == "cargar_rol") {
					selectRol(lee.datos);

				} else if (lee.resultado == "cargar_cargo") {
					selectCargo(lee.datos);

				} else if (lee.resultado == "cargar_ente") {
					selectEnte(lee.datos);

				} else if (lee.resultado == "cargar_dependencia") {
					selectDependencia(lee.datos);

				} else if (lee.resultado == "cargar_unidad") {
					selectUnidad(lee.datos);

				} else if (lee.resultado == "entrada") {

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
	// Validación para particle (nacionalidad) - habilita cédula
	$("#particle").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);

			// Habilitar/deshabilitar cédula según validación
			habilitarCampoProgresivo(this, '#cedula');
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

			// Habilitar/deshabilitar nombre según validación
			habilitarCampoProgresivo(this, '#nombre');
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

			// Habilitar/deshabilitar apellido según validación
			habilitarCampoProgresivo(this, '#apellido');
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

			// Habilitar/deshabilitar teléfono según validación
			habilitarCampoProgresivo(this, '#telefono');
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

	// Validación con formato en tiempo real para teléfono (solo números y guion) - habilita correo
	$("#telefono").on("keypress", function (e) {
		validarKeyPress(/^[0-9\b-]*$/, e);
	});

	// Formato automático CORREGIDO para teléfono (0412-1234567) - habilita correo
	$("#telefono").on("input", function () {
		formatearTelefonoConGuion($(this));
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);

			// Habilitar/deshabilitar correo según validación
			habilitarCampoProgresivo(this, '#correo');
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

			// Habilitar/deshabilitar cargo según validación
			habilitarCampoProgresivo(this, '#cargo');
		}
	});

	// Validación para cargo - habilita ente
	$("#cargo").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);

			// Habilitar/deshabilitar ente según validación
			habilitarCampoProgresivo(this, '#ente');
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

	// Validación para unidad - habilita nombre_usuario
	$("#unidad").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);

			// Habilitar/deshabilitar nombre_usuario según validación
			habilitarCampoProgresivo(this, '#nombre_usuario');
		}
	});

	// Validación para nombre_usuario - habilita rol
	$("#nombre_usuario").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ_\b]*$/, e);
	});

	$("#nombre_usuario").on("keyup", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);

			// Habilitar/deshabilitar rol según validación
			habilitarCampoProgresivo(this, '#rol');
		}
	});

	// Validación para rol - habilita campos de contraseña
	$("#rol").on("change", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			$(this).data('touched', true);
			SistemaValidacion.validarCampo.call(this);

			// Habilitar campos de contraseña si el rol es válido
			if ($(this).hasClass('is-valid')) {
				$('#clave').prop('disabled', false).prop('readOnly', false);
				$('#rclave').prop('disabled', false).prop('readOnly', false);
			} else {
				$('#clave').prop('disabled', true).prop('readOnly', true);
				$('#rclave').prop('disabled', true).prop('readOnly', true);
			}
		}
	});

	// Validación para clave
	$("#clave").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,\b]*$/, e);
	});

	$("#clave").on("keyup", function () {
		if (typeof SistemaValidacion !== 'undefined') {
			SistemaValidacion.validarCampo.call(this);
		}
	});

	// Validación para rclave
	$("#rclave").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,\b]*$/, e);
	});

	$("#rclave").on("keyup", function () {
		if (typeof SistemaValidacion !== 'undefined') {
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

// AGREGAR PATRONES ESPECÍFICOS PARA USUARIO AL OBJETO PATRONES EXISTENTE
if (typeof patrones !== 'undefined') {
	patrones.cedulaNumeros = /^[0-9]{7,10}$/;
	patrones.nombrePersona = /^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{4,45}$/;
	patrones.apellidoPersona = /^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{4,45}$/;
	patrones.correoUsuario = /^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/;
	patrones.telefonoUsuario = /^[0-9]{4}[-]{1}[0-9]{7}$/;
	patrones.nombreUsuario = /^[0-9 a-zA-ZáéíóúüñÑçÇ_]{4,45}$/;
	patrones.claveUsuario = /^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,]{8,45}$/;
}

// Extender SistemaValidacion para campos de usuario
if (typeof SistemaValidacion !== 'undefined') {
	// Guardar la función original de validarCampo
	const originalValidarCampo = SistemaValidacion.validarCampo;

	// Sobrescribir la función para incluir validaciones específicas de usuario
	SistemaValidacion.validarCampo = function () {
		const $campo = $(this);
		const valor = $campo.val() ? $campo.val().trim() : '';
		const id = this.id;
		let esValido = true;
		let mensajeError = '';

		// Solo mostrar errores si el campo ya fue interactuado
		const fueInteractuado = $campo.data('touched') || $campo.is(':focus');

		// Validaciones específicas para campos de usuario
		switch (id) {
			case 'cedula':
				esValido = patrones.cedulaNumeros.test(valor);
				mensajeError = 'La cédula debe tener entre 7 y 10 dígitos numéricos';
				break;

			case 'nombre':
				esValido = patrones.nombrePersona.test(valor);
				mensajeError = 'El nombre debe tener entre 4 y 45 caracteres (solo letras y espacios)';
				break;

			case 'apellido':
				esValido = patrones.apellidoPersona.test(valor);
				mensajeError = 'El apellido debe tener entre 4 y 45 caracteres (solo letras y espacios)';
				break;

			case 'telefono':
				esValido = patrones.telefonoUsuario.test(valor);
				mensajeError = 'El teléfono debe tener formato 0412-1234567';
				break;

			case 'correo':
				esValido = patrones.correoUsuario.test(valor);
				mensajeError = 'El formato del correo electrónico es: usuario@servidor.com';
				break;

			case 'nombre_usuario':
				esValido = patrones.nombreUsuario.test(valor);
				mensajeError = 'El nombre de usuario debe tener entre 4 y 45 caracteres (letras, números y guiones bajos)';
				break;

			case 'clave':
				esValido = patrones.claveUsuario.test(valor);
				mensajeError = 'La clave debe tener entre 8 y 45 caracteres';
				break;

			case 'rclave':
				esValido = valor === $("#clave").val() && patrones.claveUsuario.test($("#clave").val());
				mensajeError = 'Las contraseñas no coinciden';
				break;

			case 'particle':
			case 'cargo':
			case 'ente':
			case 'dependencia':
			case 'unidad':
			case 'rol':
				esValido = valor !== "default" && valor !== "" && valor !== null;
				mensajeError = 'Debe seleccionar una opción válida';
				break;

			default:
				// Si no es un campo específico de usuario, usar la validación original
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

function validarClaves() {
	if ($("#clave").val() == $("#rclave").val()) {
		return true;
	} else {
		mensajes("error", 10000, "Error", "Las contraseñas no coinciden");
		return false;
	}
}

function consultar() {
	var datos = new FormData();
	datos.append('consultar', 'consultar');
	enviaAjax(datos);
}

function crearDataTable(datos) {
	$("#tabla").DataTable({
		destroy: true,
		responsive: true,
		language: {
			url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json",
		},
		data: datos,
		columns: [
			{ data: "cedula" },
			{ data: "nombre" },
			{ data: "apellido" },
			{ data: "telefono" },
			{ data: "correo" },
			{ data: "cargo" },
			{ data: "unidad" },
			{ data: "nombre_usuario" },
			{ data: "rol" },
			{
				data: null,
				render: function (data, type, row) {
					return `<button type="button" class="btn btn-warning" onclick="modificar('${row.cedula}')">
								<i class="fa-solid fa-pen-to-square"></i>
							</button>
							<button type="button" class="btn btn-danger" onclick="eliminar('${row.cedula}')">
								<i class="fa-solid fa-trash"></i>
							</button>`;
				},
			},
		],
	});
}

function modificar(cedula) {
	var datos = new FormData();
	datos.append('modificar_entrada', 'modificar_entrada');
	datos.append('cedula', cedula);
	enviaAjax(datos);
}

function eliminar(cedula) {
	var datos = new FormData();
	datos.append('eliminar_entrada', 'eliminar_entrada');
	datos.append('cedula', cedula);
	enviaAjax(datos);
}

function registrarEntrada() {
	var datos = new FormData();
	datos.append('registrar_entrada', 'registrar_entrada');
	enviaAjax(datos);
}

function selectRol(datos) {
	$("#rol").empty();
	$("#rol").append(`<option value="default" selected>Seleccione un rol</option>`);
	datos.forEach(element => {
		$("#rol").append(`<option value="${element.id_rol}">${element.nombre_rol}</option>`);
	});
}

function selectCargo(datos) {
	$("#cargo").empty();
	$("#cargo").append(`<option value="default" selected>Seleccione un cargo</option>`);
	datos.forEach(element => {
		$("#cargo").append(`<option value="${element.id_cargo}">${element.nombre_cargo}</option>`);
	});
}

function selectEnte(datos) {
	$("#ente").empty();
	$("#ente").append(`<option value="default" selected>Seleccione un ente</option>`);
	datos.forEach(element => {
		$("#ente").append(`<option value="${element.id}">${element.nombre}</option>`);
	});
}

function selectDependencia(datos) {
	$("#dependencia").empty();
	$("#dependencia").append(`<option value="default" selected>Seleccione una dependencia</option>`);
	datos.forEach(element => {
		$("#dependencia").append(`<option value="${element.id}">${element.nombre}</option>`);
	});
}

function selectUnidad(datos) {
	$("#unidad").empty();
	$("#unidad").append(`<option value="default" selected>Seleccione una unidad</option>`);
	datos.forEach(element => {
		$("#unidad").append(`<option value="${element.id_unidad}">${element.nombre_unidad}</option>`);
	});
}

function limpia() {
	$("#particle").val("V-");
	$("#cedula").val("");
	$("#nombre").val("");
	$("#apellido").val("");
	$("#telefono").val("");
	$("#correo").val("");
	$("#cargo").val("default");
	$("#ente").val("default");
	$("#dependencia").val("default");
	$("#unidad").val("default");
	$("#nombre_usuario").val("");
	$("#rol").val("default");
	$("#clave").val("");
	$("#rclave").val("");
}