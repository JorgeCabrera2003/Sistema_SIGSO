// Reescritura limpia de tecnico.js — integra validaciones y comportamientos similares a empleado.js
// Reescritura limpia de tecnico.js — integra validaciones y comportamientos similares a empleado.js
$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarEnte();
	cargarCargo();
	cargarServicio(); // cargar áreas al iniciar

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var texto = $(this).text();

		if (texto === "Registrar" && validarenvio()) {
			confirmacion = await confirmarAccion('Se registrará un nuevo Técnico', '¿Seguro de realizar la acción?', 'question');
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
				datos.append('grado_experiencia', $("#grado_experiencia").val());
				datos.append('servicio', $("#servicio").val());
				enviaAjax(datos);
			}
		} else if (texto === "Modificar" && validarenvio()) {
			confirmacion = await confirmarAccion('Se modificará Técnico', '¿Seguro de realizar la acción?', 'question');
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
				datos.append('grado_experiencia', $("#grado_experiencia").val());
				datos.append('servicio', $("#servicio").val());
				enviaAjax(datos);
			}
		} else if (texto === "Eliminar") {
			if (validarKeyUp(/^[0-9]{7,8}$/, $("#cedula"), $("#scedula"), "") == 1) {
				confirmacion = await confirmarAccion('Se eliminará Técnico', '¿Seguro de realizar la acción?', 'question');
				if (confirmacion) {
					var datos = new FormData();
					datos.append('eliminar', 'eliminar');
					datos.append('particle', $("#particle").val());
					datos.append('cedula', $("#cedula").val());
					enviaAjax(datos);
				}
			}
		} else if (texto !== "Registrar" && texto !== "Modificar" && texto !== "Eliminar") {
			mensajes("question", 10000, "Error", "Acción desconocida: " + texto);
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
}

async function cargarDependencia(id) {
	if (typeof id === 'undefined' || id === null || id === '') return null;
	var datos = new FormData();
	datos.append('id_ente', id);
	datos.append('cargar_dependencia', 'cargar_dependencia');
	return await enviaAjax(datos);
}

async function cargarUnidad(id) {
	var datos = new FormData();
	datos.append('id_dependencia', id);
	datos.append('cargar_unidad', 'cargar_unidad');
	return await enviaAjax(datos);
}

function cargarCargo() {
	var datos = new FormData();
	datos.append('cargar_cargo', 'cargar_cargo');
	enviaAjax(datos);
}

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
		timeout: 10000,
		success: function (respuesta) {
			try {
				var lee = JSON.parse(respuesta);
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
					case "cargar_ente":
						selectEnte(lee.datos);
						break;
					case "cargar_dependencia":
						selectDependencia(lee.datos);
						break;
					case "cargar_unidad":
						selectUnidad(lee.datos);
						break;
					case "cargar_cargo":
						selectCargo(lee.datos);
						break;
					case "cargar_servicio":
						selectServicio(lee.datos);
						break;
					case "permisos_modulo":
						vistaPermiso(lee.permisos);
						break;
					case "error":
						mensajes("error", null, lee.mensaje, null);
						break;
				}
			} catch (e) {
				mensajes("error", null, "Error en JSON: " + e.message);
			}
		},
		error: function (request, status, err) {
			if (status == "timeout") {
				mensajes("error", null, "Servidor ocupado", "Intente de nuevo");
			} else {
				mensajes("error", null, "Ocurrió un error", "ERROR: <br/>" + request + status + err);
			}
		}
	});
}

function selectCargo(arreglo) {
	$("#cargo").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#cargo").append(new Option('Seleccione un Cargo', ''));
		arreglo.forEach(item => { $("#cargo").append(new Option(item.cargo, item.id)); });
		$("#cargo").attr('disabled', false);
	} else {
		$("#cargo").append(new Option('No Hay Cargos', ''));
		$("#cargo").attr('disabled', true);
	}
}

