$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarTecnico();

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;

		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará un Tipo de Servicio", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						var servicio = [];
						var componente = [];
						servicio = procesarServicio("servicio");
						componente = procesarServicio("componente");
						datos.append('registrar', 'registrar');
						datos.append('nombre', $("#nombre").val());
						datos.append('encargado', $("#encargado").val());
						datos.append('servicios', JSON.stringify(servicio));
						datos.append('componentes', JSON.stringify(componente));

						console.log(JSON.stringify(servicio));
						console.log(JSON.stringify(componente));
						enviaAjax(datos);
						envio = false;
					}
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se modificará un Tipo de Servicio", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_servicio', $("#id_servicio").val());
						datos.append('nombre', $("#nombre").val());
						datos.append('encargado', $("#encargado").val());
						enviaAjax(datos);
						envio = true;
					}
				}
				break;
			case "Eliminar":
				if (validarKeyUp(/^[0-9]{1,11}$/, $("#id_servicio"), $("#sid_servicio"), "") == 1) {
					confirmacion = await confirmarAccion("Se eliminará un Tipo de Servicio", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {
						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_servicio', $("#id_servicio").val());
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
		$("#idServicio").remove();
		$("#inputs_servicios").removeClass("d-none");
		$("#inputs_tablas").addClass("d-none");
		$("#modalTitleId").text("Registrar Tipo de Servicio");
		$("#enviar").text("Registrar");
		$("#modal1").modal("show");
	}); //<----Fin Evento del Boton Registrar
});

function cargarTecnico() {
	var datos = new FormData();
	datos.append('listar_tecnicos', 'listar_tecnicos');
	enviaAjax(datos);
}

function listarServicio(idServicio, componente = "tabla") {
	var datos = new FormData();
	datos.append('listar_servicio', 'listar_servicio');
	datos.append('id_servicio', idServicio);
	datos.append('componente', componente);
	enviaAjax(datos);
}

function listarComponente(idServicio, componente = "tabla") {
	var datos = new FormData();
	datos.append('listar_componente', 'listar_componente');
	datos.append('id_servicio', idServicio);
	datos.append('componente', componente);
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
					crearDataTable(lee.datos);

				} else if (lee.resultado == "listar_componente") {
					if (lee.componente == "tabla") {
						TablaComponente(lee.datos);

					} else if (lee.componente == "input") {
						itemServicio(lee.datos, "componente");
					} else {
						console.log("error");
					}

				} else if (lee.resultado == "listar_servicio") {
					if (lee.componente == "tabla") {
						TablaServicio(lee.datos);
					} else if (lee.componente == "input") {
						itemServicio(lee.datos, "servicio");
					} else {
						console.log("error");
					}

				} else if (lee.resultado == "modificar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "eliminar") {
					$("#modal1").modal("hide");
					mensajes("success", 10000, lee.mensaje, null);
					consultar();

				} else if (lee.resultado == "entrada") {

				} else if (lee.resultado == "listar_tecnicos") {
					selectTecnico(lee.datos);

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

	$('#encargado').on('change', function () {

		if ($(this).val() === 'default') {

			estadoSelect(this, '#sencargado', "Seleccione un Encargado", 0);
		} else {
			estadoSelect(this, '#sencargado', "", 1);
		}
	});


	$(".input-grupo").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.\b]*$/, e);
	})

	$(".input-grupo").on("keyup", function () {
		console.log($(this).attr('id'));
		var idSpan = $(this).attr('id');
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $(this), $("#s" + idSpan),
			"El nombre debe contener entre 3 a 20 carácteres"
		);
	})
}

function procesarServicio(parametro) {
	let arreglo = [];
	let clase = "";
	let grupo = "";

	if (parametro == 'servicio') {
		clase = "btn-agregarS";
		grupo = "servicio"

	} else if (parametro == 'componente') {
		clase = "btn-agregarC";
		grupo = "componente"
	} else {
		return [];
	}
	$('.row-' + clase).each(function () {
		var nombre = $(this).find('.grupo-' + grupo).val();
		var bool;
		if ($(this).find('.form-check-input').prop('checked')) {
			bool = 1;
		} else {
			bool = 0;
		};

		arreglo.push({
			nombre: nombre,
			estado: bool,
			id: $(this).find('.grupo-' + grupo).attr('data-id-item') || null
		})
	})
	return arreglo;
}

