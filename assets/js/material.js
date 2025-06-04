$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();

	const hoy = new Date().toISOString().split('T')[0];
	document.getElementById('fecha_inicio').max = hoy;
	document.getElementById('fecha_fin').max = hoy;

	// Validar que fecha inicio <= fecha fin
	document.getElementById('fecha_inicio').addEventListener('change', function () {
		const fechaFin = document.getElementById('fecha_fin');
		if (this.value > fechaFin.value) {
			fechaFin.value = this.value;
		}
		fechaFin.min = this.value;
	});

	document.getElementById('fecha_fin').addEventListener('change', function () {
		const fechaInicio = document.getElementById('fecha_inicio');
		if (this.value < fechaInicio.value) {
			fechaInicio.value = this.value;
		}
	});

	

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {
			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará un Material", "¿Está seguro de realizar la acción?", "question");

					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('nombre', $("#nombre").val());
						datos.append('ubicacion', $("#ubicacion").val());
						datos.append('stock', $("#stock").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará un Material", "¿Está seguro de realizar la acción?", "question");

					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_material', $("#id_material").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('ubicacion', $("#ubicacion").val());
						datos.append('stock', $("#stock").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{1,11}$/, $("#id_material"), $("#sid_material"), "") === 1) {
					confirmacion = await confirmarAccion("Se eliminará un Material", "¿Está seguro de realizar la acción?", "warning");

					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_material', $("#id_material").val());
						enviaAjax(datos);
						envio = true;
					}
				} else {
					envio = false;
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
		$("#idMaterial").remove();
		$("#modalTitleId").text("Registrar Material");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	});

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});
});

async function restaurarMaterial(boton) {
	var confirmacion = false;
	var linea = $(boton).closest('tr');
	var id_material = $(linea).find('td:eq(0)').text();
	console.log(id_material);

	confirmacion = await confirmarAccion("¿Restaurar Material?", "¿Está seguro que desea restaurar este material?", "question");

	if (confirmacion) {
		var datos = new FormData();
		datos.append('restaurar', 'restaurar');
		datos.append('id_material', id_material);
		enviaAjax(datos);
	}
}


function consultarEliminadas() {
	var datos = new FormData();
	datos.append('consultar_eliminadas', 'consultar_eliminadas');

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
		beforeSend: function () {},
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
				} else if (lee.resultado == "modificar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();
				} else if (lee.resultado == "eliminar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();
				} else if (lee.resultado == "consultar_eliminadas") {
					TablaEliminados(lee.datos);

				} else if (lee.resultado == "restaurar") {
					mensajes("success", null, "Material restaurado", lee.mensaje);
					consultarEliminadas();
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
		complete: function () {},
	});
}

function capaValidar() {
	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#nombre").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,90}$/, $(this), $("#snombre"),
			"El nombre del material debe tener de 4 a 90 carácteres"
		);
	});

	$("#stock").on("keypress", function (e) {
		validarKeyPress(/^[0-9\b]*$/, e);
	});
	$("#stock").on("keyup", function () {
		validarKeyUp(
			/^[0-9]{1,11}$/, $(this), $("#sstock"),
			"El stock debe ser un número válido"
		);
	});

	$("#ubicacion").on("change", function () {
		if($(this).val() != ""){
			console.log("select actvio");
			estadoSelect($(this), $("#subicacion"), "Debe seleccionar una ubicación",1);
		}else{
			estadoSelect($(this), $("#subicacion"), "Debe seleccionar una ubicación");
		}
	});
}

function validarenvio() {
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{4,90}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre del material debe tener de 4 a 90 carácteres");
		return false;
	} else if ($("#ubicacion").val() == "") {
		mensajes("error", 10000, "Verifica", "Seleccione una ubicación válida");
		return false;
	} else if (validarKeyUp(/^[0-9]{1,11}$/, $("#stock"), $("#sstock"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El stock debe ser un número válido");
		return false;
	}
	return true;
}

function crearDataTable(arreglo) {
	console.log(arreglo);
	if ($.fn.DataTable.isDataTable('#tabla1')) {
		$('#tabla1').DataTable().destroy();
	}
	$('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{data: 'id_material'},
			{data: 'nombre_material'},
			{data: 'nombre_oficina'},
			{data: 'stock'},
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
	$("#ubicacion").removeClass("is-valid is-invalid");
	$("#ubicacion").val("");
	$("#stock").removeClass("is-valid is-invalid");
	$("#stock").val("");

	$("#nombre").prop("readOnly", false);
	$("#ubicacion").prop("readOnly", false);
	$("#stock").prop("readOnly", false);

	$('#enviar').prop('disabled', false);
}

function rellenar(pos, accion) {
	limpia();

	linea = $(pos).closest('tr');

	$("#idMaterial").remove();
	$("#Fila1").prepend(`<div class="col-4" id="idMaterial">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_material" type="text" id="id_material" readOnly>
              <span id="sid_material"></span>
              <label for="id_material" class="form-label">ID del Material</label>
            </div>`);

	$("#id_material").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());
	buscarSelect("#ubicacion", $(linea).find("td:eq(2)").text(), "text");
	$("#stock").val($(linea).find("td:eq(3)").text());

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Material")
		$("#enviar").text("Modificar");
	} else {
		$("#nombre").prop("readOnly", true);
		$("#ubicacion").prop("disabled", true);
		$("#stock").prop("readOnly", true);
		$("#modalTitleId").text("Eliminar Material")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}

function TablaEliminados(arreglo) {
	if ($.fn.DataTable.isDataTable('#tablaEliminadas')) {
		$('#tablaEliminadas').DataTable().destroy();
	}

	$('#tablaEliminadas').DataTable({
		data: arreglo,
		columns: [
			{data: 'id_material'},
			{data: 'nombre_material'},
			{data: 'ubicacion'},
			{data: 'stock'},
			{
				data: null,
				render: function () {
					return `<button onclick="restaurarMaterial(this)" class="btn btn-success">
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