function selectEnte(arreglo) {
	$("#ente").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#ente").append(new Option('Seleccione un Ente', ''));
		arreglo.forEach(item => { $("#ente").append(new Option(item.nombre_ente, item.id_ente)); });
		$("#ente").attr('disabled', false);
		try { if (arreglo[0] && arreglo[0].id_ente) cargarDependencia(arreglo[0].id_ente); } catch (e) { }
	} else {
		$("#ente").append(new Option('No Hay Entes', ''));
		$("#ente").attr('disabled', true);
	}
}

function selectDependencia(arreglo) {
	$("#dependencia").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#dependencia").append(new Option('Seleccione una Dependencia', ''));
		arreglo.forEach(item => { $("#dependencia").append(new Option(item.nombre_dependencia, item.id_dependencia)); });
		$("#dependencia").attr('disabled', false);
	} else {
		$("#dependencia").append(new Option('No Hay Dependencias', ''));
		$("#dependencia").attr('disabled', true);
	}
}

function selectUnidad(arreglo) {
	$("#unidad").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#unidad").append(new Option('Seleccione una Unidad', ''));
		arreglo.forEach(item => { $("#unidad").append(new Option(item.nombre_unidad, item.id_unidad)); });
		$("#unidad").attr('disabled', false);
	} else {
		$("#unidad").append(new Option('No Hay Unidades', ''));
		$("#unidad").attr('disabled', true);
	}
}

function selectServicio(arreglo) {
	$("#servicio").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {
		$("#servicio").append(new Option('Seleccione un Área de Servicio', ''));
		arreglo.forEach(item => { $("#servicio").append(new Option(item.nombre_tipo_servicio, item.id_tipo_servicio)); });
		$("#servicio").attr('disabled', false);
	} else {
		$("#servicio").append(new Option('No hay áreas', ''));
		$("#servicio").attr('disabled', true);
	}
}

function capaValidar() {
	// Cédula
	$("#cedula").on("keypress", function (e) { validarKeyPress(/^[0-9\b]*$/, e); });
	$("#cedula").on("keyup", function () {
		if (validarKeyUp(/^[0-9]{7,8}$/, $(this), $("#scedula"), "La cédula debe tener 7 u 8 dígitos numéricos")) $(this).attr("placeholder", "");
		else $(this).attr("placeholder", "0000000");
	});

	// Nombre
	$("#nombre").on("keypress", function (e) { validarKeyPress(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ \b]*$/, e); });
	$("#nombre").on("keyup", function () { validarKeyUp(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{3,45}$/, $(this), $("#snombre"), "El nombre debe tener de 3 a 45 carácteres"); });

	// Apellido
	$("#apellido").on("keypress", function (e) { validarKeyPress(/^[0-9 a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ\b]*$/, e); });
	$("#apellido").on("keyup", function () { validarKeyUp(/^[a-zA-ZÁÉÍÓÚáéíóúüñÑçÇ ]{3,45}$/, $(this), $("#sapellido"), "El apellido debe tener de 3 a 45 carácteres"); });

	// Correo
	$("#correo").on("keypress", function (e) { validarKeyPress(/^[-0-9a-z_.@\b]*$/, e); });
	$("#correo").on("keyup", function () { validarKeyUp(/^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/, $(this), $("#scorreo"), "El formato del correo electrónico es: usuario@servidor.com"); });

	// Teléfono: permitir solo números/guión, auto-insertar '-' después de 4 dígitos y sanitizar paste
	$("#telefono").on("keypress", function (e) { validarKeyPress(/^[-0-9\b]*$/, e); });
	// Formato al teclear / input (maneja borrado y pegado)
	$("#telefono").on("input", function () {
		var val = $(this).val();
		// mantener solo dígitos
		var digits = val.replace(/[^0-9]/g, "");
		if (digits.length > 11) digits = digits.substring(0, 11); // límite 11 dígitos sin guión
		// formatear como 4-7 (0414-1234567)
		if (digits.length > 4) {
			digits = digits.substring(0, 4) + '-' + digits.substring(4);
		}
		$(this).val(digits);
		validarKeyUp(/^[0-9]{4}[-]{1}[0-9]{7}$/, $(this), $("#stelefono"), "El numero de teléfono debe tener el siguiente formato: ****-*******");
	});
	// Sanitizar paste (por si pegan con espacios o sin guión)
	$("#telefono").on("paste", function (e) {
		e.preventDefault();
		var paste = (e.originalEvent || e).clipboardData.getData('text') || '';
		var digits = paste.replace(/[^0-9]/g, '');
		if (digits.length > 11) digits = digits.substring(0, 11);
		if (digits.length > 4) digits = digits.substring(0, 4) + '-' + digits.substring(4);
		$(this).val(digits).trigger('input');
	});

	// Selects: mantener placeholder sin valor y comportamiento progresivo ente->dependencia->unidad
	$("#cargo").on("change", function () { if ($(this).val() == "") estadoSelect(this, "#scargo", "Debe seleccionar un cargo", 0); else estadoSelect(this, "#scargo", "", 1); });

	$("#ente").on("change", function () {
		if ($(this).val() == "") {
			estadoSelect(this, "#sdependencia", "Debe seleccionar un Ente", 0);
			$("#dependencia").empty(); $("#unidad").empty(); $("#dependencia").attr("disabled", true); $("#unidad").attr("disabled", true);
		} else { estadoSelect(this, "#sdependencia", "", 1); cargarDependencia($(this).val()); }
	});

	$("#dependencia").on("change", function () {
		if ($(this).val() == "" || $(this).val() == null) { estadoSelect(this, "#sdependencia", "Debe seleccionar una Dependencia", 0); $("#unidad").empty(); $("#unidad").attr("disabled", true); }
		else { estadoSelect(this, "#sdependencia", "", 1); cargarUnidad($(this).val()); }
	});

	$("#unidad").on("change", function () { if ($(this).val() == "") estadoSelect(this, "#sunidad", "Debe seleccionar una Unidad", 0); else estadoSelect(this, "#sunidad", "", 1); });
}