function inputServicio() {
	let idS = [];
	let resultado = 1
	let bool;

	idS = Array.from(document.querySelectorAll('.btn-agregarS input[type="text"][id]'))
		.map(input => input.id)
		.concat(Array.from(document.querySelectorAll('.btn-agregarC input[type="text"][id]'))
			.map(input => input.id));

	console
	idS.forEach((id, index) => {
		console.log($(`#${id}`));
		bool = validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, ($(`#${id}`)), $(`#s${id}`), "")
		if (bool === 0) {
			resultado = 0;
		}
	})

	console.log(resultado);
	return resultado;
}

function validarenvio() {
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $("#nombre"), $("#snombre"), "") == 0) {
		mensajes("error", 10000, "Verifica", "El nombre de la marca debe tener de 4 a 45 carácteres");
		return false;

	} else if ($('#encargado').val() === 'default') {
		mensajes("error", 10000, "Verifica", "Seleccione una dependencia");
		return false;
	} else if (inputServicio() == 0) {
		mensajes("error", 10000, "Verifica", "Uno de los Servicios no es valido");
		return false;
	} else {
		return true;
	}
}

function vistaPermiso(permisos = null) {

	if (Array.isArray(permisos) || Object.keys(permisos).length == 0 || permisos == null) {

		$('.modificar').remove();
		$('.eliminar').remove();

	} else {

		if (permisos['tipo_servicio']['modificar']['estado'] == '0') {
			$('.modificar').remove();
		}

		if (permisos['tipo_servicio']['eliminar']['estado'] == '0') {
			$('.eliminar').remove();
		}
	}
};

$("#btn-agregarC").on("click", async function () {
	crearInput(this)
})

$("#btn-agregarS").on("click", async function () {
	crearInput(this)
})

var idC = null;
var idS = null;
function crearInput(etiqueta) {
	var id;
	var labelStr;
	var idInput;
	var grupo;
	console.log(etiqueta);
	console.log($(etiqueta).attr('id'));
	id = $(etiqueta).attr('id');
	console.log($("." + id));

	console.log(id + ": " + $("." + id).find('.row-' + id).length);

	if ($("." + id).find('.row-' + id).length < 10) {

		if (id == 'btn-agregarS') {

			idS = idS + 1;
			labelStr = 'Servicio Ofrecido';
			grupo = 'servicio';
			console.log(idS);
			idInput = idS;
		}

		if (id == 'btn-agregarC') {

			idC = idC + 1;
			labelStr = 'Nombre del Componente';
			grupo = 'componente';
			console.log(idC);
			idInput = idC;
		}

		$("." + id).append(`<div id="${grupo}${idInput}" class="row text-center d-flex align-items-center row-${id}">
                  <div class="col-xl-6">
                    <div class="form-floating mb-3 mt-4">
                      <input placeholder="" class="form-control input-grupo grupo-${grupo}" name="nombre" data-id-item= type="text" id="nombre-${grupo}${idInput}"
                        maxlength="20">
                      <span id="snombre-${grupo}${idInput}"></span>
                      <label for="nombre-${grupo}${idInput}" class="form-label">${labelStr}</label>
                    </div>
                  </div>
                  <div class="col-xl-3 align-self-center d-flex justify-content-center">
                    <div class="form-check form-switch d-flex justify-content-center flex-nowrap">
                      <input class="form-check-input" type="checkbox" role="switch" value="" id=""><br>
                      <label class="form-check-label d-flex justify-content-center" for="">Incluir Observación</label>
                    </div>
                    </button>
                  </div>
                  <div class="col-xl-3 align-self-center">
                    <button type="button" id="boton-${grupo}${idInput}" onclick="eliminarItem('boton-${grupo}${idInput}')" class="btn btn-primary btn-sm mx-auto my-4 ">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </div>
                </div>`);
	} else {

	}
	capaValidar();
}

$("#btn-configuarS").on("click", function () {
	listarServicio($("#id_servicio").val(), "input")
})

$("#btn-configuarC").on("click", function () {
	listarComponente($("#id_servicio").val(), "input")

})

$("#retroceder-config").on("click", function () {
	$("#modal1").modal("show")
	$("#modalConfigurar").modal("hide");
})

