$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {
			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará una Marca", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('nombre', $("#nombre").val());
						enviaAjax(datos)
						envio = true;
					};
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará una Marca", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_marca', $("#id_marca").val());
						datos.append('nombre', $("#nombre").val());
						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/, $("#id_marca"), $("#sid_marca"), "") == 1) {
					confirmacion = await confirmarAccion("Se eliminará una Marca", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_marca', $("#id_marca").val());
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

	$("#btn-registrar").on("click", function () { //<---- Evento del Boton Registrar
		limpia();
		$("#idMarca").remove();
		$("#modalTitleId").text("Registrar Marca");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar

	$("#btn-consultar-eliminados").on("click", function () {
		consultarEliminadas();
		$("#modalEliminadas").modal("show");
	});

});

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
					iniciarTabla(lee.datos);

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
	$("#nombre").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	});
	$("#nombre").on("keyup", function () {
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $(this), $("#snombre"),
			"El nombre del edificio debe tener de 4 a 45 carácteres"
		);
	});
}

function validarenvio() {
	//OJO TAREA, AGREGAR LA VALIDACION DEL nro	
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre de la marca debe tener de 4 a 45 carácteres");
		return false;

	}
	return true;
}

var tabla;

function iniciarTabla(arreglo) {
	if (tabla == null) {
		crearDataTable(arreglo);
	} else {
		tabla.destroy();
		crearDataTable(arreglo);
	}
};

function vistaPermiso(permisos = null) {

	if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {

		$('.modificar').remove();
		$('.eliminar').remove();

	} else {

		if (permisos['marca']['modificar']['estado'] == '0') {
			$('.modificar').remove();
		}

		if (permisos['marca']['eliminar']['estado'] == '0') {
			$('.eliminar').remove();
		}
	}
};

function crearDataTable(arreglo) {
	tabla = $('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id_marca' },
			{ data: 'nombre_marca' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>`;
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
			{ data: 'id_marca' },
			{ data: 'nombre_marca' },
			{
				data: null,
				render: function () {
					return `<button onclick="restaurarDependencia(this)" class="btn btn-success restaurar">
                            <i class="fa-solid fa-recycle"></i>
                            </button>`;
				}
			}
		],
		order: [
			[1, 'asc']
		],
		language: {
			url: idiomaTabla,
		}
	});
	ConsultarPermisos();
}

function restaurarDependencia(boton) {
	var linea = $(boton).closest('tr');
	var id = $(linea).find('td:eq(0)').text();

	Swal.fire({
		title: '¿Restaurar Marca?',
		text: "¿Está seguro que desea restaurar esta marca?",
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sí, restaurar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			var datos = new FormData();
			datos.append('restaurar', 'restaurar');
			datos.append('id_marca', id);

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
							mensajes("success", null, "Marca restaurada", lee.mensaje);
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
					mensajes("error", null, "Error", "No se pudo restaurar la marca");
				}
			});
		}
	});
}

function limpia() {
	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");


	$("#nombre").prop('readOnly', false);
	$("#id_marca").prop('readOnly', false);

	$('#enviar').prop('disabled', false);
}


function rellenar(pos, accion) {

	linea = $(pos).closest('tr');

	$("#idMarca").remove();
	$("#Fila1").prepend(`<div class="col-4" id="idMarca">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_marca" type="text" id="id_marca" readOnly>
              <span id="sid_marca"></span>
              <label for="id_marca" class="form-label">ID de la Marca</label>
            </div>`);


	$("#id_marca").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());

	if (accion == 0) {
		$("#modalTitleId").text("Modificar Marca")
		$("#enviar").text("Modificar");
	}
	else {
		$("#nombre").prop('readOnly', true);
		$("#id_marca").prop('readOnly', true);
		$("#modalTitleId").text("Eliminar Marca")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}