function validarenvio() {
	if (validarKeyUp(/^[0-9]{7,8}$/, $("#cedula"), $("#scedula"), "") == 0) { mensajes("error", 10000, "Verifica", "Cédula no válida, el formato es: ********"); return false; }
	if (validarKeyUp(/^[a-z A-ZÁÉÍÓÚáéíóúüñÑçÇ]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) { mensajes("error", 10000, "Verifica", "El nombre del técnico debe tener de 3 a 45 carácteres"); return false; }
	if (validarKeyUp(/^[a-z A-ZÁÉÍÓÚáéíóúüñÑçÇ]{3,45}$/, $("#apellido"), $("#sapellido"), "") == 0) { mensajes("error", 10000, "Verifica", "El apellido debe tener de 3 a 45 carácteres"); return false; }
	if (validarKeyUp(/^[0-9]{4}[-]{1}[0-9]{7}$/, $("#telefono"), $("#stelefono"), "") == 0) { mensajes("error", 10000, "Verifica", "El numero de teléfono debe tener el siguiente formato: ****-*******"); return false; }
	if (validarKeyUp(/^[-0-9a-zç_]{6,36}[@]{1}[0-9a-z]{5,25}[.]{1}[com]{3}$/, $("#correo"), $("#scorreo"), "") == 0) { mensajes("error", 10000, "Verifica", "El formato del correo electrónico es: usuario@servidor.com"); return false; }

	if ($("#ente").val() == "") { mensajes("error", 10000, "Verifica", "Debe seleccionar un Ente"); return false; }
	if ($("#dependencia").val() == "" || $("#dependencia").val() == null) { mensajes("error", 10000, "Verifica", "Debe seleccionar una Dependencia"); return false; }
	if ($("#unidad").val() == "" || $("#unidad").val() == null) { mensajes("error", 10000, "Verifica", "Debe seleccionar una Unidad"); return false; }
	if ($("#cargo").val() == "") { mensajes("error", 10000, "Verifica", "Debe seleccionar un cargo"); return false; }
	if ($("#grado_experiencia").val() < 0 || $("#grado_experiencia").val() > 5) { mensajes("error", 10000, "Verifica", "Seleccione un grado de experiencia válido"); return false; }
	return true;
}

function vistaPermiso(permisos = null) {
	if (!permisos || Object.keys(permisos).length == 0) { $('.modificar').remove(); $('.eliminar').remove(); $('.reactivar').remove(); return; }
	if (permisos['tecnico'] && permisos['tecnico']['modificar'] && permisos['tecnico']['modificar']['estado'] == '0') $('.modificar').remove();
	if (permisos['tecnico'] && permisos['tecnico']['eliminar'] && permisos['tecnico']['eliminar']['estado'] == '0') $('.eliminar').remove();
	if (permisos['tecnico'] && permisos['tecnico']['reactivar'] && permisos['tecnico']['reactivar']['estado'] == '0') $('.reactivar').remove();
}

function crearDataTable(arreglo) {
	if ($.fn.DataTable.isDataTable('#tabla1')) { $('#tabla1').DataTable().destroy(); }
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
			{ data: null, render: function () { return `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button> <button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`; } }
		],
		language: { url: idiomaTabla }
	});
}

