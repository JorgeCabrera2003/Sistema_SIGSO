// ...copia y adapta la lógica de empleado.js para el módulo técnico...
// Cambia los textos y endpoints según corresponda para técnicos.
// Por ejemplo, cambia "Empleado" por "Técnico" en los mensajes y títulos.
// Cambia los nombres de variables si es necesario.
// El resto de la lógica (validaciones, AJAX, etc.) puede ser muy similar.

$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarEnte()
	cargarDependencia();
	cargarServicio(); // <--- Cargar áreas al iniciar

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;
		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion('Se registrará un nuevo Técnico', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('cedula', $("#cedula").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('apellido', $("#apellido").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('correo', $("#correo").val());
						datos.append('unidad', $("#unidad").val());
						datos.append('cargo', $("#cargo").val());
						datos.append('servicio', $("#servicio").val()); // <-- Agrega el área
						if ($("#check_user").prop('checked')) {
							datos.append('check_usuario', 1);
						} else {
							datos.append('check_usuario', 0);
						}
						enviaAjax(datos);
					}
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion('Se modificará Técnico', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('cedula', $("#cedula").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('apellido', $("#apellido").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('correo', $("#correo").val());
						datos.append('unidad', $("#unidad").val());
						datos.append('cargo', $("#cargo").val());
						datos.append('servicio', $("#servicio").val()); // <-- Agrega el área
						enviaAjax(datos);
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{7,10}$/, $("#cedula"), $("#scedula"), "") == 1) {
					confirmacion = await confirmarAccion('Se eliminará Técnico', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('cedula', $("#cedula").val());
						enviaAjax(datos);
					}
				}
				break;

			default:
				mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());
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
		$("#idTecnico").remove();
		$("#modalTitleId").text("Registrar Técnico");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	});
});

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

function cargarCargo() {
	var datos = new FormData();
	datos.append('cargar_cargo', 'cargar_cargo')
	enviaAjax(datos);
};

function cargarServicio() {
	var datos = new FormData();
	datos.append('cargar_servicio', 'cargar_servicio');
	enviaAjax(datos);
}

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

				} else if (lee.resultado == "cargar_dependencia") {
					selectDependencia(lee.datos);

				} else if (lee.resultado == "cargar_unidad") {
					selectUnidad(lee.datos);

				} else if (lee.resultado == "cargar_cargo") {
					selectCargo(lee.datos);

				} else if (lee.resultado == "cargar_servicio") {
					selectServicio(lee.datos);
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

function selectCargo(arreglo) {
	$("#cargo").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#cargo").append(
			new Option('Seleccione un Cargo', 'default')
		);
		arreglo.forEach(item => {
			$("#cargo").append(
				new Option(item.cargo, item.id)
			);
		});
	} else {
		$("#cargo").append(
			new Option('No Hay Cargos', 'default')
		);
	}
}

function selectEnte(arreglo) {
	$("#ente").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#ente").attr('disabled', false);

		$("#ente").append(
			new Option('Seleccione un Ente', 'default')
		);
		arreglo.forEach(item => {
			$("#ente").append(
				new Option(item.nombre_ente, item.id_ente)
			);
		});
	} else {
		$("#ente").append(
			new Option('No Hay Entes', 'default')
		);
		$("#ente").attr('disabled', false);
	}
}

function selectDependencia(arreglo) {
	$("#dependencia").empty();

	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#dependencia").attr('disabled', false);
		$("#dependencia").append(
			new Option('Seleccione una Dependencia', 'default')
		);
		arreglo.forEach(item => {
			$("#dependencia").append(
				new Option(item.nombre_dependencia, item.id_dependencia)
			);
		});
	} else {
		$("#dependencia").append(
			new Option('No Hay Dependencias', 'default')
		);
		$("#dependencia").attr('disabled', true);
	}
}

function selectUnidad(arreglo) {
	$("#unidad").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#unidad").attr('disabled', false);
		$("#unidad").append(
			new Option('Seleccione una Unidad', 'default')
		);
		arreglo.forEach(item => {
			$("#unidad").append(
				new Option(item.nombre_unidad, item.id_unidad)
			);
		});
	} else {
		$("#unidad").append(
			new Option('No Hay Unidades', 'default')
		);
		$("#unidad").attr('disabled', true);
	}
}

function selectServicio(arreglo) {
	$("#servicio").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#servicio").append(
			new Option('Seleccione un Área de Servicio', 'default')
		);
		arreglo.forEach(item => {
			$("#servicio").append(
				new Option(item.nombre_tipo_servicio, item.id_tipo_servicio)
			);
		});
	} else {
		$("#servicio").append(
			new Option('No hay áreas', 'default')
		);
	}
}

