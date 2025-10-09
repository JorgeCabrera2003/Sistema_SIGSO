$(document).ready(function () {

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
	consultar();
	registrarEntrada();
	capaValidar();
	cargarRol();
	cargarCargo();
	cargarEnte();

	$("#enviar").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará un Usuario", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('nombre_usuario', $("#nombre_usuario").val());
						datos.append('particle', $("#particle").val());
						datos.append('cargo', $("#cargo").val());
						datos.append('unidad', $("#unidad").val());
						datos.append('cedula', $("#cedula").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('apellido', $("#apellido").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('correo', $("#correo").val());
						datos.append('clave', $("#clave").val());
						datos.append('rclave', $("#rclave").val());
						datos.append('rol', $("#rol").val());
						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará un Usuario", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('nombre_usuario', $("#nombre_usuario").val());
						datos.append('cedula', $("#cedula").val());
						datos.append('particle', $("#particle").val());
						datos.append('cargo', $("#cargo").val());
						datos.append('unidad', $("#unidad").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('apellido', $("#apellido").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('correo', $("#correo").val());
						datos.append('clave', $("#clave").val());
						datos.append('rclave', $("#rclave").val());
						datos.append('rol', $("#rol").val());
						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(
					/^[VE]{1}[-]{1}[0-9]{7,10}$/, $("#cedula"), $("#scedula"),
					"Cédula no válida, el formato es: V-**********"
				)) {
					confirmacion = await confirmarAccion("Se elimanrá un Usuario", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('cedula', $("#cedula").val());
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

	$("#btn-registrar").on("click", function () {
		limpia();
		$("#modalTitleId").text("Registrar Usuario");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar
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

function cargarDependencia(id) {
	var datos = new FormData();
	datos.append('id_ente', id);
	datos.append('cargar_dependencia', 'cargar_dependencia');
	enviaAjax(datos);
};

function cargarUnidad(id) {
	var datos = new FormData();
	datos.append('id_dependencia', id);
	datos.append('cargar_unidad', 'cargar_unidad');
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

	$("#nombre_usuario").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ_\b]*$/, e);
	});
	$("#nombre_usuario").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ_]{4,45}$/, $(this), $("#snombre_usuario"),
			"El nombre de usuario debe tener de 4 a 45 carácteres"
		);
	});

	$("#cedula").on("keypress", function (e) {
		validarKeyPress(/^[-0-9VE\B]*$/, e);
	});
	$("#cedula").on("keyup", function () {
		validarKeyUp(
			/^[V]{1}[-]{1}[0-9]{7,10}$/, $(this), $("#scedula"),
			"Cédula no válida, el formato es: V-**********"
		);
	});

	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ\b]*$/, e);
	});
	$("#nombre").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/, $(this), $("#snombre"),
			"El nombre debe tener de 4 a 45 carácteres"
		);
	});

	$("#apellido").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ\b]*$/, e);
	});
	$("#apellido").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/, $(this), $("#sapellido"),
			"El apellido debe tener de 4 a 45 carácteres"
		);
	});

	$("#correo").on("keypress", function (e) {
		validarKeyPress(/^[-0-9a-z_.@\b]*$/, e);
	});
	$("#correo").on("keyup", function () {
		validarKeyUp(/^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/, $(this), $("#scorreo"),
			"El formato del correo electrónico es: usuario@servidor.com"
		);
	});

	$("#telefono").on("keypress", function (e) {
		validarKeyPress(/^[-0-9\b]*$/, e);
	});
	$("#telefono").on("keyup", function () {
		validarKeyUp(/^[0-9]{4}[-]{1}[0-9]{7}$/, $(this), $("#stelefono"),
			"El numero de teléfono debe tener el siguiente formato: ****-*******"
		);
	});

	$("#rol").on("change", function () {
		if ($(this).val() == "default") {
			estadoSelect(this, "#srol", "Debe seleccionar un rol", 0);
		} else {
			estadoSelect(this, "#srol", "", 1);
		}
	});

	$("#clave").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,\b]*$/, e);
	});
	$("#clave").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,]{8,45}$/, $(this), $("#sclave"),
			"La clave debe tener mínimo 8 caracteres"
		);
	});

	$("#rclave").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,\b]*$/, e);
	});

	$("#rclave").on("keyup", function () {

		if ($(this).val() == $("#clave").val()) {

			$(this).addClass("is-valid");
			$(this).removeClass("is-invalid");
			$("#srclave").text("");
			$("#srclave").addClass("valid-feedback");
			$("#srclave").removeClass("invalid-feedback");

		} else {

			$(this).addClass("is-invalid");
			$(this).removeClass("is-valid");
			$("#srclave").text('La clave no coincide');
			$("#srclave").removeClass("valid-feedback");
			$("#srclave").addClass("invalid-feedback");
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
		if ($(this).val() == "default") {
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

	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ_]{4,45}$/, $("#nombre_usuario"), $("#snombre_usuario"),
		"") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre de usuario debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(
		/^[V]{1}[-]{1}[0-9]{7,10}$/, $("#cedula"), $("#scedula"),
		"") == 0) {
		mensajes("error", 10000, "Verifica", "Cédula no válida, el formato es: V-**********");
		return false;

	} else if (validarKeyUp(
		/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/, $("#nombre"), $("#snombre"),
		"") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,45}$/, $("#apellido"), $("#sapellido"),
		"") == 0) {
		mensajes("error", 10000, "Verifica", "El apellido debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[-0-9A-Za-zç_]{6,36}[@]{1}[0-9a-zA-Z]{5,25}[.]{1}[com]{3}$/, $("#correo"), $("#scorreo"),
		"") == 0) {
		mensajes("error", 10000, "Verifica", "El formato del correo electrónico es: usuario@servidor.com");
		return false;

	} else if (validarKeyUp(/^[0-9]{4}[-]{1}[0-9]{7}$/, $("#telefono"), $("#stelefono"),
		"") == 0) {
		mensajes("error", 10000, "Verifica", "El numero de teléfono debe tener el siguiente formato: ****-*******");
		return false;

	} else if ($("#rol").val() == "default") {
		mensajes("error", 10000, "Verifica", "Debe seleccionar un rol");
		return false;

	} else if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ_*+.,]{8,45}$/, $("#clave"), $("#sclave"),
		"") == 0) {
		mensajes("error", 10000, "Verifica", "La clave debe tener mínimo 8 caracteres");
		return false;

	} else if ($("#rclave").val() != $("#clave").val()) {
		mensajes("error", 10000, "Verifica", "La clave no coincide");
		return false;

	}
	return true;
}

