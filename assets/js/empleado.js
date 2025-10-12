$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarCargo();
	cargarEnte()

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;
		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion('Se registrará un nuevo Empleado', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('particle', $("#particle").val());
						datos.append('cedula', $("#cedula").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('apellido', $("#apellido").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('correo', $("#correo").val());
						datos.append('unidad', $("#unidad").val());
						datos.append('cargo', $("#cargo").val());

						enviaAjax(datos);
					}
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion('Se modificará Empleado', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('particle', $("#particle").val());
						datos.append('cedula', $("#cedula").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('apellido', $("#apellido").val());
						datos.append('telefono', $("#telefono").val());
						datos.append('correo', $("#correo").val());
						datos.append('unidad', $("#unidad").val());
						datos.append('cargo', $("#cargo").val());
						enviaAjax(datos);
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{7,10}$/, $("#cedula"), $("#scedula"), "") == 1) {
					confirmacion = await confirmarAccion('Se elminará Empleado', '¿Seguro de realizar la acción?', 'question');
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('particle', $("#particle").val());
						datos.append('cedula', $("#cedula").val());
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

	$("#btn-registrar").on("click", function () { //<---- Evento del Boton Registrar
		limpia();

		$("#modalTitleId").text("Registrar Empleado");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar
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

				} else if (lee.resultado == "cargar_ente") {
					selectEnte(lee.datos);

				} else if (lee.resultado == "cargar_dependencia") {
					selectDependencia(lee.datos);

				} else if (lee.resultado == "cargar_unidad") {
					selectUnidad(lee.datos);

				} else if (lee.resultado == "cargar_cargo") {
					selectCargo(lee.datos);

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



function selectCargo(arreglo) {
	$("#cargo").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {

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

function capaValidar() {

	$("#cedula").on("keypress", function (e) {
		validarKeyPress(/^[0-9\B]*$/, e);
	});

	$("#cedula").on("keyup", function () {
		if(validarKeyUp(
			/^[0-9]{7,10}$/, $(this), $("#scedula"),
			"Cédula no válida, el formato es: 00000000"
		)){
			$(this).attr("placeholder", "")
		} else{
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
		mensajes("error", 10000, "Verifica", "Cédula no válida, el formato es: V-**********");
		return false;

	} else if (validarKeyUp(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{4,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del empleado debe tener de 4 a 45 carácteres");
		return false;

	} else if (validarKeyUp(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{4,45}$/, $("#apellido"), $("#sapellido"), "") == 0) {
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
		mensajes("error", 10000, "Verifica", "Debe seleccionar un Cargo");
		return false;
	}
	return true;
}

async function crearDataTable(arreglo) {

	json = await ConsultarPermisos();
	arrayPermiso = JSON.parse(json);

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
			{
				data: null, render: function (row) {
					const html = [];

					if (Array.isArray(arrayPermiso) || Object.keys(arrayPermiso).length == 0 || arrayPermiso == null) {
					} else {
						if (arrayPermiso.permisos.empleado.modificar.estado == '1') {
							html.push(`<button onclick="rellenar(this, 0)" data-ci='${row.cedula}' title="Modificar" class="btn btn-update">
                    					<i class="fa-solid fa-pen-to-square"></i>
                						</button>`);
						}

						if (arrayPermiso.permisos.empleado.eliminar.estado == '1') {
							html.push(`<button onclick="rellenar(this, 1, )" data-ci='${row.cedula}' title="Eliminar" class="btn btn-danger">
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

	$("#ente").val('default').change().removeClass("is-valid is-invalid").prop('readOnly', true);
	$("#sente").text("");

	$("#dependencia").val('default').change().removeClass("is-valid is-invalid").prop('readOnly', true).empty();
	$("#sdependencia").text("");

	$("#unidad").val('default').change().removeClass("is-valid is-invalid").prop('readOnly', true).empty();
	$("#sunidad").text("");

	$("#cargo").val('default').change().removeClass("is-valid is-invalid").prop('readOnly', false);
	$("#scargo").text("");

	$('#enviar').val('default').change();
}

async function rellenar(pos, accion) {
	limpia();
	var espera;
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

	if (espera) {
		buscarSelect('#unidad', $(linea).find("td:eq(6)").text(), 'text');
	}

	buscarSelect('#cargo', $(linea).find("td:eq(7)").text(), 'text');


	if (accion == 0) {
		$("#modalTitleId").text("Modificar Empleado")
		$("#enviar").text("Modificar");
	}
	else {
		$("#cedula").prop('readOnly', true);
		$("#nombre").prop('readOnly', true);
		$("#apellido").prop('readOnly', true);
		$("#telefono").prop('readOnly', true);
		$("#correo").prop('readOnly', true);
		$("#dependencia").prop('disable', true);
		$("#unidad").prop('disable', true);
		$("#cargo").prop('disable', true);

		$("#modalTitleId").text("Eliminar Empleado")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}