function capaValidar() {
	$("#cedula").on("keypress", function (e) {
		validarKeyPress(/^[0-9\B]*$/, e);
	});

	$("#cedula").on("keyup", function () {
		if (validarKeyUp(
			/^[0-9]{7,10}$/, $(this), $("#scedula"),
			"Cédula no válida, el formato es: 00000000"
		)) {
			$(this).attr("placeholder", "")
		} else {
			$(this).attr("placeholder", "00000000")
		}
	});

	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ \b]*$/, e);
	});
	$("#nombre").on("keyup", function () {
		validarKeyUp(
			/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{4,45}$/, $(this), $("#snombre"),
			"El nombre debe tener de 4 a 45 carácteres"
		);
	});

	$("#apellido").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ\b]*$/, e);
	});
	$("#apellido").on("keyup", function () {
		validarKeyUp(
			/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{4,45}$/, $(this), $("#sapellido"),
			"El apellido debe tener de 4 a 45 carácteres"
		);
	});

	$("#correo").on("keypress", function (e) {
		validarKeyPress(/^[-0-9a-z_.@\b]*$/, e);
	});
	$("#correo").on("keyup", function () {
		validarKeyUp(
			/^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/, $(this), $("#scorreo"),
			"El formato del correo electrónico es: usuario@servidor.com"
		);
	});

	$("#telefono").on("keypress", function (e) {
		validarKeyPress(/^[-0-9\b]*$/, e);
	});
	$("#telefono").on("keyup", function () {
		validarKeyUp(
			/^[0-9]{4}[-]{1}[0-9]{7}$/, $(this), $("#stelefono"),
			"El numero de teléfono debe tener el siguiente formato: ****-*******"
		);
	});

	$("#cargo").on("change", function () {
		if ($(this).val() == "default") {
			estadoSelect(this, "#scargo", "Debe seleccionar un cargo", 0);
		} else {
			estadoSelect(this, "#scargo", "", 1);
		}
	});

	$("#ente").on("change", function () {
		if ($(this).val() == "default") {
			estadoSelect(this, "#sdependencia", "Debe seleccionar un Ente", 0);
			$("#dependencia").empty();
			$("#unidad").empty();
			$("#dependencia").attr("disabled", true);
			$("#unidad").attr("disabled", true);
		} else {
			estadoSelect(this, "sid_dependencia", "", 1);
			cargarDependencia($(this).val());
		}
	})

	$("#dependencia").on("change", function () {
		if ($(this).val() == "default" || $(this).val() == null) {
			estadoSelect(this, "#sdependencia", "Debe seleccionar una Dependencia", 0);
			$("#unidad").empty();
			$("#unidad").attr("disabled", true);
		} else {
			estadoSelect(this, "sid_dependencia", "", 1);
			cargarUnidad($(this).val()); // <-- Esto carga el modal de unidad según la dependencia seleccionada
		}
	})
	$("#unidad").on("change", function () {
		if ($(this).val() == "default") {
			estadoSelect(this, "#sunidad", "Debe seleccionar una Unidad", 0);

		} else {
			estadoSelect(this, "#sunidad", "", 1);
		}
	})
}