function itemServicio(datos, item) {

	if (item == "servicio") {
		$("#titulo-configurar").text("Servicios");
	} else if (item == "componente") {
		$("#titulo-configurar").text("Componentes");
	} else {
		$("#titulo-configurar").text("");
	}
	$("#div-configurar").empty();
	datos.forEach(item => {
		$("#div-configurar").append(`<div id="" class="row text-center d-flex align-items-center row-">
                <div class="col-xl-2">
                    <div class="form-floating mb-3 mt-4">
                      <input placeholder="" value="${item.id}" class="form-control input-grupo input-id" name="id" data-id-itemid=${item.id} type="text" id="id-${item.id}"
                        maxlength="20">
                      <span id="sid-${item.id}"></span>
                      <label for="id-${item.id}" class="form-label">ID</label>
                    </div>
                  </div>  
				<div class="col-xl-4">
                    <div class="form-floating mb-3 mt-4">
                      <input placeholder="" value="${item.nombre}" class="form-control input-grupo input-nombre" name="nombre" data-id-nombre=${item.id} type="text" id="nombre-${item.id}"
                        maxlength="20">
                      <span id="snombre-${item.id}"></span>
                      <label for="nombre-${item.id}" class="form-label">Nombre</label>
                    </div>
                  </div>
                  <div class="col-xl-4 align-self-center d-flex justify-content-center">
                    <div class="form-check form-switch d-flex justify-content-center flex-nowrap">
                      <input class="form-check-input" type="checkbox" role="switch" value="" id="checkbox-${item.id}"><br>
                      <label class="form-check-label d-flex justify-content-center" for="">Incluir Observación</label>
                    </div>
                    </button>
                  </div>
                  <div class="col-xl-2 align-self-center">
                    <button type="button" id="boton-guardar" class="btn btn-primary btn-sm mx-auto my-4 ">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </div>
                </div>`)
		if (item.bool_texto == 1) {
			$(`#checkbox-${item.id}`).prop('checked', true);
		}
		$(`#id-${item.id}`).prop('readOnly', true)
		
	});
	console.log(item);
	console.log(datos);
	$("#modal1").modal("hide")
	$("#modalConfigurar").modal("show");
}

function eliminarItem(id) {
	const input = $("#" + id);
	const contenedorPadre = input.parent().parent();
	console.log(contenedorPadre);
	contenedorPadre.remove();
};

function selectTecnico(arreglo) {
	$("#encargado").empty();
	if (Array.isArray(arreglo) && arreglo.length > 0) {

		$("#encargado").append(
			new Option('Seleccione un Encargado', 'default')
		);
		$("#encargado").append(
			new Option('No asignar', '')
		);
		arreglo.forEach(item => {
			$("#encargado").append(
				new Option(item.nombre_completo, item.cedula_empleado)
			);
		});
	} else {
		$("#encargado").append(
			new Option('No Hay Encargados', 'default')
		);
		$("#encargado").append(
			new Option('No asignar', '')
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
			{ data: 'id_tipo_servicio' },
			{ data: 'nombre_tipo_servicio' },
			{ data: 'cedula_encargado' },
			{ data: 'encargado' },
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
	ConsultarPermisos();
}

function TablaServicio(arreglo) {
	if ($.fn.DataTable.isDataTable('#tabla_servicio')) {
		$('#tabla_servicio').DataTable().destroy();
	}
	$('#tabla_servicio').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id' },
			{ data: 'nombre' }],

		language: {
			url: idiomaTabla,
		}
	});
	ConsultarPermisos();
}

function TablaComponente(arreglo) {

	console.log(arreglo);
	if ($.fn.DataTable.isDataTable('#tabla_componentes')) {
		$('#tabla_componentes').DataTable().destroy();
	}
	$('#tabla_componentes').DataTable({
		data: arreglo,
		columns: [
			{ data: 'id' },
			{ data: 'nombre' }],

		language: {
			url: idiomaTabla,
		}
	});
	ConsultarPermisos();
}

function limpia() {
	$("#nombre").removeClass("is-valid is-invalid");
	$("#nombre").val("");

	$("#container-servicio").empty();
	$("#container-componente").empty();
	$('#enviar').prop('disabled', false);
	idS = 0;
	idC = 0;
}


function rellenar(pos, accion) {
	$("#inputs_servicios").addClass("d-none");
	$("#inputs_tablas").removeClass("d-none");

	linea = $(pos).closest('tr');

	$("#idServicio").remove();
	$("#Fila1").prepend(`<div class="col-lg-4" id="idServicio">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_servicio" type="text" id="id_servicio" readOnly>
              <span id="sid_servicio"></span>
              <label for="id_servicio" class="form-label">ID del Tipo de Servicio</label>
            </div>`);

	$("#id_servicio").val($(linea).find("td:eq(0)").text());
	$("#nombre").val($(linea).find("td:eq(1)").text());
	buscarSelect("#encargado", $(linea).find("td:eq(2)").text(), "value");

	listarServicio($(linea).find("td:eq(0)").text())
	listarComponente($(linea).find("td:eq(0)").text())


	if (accion == 0) {
		$("#modalTitleId").text("Modificar Tipo de Servicio")
		$("#enviar").text("Modificar");
	}
	else {
		$("#modalTitleId").text("Eliminar Tipo de Servicio")
		$("#enviar").text("Eliminar");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}