function selectRol(arreglo) {
	$("#rol").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {

		$("#rol").append(
			new Option('Seleccione un rol', 'default')
		);
		arreglo.forEach(item => {
			$("#rol").append(
				new Option(item.nombre_rol, item.id_rol)
			);
		});
	} else {
		$("#rol").append(
			new Option('No Hay Cargos', 'default')
		);
	}
}

function selectCargo(arreglo) {
	$("#cargo").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#cargo").attr('disabled', false);
		$("#cargo").append(
			new Option('Seleccione un Cargo', 'default')
		);
		arreglo.forEach(item => {
			$("#cargo").append(
				new Option(item.nombre_cargo, item.id_cargo)
			);
		});
	} else {
		$("#cargo").append(
			new Option('No Hay Cargos', 'default')
		);

		$("#cargo").attr('disabled', true);
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

function crearDataTable(arreglo) {

	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}

	$('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{ data: 'nombre_usuario' },
			{ data: 'rol' },
			{ data: 'cedula' },
			{ data: 'nombres' },
			{ data: 'apellidos' },
			{ data: 'telefono' },
			{ data: 'correo' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" title="Modificar" class="btn btn-update"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" title="Eliminar" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>`;
					return botones;
				}
			}
		],
		language: {
			url: idiomaTabla,
		}
	});

}

function limpia() {
	$("#nombre_usuario").removeClass("is-valid is-invalid");
	$("#nombre_usuario").val("");
	$("#snombre_usuario").text("");

	$("#cedula").removeClass("is-valid is-invalid");
	$("#cedula").val("");
	$("#scedula").text("");

	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");
	$("#snombre").text("");

	$("#apellido").removeClass("is-valid is-invalid");
	$("#apellido").val("");
	$("#sapellido").text("");

	$("#correo").removeClass("is-valid is-invalid");
	$("#correo").val("");
	$("#scorreo").text("");

	$("#telefono").removeClass("is-valid is-invalid");
	$("#telefono").val("");
	$("#stelefono").text("");

	$("#clave").removeClass("is-valid is-invalid");
	$("#clave").val("");
	$("#sclave").text("");

	$("#rclave").removeClass("is-valid is-invalid");
	$("#rclave").val("");
	$("#srclave").text("");

}
async function rellenar(pos, accion) {
	limpia();
	linea = $(pos).closest('tr');
	$("#nombre_usuario").val($(linea).find("td:eq(0)").text());
	buscarSelect('#rol', $(linea).find("td:eq(1)").text(), 'text');
	$("#cedula").val($(linea).find("td:eq(2)").text());
	$("#nombre").val($(linea).find("td:eq(3)").text());
	$("#apellido").val($(linea).find("td:eq(4)").text());
	$("#telefono").val($(linea).find("td:eq(5)").text());
	$("#correo").val($(linea).find("td:eq(6)").text());

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Usuario")
		$("#enviar").text("Modificar");
	}
	else {
		$("#modalTitleId").text("Eliminar Usuario")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}