function validarenvio() {
	if (validarKeyUp(/^[0-9]{7,10}$/, $("#cedula"), $("#scedula"), "") == 0) {
		mensajes("error", 10000, "Verifica", "Cédula no válida, el formato es: **********");
		return false;

	} else if (validarKeyUp(/^[a-z A-ZÁÉÍÓÚáéíóúüñÑçÇ]{4,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del técnico debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[a-z A-ZÁÉÍÓÚáéíóúüñÑçÇ]{4,45}$/, $("#apellido"), $("#sapellido"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El apellido debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[0-9]{4}[-]{1}[0-9]{7}$/, $("#telefono"), $("#stelefono"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El numero de teléfono debe tener el siguiente formato: ****-*******");
		return false;

	} else if (validarKeyUp(/^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/, $("#correo"), $("#scorreo"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El formato del correo electrónico es: usuario@servidor.com");
		return false;

	} else if ($("#ente").val() == "default") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un Ente");
		return false;

	} else if ($("#dependencia").val() == "default" || $("#dependencia").val() == null) {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un Dependencia");
		return false;

	} else if ($("#unidad").val() == "default" || $("#unidad").val() == null) {
		mensajes("error", 10000, "Verifica", "Debe seleccionar una Unidad");
		return false;
		
	} else if ($("#cargo").val() == "default") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un cargo");
		return false;
	}
	return true;
}

function vistaPermiso(permisos = null) {
	if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {
		$('.modificar').remove();
		$('.eliminar').remove();
		$('.restaurar').remove();
	} else {
		if (permisos['tecnico']['modificar']['estado'] == '0') {
			$('.modificar').remove();
		}
		if (permisos['tecnico']['eliminar']['estado'] == '0') {
			$('.eliminar').remove();
		}
		if (permisos['tecnico']['restaurar']['estado'] == '0') {
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
			{ data: 'cedula' },
			{ data: 'nombre' },
			{ data: 'apellido' },
			{ data: 'telefono' },
			{ data: 'correo' },
			{ data: 'dependencia' },
			{ data: 'unidad' },
			{ data: 'cargo' },
			{ data: 'servicio' },
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
}

function limpia() {
	$("#cedula").removeClass("is-valid is-invalid").val("").prop('readOnly', false);
	$("#scedula").text("");

	$("#nombre").removeClass("is-valid is-invalid").val("").prop('readOnly', false);
	$("#snombre").text("");

	$("#apellido").removeClass("is-valid is-invalid").val("").prop('readOnly', false);
	$("#sapellido").text("");

	$("#correo").removeClass("is-valid is-invalid").val("").prop('readOnly', false);
	$("#scorreo").text("");

	$("#telefono").removeClass("is-valid is-invalid").val("").prop('readOnly', false);
	$("#stelefono").text("");

	$("#ente").val('default').change().removeClass("is-valid is-invalid").prop('disabled', false);
	$("#sente").text("");

	$("#dependencia").val('default').change().removeClass("is-valid is-invalid").prop('disabled', true).empty();
	$("#sdependencia").text("");

	$("#unidad").val('default').change().removeClass("is-valid is-invalid").prop('disabled', true).empty();
	$("#sunidad").text("");

	$("#cargo").val('default').change().removeClass("is-valid is-invalid").prop('disabled', false);
	$("#scargo").text("");

	$('#enviar').val('default').change();
}

async function rellenar(pos, accion) {
	limpia();
	linea = $(pos).closest('tr');
	var info_empleado = null;
	var cedula_completa = $(linea).find("td:eq(0)").text()
	var cedula = cedula_completa.substring(2);
	var letra_ci = cedula_completa.substring(0, 2);

	datos = new FormData();
	datos.append("buscar_usuario", null);
	datos.append("cedula", cedula_completa);
	info_empleado = await enviaAjax(datos);
	info_empleado = JSON.parse(info_empleado);

	if (info_empleado != null) {
		if (info_empleado.unidad != null && info_empleado.dependencia && info_empleado.ente) {
			buscarSelect('#ente', info_empleado.ente.arreglo.id, 'value');
			await cargarDependencia(info_empleado.ente.arreglo.id);
			buscarSelect('#dependencia', info_empleado.dependencia.arreglo.id, 'value');
			await cargarUnidad(info_empleado.dependencia.arreglo.id);
			buscarSelect('#unidad', info_empleado.unidad.arreglo.id_unidad, 'value');
		}
		buscarSelect('#cargo', info_empleado.empleado.arreglo.id_cargo, 'value');
	}

	buscarSelect('#particle', letra_ci, 'value');
	$("#cedula").val(cedula);
	$("#nombre").val($(linea).find("td:eq(1)").text());
	$("#apellido").val($(linea).find("td:eq(2)").text());
	$("#telefono").val($(linea).find("td:eq(3)").text());
	$("#correo").val($(linea).find("td:eq(4)").text());

	buscarSelect('#cargo', $(linea).find("td:eq(7)").text(), 'text');

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Técnico")
		$("#enviar").text("Modificar");
	}
	else {
		$("#cedula").prop('readOnly', true);
		$("#nombre").prop('readOnly', true);
		$("#apellido").prop('readOnly', true);
		$("#telefono").prop('readOnly', true);
		$("#correo").prop('readOnly', true);
		$("#particle").prop('disabled', true);
		$("#dependencia").prop('disabled', true).removeClass("is-valid is-invalid");
		$("#ente").prop('disabled', true).removeClass("is-valid is-invalid");
		$("#unidad").prop('disabled', true).removeClass("is-valid is-invalid");
		$("#cargo").prop('disabled', true).removeClass("is-valid is-invalid");
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function consultar() {
	var datos = new FormData();
	datos.append('consultar', 'consultar');
	enviaAjax(datos);
}

function registrarEntrada() {
	var datos = new FormData();
	datos.append('entrada', 'entrada');
	enviaAjax(datos);
}
