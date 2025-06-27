$(document).ready(function () {
	consultar();

	$("#enviar").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará un Rol", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();

						const permisos_modulos = [];

						$('[data-modulo-string]').each(function () {
							const modulo = $(this);
							const moduloString = modulo.data('moduloString');
							const permisos = [];

							// Obtener todos los checkboxes dentro del módulo
							modulo.find('.form-check-input').each(function () {
								const checkbox = $(this);
								var bool;
								checkbox.attr('data-id-permiso', '');
								if (checkbox.prop('checked')) {
									bool = 1;
								} else {
									bool = 0;
								}
								permisos.push({
									accion: checkbox.val(),
									estado: bool,
									id: checkbox.attr('data-id-permiso') || null
								});

								checkbox.attr('data-id-permiso', '');
							});
							if (permisos.length > 0) {
								permisos_modulos.push({
									modulo: moduloString,
									permisos: permisos
								});
							}

						});
						console.log(datos);

						datos.append('registrar', 'registrar');
						datos.append('nombre', $("#nombre").val());
						datos.append('cargar_permiso', 'cargar_permiso');
						datos.append('datos', JSON.stringify(permisos_modulos));

						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará un Rol", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();

						const permisos_modulos = [];

						$('[data-modulo-string]').each(function () {
							const modulo = $(this);
							const moduloString = modulo.data('moduloString');
							const permisos = [];

							// Obtener todos los checkboxes dentro del módulo
							modulo.find('.form-check-input').each(function () {
								const checkbox = $(this);
								var bool;
								if (checkbox.prop('checked')) {
									bool = 1;
								} else {
									bool = 0;
								}
								permisos.push({
									accion: checkbox.val(),
									estado: bool,
									id: checkbox.attr('data-id-permiso') || null
								});
								checkbox.attr('data-id-permiso', '');
							});
							if (permisos.length > 0) {
								permisos_modulos.push({
									modulo: moduloString,
									permisos: permisos
								});
							}

						});
						console.log(datos);

						datos.append('modificar', 'modificar');
						datos.append('id_rol', $("#id_rol").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('cargar_permiso', 'cargar_permiso');
						datos.append('datos', JSON.stringify(permisos_modulos));

						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{1,11}$/, $("#id_rol"), $("#sid_rol"), "") == 1) {
					confirmacion = await confirmarAccion("Se eliminará un Rol", "¿Está seguro de realizar la acción?", "warning");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_rol', $("#id_rol").val());
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

	$("#permisos").on("click", async function () {

		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {

			case "Guardar":

				confirmacion = await confirmarAccion("Se subirán los permisos", "¿Está seguro de realizar la acción?", "question");

				if (confirmacion) {
					var formulario = new FormData();
					const datos = [];

					// Seleccionar todos los divs que contienen módulos
					$('[data-modulo-string]').each(function () {
						const modulo = $(this);
						const moduloString = modulo.data('moduloString');
						const permisos = [];

						// Obtener todos los checkboxes dentro del módulo
						modulo.find('.form-check-input').each(function () {
							const checkbox = $(this);
							var bool;
							if (checkbox.prop('checked')) {
								bool = 1;
							} else {
								bool = 0;
							}
							permisos.push({
								accion: checkbox.val(),
								estado: bool
							});
						});

						if (permisos.length > 0) {
							datos.push({
								modulo: moduloString,
								permisos: permisos
							});
						}
					});
					console.log(datos);
					formulario.append('id_rol', $('#Pid_rol').val());
					formulario.append('cargar_permiso', 'cargar_permiso');
					formulario.append('datos', JSON.stringify(datos));
					enviaAjax(formulario);
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

	$("#btn-registrar").on("click", function () { //<---- Evento del Boton Registrar
		limpia();
		$("#idRol").remove();
		$("#modalTitleId").text("Registrar Rol");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar
});

async function TraerPermiso(id) {
	var data = new FormData();
	data.append("filtrar_permiso", "filtrar_permiso");
	data.append("parametro", "modulo_id");
	data.append("id_rol", id);

	await enviaAjax(data);
}

async function enviaAjax(datos) {
	$.ajax({
		async: true,
		url: "",
		type: "POST",
		contentType: false,
		data: datos,
		processData: false,
		cache: false,
		beforeSend: function () { },

		success: function (respuesta) {
			console.log(respuesta);
			try {
				var lee = JSON.parse(respuesta);
				if (lee.resultado == "registrar") {
					$("#modal1").modal("hide");
					mensajes(lee.icon, 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "consultar") {
					crearDataTable(lee.datos);

				} else if (lee.resultado == "traer_permiso") {
					ColocarPermisos(lee.permiso);

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
			"El nombre del rol debe tener de 4 a 45 carácteres"
		);
	});
}

function validarenvio() {
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del rol debe tener de 4 a 45 carácteres");
		return false;

	}
	return true;
}

function ColocarPermisos(Arraypermisos) {

	console.log(Arraypermisos);

	$('[data-modulo-string]').each(function () {
		const modulo = $(this);
		const moduloString = modulo.data('moduloString');
		const permisos = [];

		const permisosModulo = Arraypermisos[moduloString] || {}
		console.log(permisosModulo);
		modulo.find('.form-check-input').each(function () {
			const checkbox = $(this);
			const accion = checkbox.val();  // Variable intermedia
			console.log(permisosModulo[accion]);

			if (permisosModulo[accion]) {
				if (permisosModulo[accion].estado == 1) {
					checkbox.prop('checked', true);
					checkbox.attr('data-id-permiso', permisosModulo[accion].id);

				} else {
					checkbox.prop('checked', false);
					checkbox.attr('data-id-permiso', permisosModulo[accion].id);
				}
				console.log("Encontrado");
			} else {
				checkbox.prop('checked', false);
				checkbox.attr('data-id-permiso', '');
				console.log("Perdido");
			}
			console.log(checkbox.attr('data-id-permiso'));
		});

		if (permisos.length > 0) {
			permisos_modulos.push({
				modulo: moduloString,
				permisos: permisos
			});
		}
	});
}

function crearDataTable(arreglo) {
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}

	$('#tabla1').DataTable({
		data: arreglo,
		order: [[0, "desc"]],
		columns: [
			{ data: 'id_rol' },
			{ data: 'nombre_rol' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>`;
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
	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");

	$("#id_rol").removeClass("is-valid is-invalid");
	$("#id_rol").val("default");

	$("#nombre").prop('readOnly', false);
	$("#id_rol").prop('disabled', false);
	$('#enviar').prop('disabled', false);
	var datos = new FormData();

	$('[data-modulo-string]').each(function () {
		const modulo = $(this);

		modulo.find('.form-check-input').each(function () {
			const checkbox = $(this);
			checkbox.attr('data-id-permiso', '');
			checkbox.prop('checked', false);
			checkbox.prop('disabled', false);
		});
	});
}


async function rellenar(pos, accion) {
	limpia();
	linea = $(pos).closest('tr');

	$("#idRol").remove();
	$("#Fila1").prepend(`<div class="col-md-4" id="idRol">
            <div class="form-floating">
              <input placeholder="" class="form-control" name="id_rol" type="text" id="id_rol" readOnly>
              <span id="sid_rol"></span>
              <label for="id_rol" class="form-label">ID de la Rol</label>
            </div>`);

	$("#id_rol").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());

	if (accion == 0) {

		await TraerPermiso($(linea).find("td:eq(0)").text());
		$("#id_rol").prop('readOnly', true);
		$("#id_rol").val($(linea).find("td:eq(0)").text());
		$("#nombre").val($(linea).find("td:eq(1)").text());

		$("#modalTitleId").text("Modificar Rol");
		$("#enviar").text("Modificar");
		$("#modal1").modal("show");

	} else {
		await TraerPermiso($(linea).find("td:eq(0)").text());
		$("#nombre").prop('readOnly', true);
		$("#id_rol").prop('disabled', true);
		$("#id_rol").val($(linea).find("td:eq(0)").text());
		$("#nombre").val($(linea).find("td:eq(1)").text());

		$("#modalTitleId").text("Eliminar Rol");
		$("#enviar").text("Eliminar");
		$("#modal1").modal("show");

		$('[data-modulo-string]').each(function () {
			const modulo = $(this);

			modulo.find('.form-check-input').each(function () {
				const checkbox = $(this);
				checkbox.prop('disabled', true);
			});
		});
	}
	$('#enviar').prop('disabled', false);
}