function limpia() {
	$("#cedula,#nombre,#apellido,#correo,#telefono").removeClass("is-valid is-invalid").val("").prop('readOnly', false);
	$("#scedula,#snombre,#sapellido,#scorreo,#stelefono,#sdependencia,#sunidad,#scargo,#sente").text("");
	$("#ente,#dependencia,#unidad,#cargo").val("").change();
}

async function rellenar(pos, accion) {
	limpia();
	var linea = $(pos).closest('tr');
	var cedula_completa = $(linea).find("td:eq(0)").text();
	var cedula = cedula_completa.replace(/[^0-9]/g, '');
	datos = new FormData();
	datos.append("buscar_usuario", null);
	datos.append("cedula", cedula_completa);
	var info_empleado = await enviaAjax(datos);
	try { info_empleado = JSON.parse(info_empleado); } catch (e) { info_empleado = null; }

	if (info_empleado) {
		if (info_empleado.unidad && info_empleado.dependencia && info_empleado.ente) {
			buscarSelect('#ente', info_empleado.ente.arreglo.id, 'value');
			await cargarDependencia(info_empleado.ente.arreglo.id);
			buscarSelect('#dependencia', info_empleado.dependencia.arreglo.id, 'value');
			await cargarUnidad(info_empleado.dependencia.arreglo.id);
			buscarSelect('#unidad', info_empleado.unidad.arreglo.id_unidad, 'value');
		}
		buscarSelect('#cargo', info_empleado.empleado.arreglo.id_cargo, 'value');
		buscarSelect('#grado_experiencia', info_empleado.empleado.arreglo.grado_experiencia_empleado, 'value');
	}

	$("#cedula").val(cedula);
	$("#nombre").val($(linea).find("td:eq(1)").text());
	$("#apellido").val($(linea).find("td:eq(2)").text());
	$("#telefono").val($(linea).find("td:eq(3)").text());
	$("#correo").val($(linea).find("td:eq(4)").text());

	if (accion == 0) { $("#modalTitleId").text("Modificar Técnico"); $("#enviar").text("Modificar"); }
	else { $("#cedula,#nombre,#apellido,#telefono,#correo").prop('readOnly', true); $("#particle,#dependencia,#ente,#unidad,#cargo").prop('disabled', true); $("#enviar").text("Eliminar"); }
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function consultar() { var datos = new FormData(); datos.append('consultar', 'consultar'); enviaAjax(datos); }
function registrarEntrada() { var datos = new FormData(); datos.append('entrada', 'entrada'); enviaAjax(datos); }
