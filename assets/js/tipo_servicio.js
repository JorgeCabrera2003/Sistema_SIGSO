$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarTecnico();

	$("#enviar").on("click", async function () {
		var confirmacion = false;
		var envio = false;
		var datos = new FormData();
		switch ($("#senviar").text()) {

			case "Registrar":
				if (validarenvio()) {
					confirmacion = await confirmarAccion("Se registrará un Tipo de Servicio", "¿Está seguro de realizar la acción?", "question");
					if (confirmacion) {

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
						enviaAjax(datos, '#spinner-enviar');
						envio = true;
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
						enviaAjax(datos, '#spinner-enviar');
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
						enviaAjax(datos, '#spinner-enviar');
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
		$("#senviar").text("Registrar");
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
	if (componente == 'tabla') {
		$("#div-TablaServicio").addClass("d-none");
		$("#spinnerServicios").removeClass("d-none");
	} else if (componente == 'input') {
		$("#div-configurar").addClass("d-none");
		$("#spinner-configuracion").removeClass("d-none");
	} else {
		componente = 'tabla';
		$("#div-TablaServicio").addClass("d-none");
		$("#spinnerServicios").removeClass("d-none");
	}
	datos.append('listar_servicio', 'listar_servicio');
	datos.append('id_servicio', idServicio);
	datos.append('componente', componente);
	enviaAjax(datos);
}

function listarComponente(idServicio, componente = "tabla") {
	var datos = new FormData();
	if (componente == 'tabla') {
		$("#div-TablaComponente").addClass("d-none");
		$("#spinnerComponentes").removeClass("d-none");
	} else if (componente == 'input') {
		$("#div-configurar").addClass("d-none");
		$("#spinner-configuracion").removeClass("d-none");
	} else {
		componente = 'tabla';
		$("#div-TablaServicio").addClass("d-none");
		$("#spinnerServicios").removeClass("d-none");
	}
	datos.append('listar_componente', 'listar_componente');
	datos.append('id_servicio', idServicio);
	datos.append('componente', componente);
	enviaAjax(datos);
}

function enviaAjax(datos, spinner = null) {
	$.ajax({
		async: true,
		url: "",
		type: "POST",
		contentType: false,
		data: datos,
		processData: false,
		cache: false,
		beforeSend: function () {
			if (spinner != null) {
				$(spinner).removeClass("d-none");
			}
		},
		timeout: 10000, //tiempo maximo de espera por la respuesta del servidor
		success: function (respuesta) {
			if (spinner != null) {
				$(spinner).addClass("d-none");
			}
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
			"El nombre del servicio debe tener de 4 a 45 carácteres"
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

	$(".prefijo-input").on("keypress", function (e) {
		validarKeyPress(/^[0-9 a-zA-ZáéíóúüñÑçÇ - \b]*$/, e);
	})

	$(".input-grupo").on("keyup", function () {
		console.log($(this).attr('id'));
		var idSpan = $(this).attr('id');
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, $(this), $("#s" + idSpan),
			"El nombre debe contener entre 3 a 45 carácteres"
		);
	})
	$(".prefijo-input").on("keyup", function () {
		console.log($(this).attr('id'));
		var idSpan = $(this).attr('id');
		validarKeyUp(
			/^[0-9 a-zA-ZáéíóúüñÑçÇ - ]{5,45}$/, $(this), $("#s" + idSpan),
			"El Prefijo debe tener entre 4 a 45 carácteres"
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
		var prefijo = null;
		var bool;
		if ($(this).find('.form-check-input').prop('checked')) {
			prefijo = $(this).find('.prefijo-' + grupo).val();
			bool = 1;
		} else {
			bool = 0;
			prefijo = null
		};

		arreglo.push({
			nombre: nombre,
			prefijo: prefijo,
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
	let prefijoid = [];

	idS = Array.from(document.querySelectorAll('.btn-agregarS input.input-grupo[type="text"][id]'))
		.map(input => input.id)
		.concat(Array.from(document.querySelectorAll('.btn-agregarC input.input-grupo[type="text"][id]'))
			.map(input => input.id));

	prefijoid = Array.from(document.querySelectorAll('.btn-agregarS input.prefijo-input[type="text"][id]'))
		.map(input => input.id)
		.concat(Array.from(document.querySelectorAll('.btn-agregarC input.prefijo-input[type="text"][id]'))
			.map(input => input.id));

	idS.forEach((id, index) => {
		console.log($(`#${id}`));
		bool = validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, ($(`#${id}`)), $(`#s${id}`), "")
		if (bool === 0) {
			resultado = 0;
		}
	})

	prefijoid.forEach((id, index) => {
		console.log($(`#${id}`));
		if ($(`checkbox-${id}`).prop('checked')) {
			bool = validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ - ]{5,45}$/, ($(`#${id}`)), $(`#s${id}`), "")
			if (bool === 0) {
				resultado = 0;
			}
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
                  <div class="col-xl-4">
                      <input placeholder="${labelStr}" class="form-control input-grupo grupo-${grupo}" name="nombre" data-id-item= type="text" id="nombre-${grupo}${idInput}"
                        maxlength="45">
                      <span id="snombre-${grupo}${idInput}"></span>
                  </div>
                  <div class="col-xl-3 align-self-center d-flex justify-content-center">
                    <div class="form-check form-switch d-flex justify-content-center flex-nowrap">
                      <input class="form-check-input" type="checkbox" role="switch" value="" id="checkbox-${grupo}${idInput}" onchange="bloquearInputCheck(this,'${grupo}${idInput}')"><br>
                      <label class="form-check-label d-flex justify-content-center" for="" >Observación</label>
                    </div>
                    </button>
                  </div>
				  <div class="col-xl-3">
                      <input placeholder="Prefijo" readOnly class="form-control prefijo-input prefijo-${grupo} grupo-${grupo}" name="prefijo" data-id-item= type="text" id="prefijo-${grupo}${idInput}"
                        maxlength="45">
                      <span id="sprefijo-${grupo}${idInput}"></span>
                  </div>
                  <div class="col-xl-2 align-self-center">
                    <button type="button" id="boton-${grupo}${idInput}" onclick="eliminarItem('boton-${grupo}${idInput}')" class="btn btn-primary btn-sm mx-auto my-4 ">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </div>
                </div>`);
	} else {

	}
	capaValidar();
}

function bloquearInputCheck(input, id) {

	if ($(input).prop('checked')) {
		$("#prefijo-" + id).prop("readOnly", false);
	} else {
		$("#prefijo-" + id).prop("readOnly", true);
		$("#prefijo-" + id).removeClass("is-invalid is-valid");
	}
};

$("#btn-configuarS").on("click", function () {
	listarServicio($("#id_servicio").val(), "input")
})

$("#btn-configuarC").on("click", function () {
	listarComponente($("#id_servicio").val(), "input")

})

$("#agregar-config").on("click", function () {
	crearInputConfiguracion()
})

$("#retroceder-config").on("click", function () {
	$("#modal1").modal("show")
	$("#modalConfigurar").modal("hide");
})

$("#guardar-config").on("click", function () {

	var valores = null;
	if (inputGuardar() == 0) {

		mensajes("error", 10000, "Verifica", "Uno de los campos no es válido")

	} else {
		if ($("#titulo-configurar").text() == "Servicios") {
			valores = procesarServicio("servicio");
		} else if ($("#titulo-configurar").text() == "Componentes") {
			valores = procesarServicio("componente");
		} else {
			console.log("error");
		}
	}

	console.log(valores);
})

function crearInputConfiguracion() {
	let grupo = "";
	let clase = "";
	let labelStr = "";
	let idInput = null;

	if ($("#titulo-configurar").text() == "Servicios") {
		idS = idS + 1;
		grupo = "servicio";
		clase = "btn-agregarS";
		idInput = idS

	} else if ($("#titulo-configurar").text() == "Componentes") {
		idC = idC + 1;
		grupo = "componente";
		clase = "btn-agregarC";
		idInput = idC
	} else {
		console.log("error")
	}
	if ($("#div-configurar").find('.row-' + clase).length < 15) {

		let tabla = $('#tabla_configurar').DataTable()

		tabla.row.add({
			id: grupo + "" + idInput,
			nombre: "",
			bool_texto: false,
			prefijo: ""
		}).draw(false)

		$("#div-configurar").prepend(`<div id="" class="row text-center d-flex align-items-center row-${clase}">
                <div class="col-xl-1">
                      <input placeholder="" value="(Generar Automáticamente)" class="form-control input-grupo input-id" name="id"  type="text" id="id-${grupo}-${idInput}"
                        maxlength="24" disabled>
                      <span id="sid-${grupo}-${idInput}"></span>
                  </div>  
				<div class="col-xl-4">
                      <input placeholder="Nombre" value="" class="form-control input-grupo input-nombre grupo-${grupo}" name="nombre" data-id-item="" type="text" id="nombre-${grupo}-${idInput}"
                        maxlength="65">
                      <span id="snombre-${grupo}-${idInput}""></span>
                </div>
                  <div class="col-xl-2 align-self-center d-flex justify-content-center">
                    <div class="form-check form-switch d-flex justify-content-center flex-nowrap">
                      <input class="form-check-input" type="checkbox" role="switch" value="" id="checkbox-${grupo}-${idInput}" onchange="bloquearInputCheck(this, '${grupo}${idInput}')"><br>
                      <label class="form-check-label d-flex justify-content-center" for="">Observación</label>
                    </div>
                  </div>
				  <div class="col-xl-3">
                      <input placeholder="Prefijo" readOnly class="form-control prefijo-input prefijo-${grupo} grupo-${grupo}" name="prefijo" data-id-item= type="text" id="prefijo-${grupo}${idInput}"
                        maxlength="45">
                      <span id="sprefijo-${grupo}${idInput}"></span>
                	</div>
                  <div class="col-xl-2 align-self-center">
                    <button type="button" id="boton-${grupo}-${idInput}" onclick="eliminarItemConfiguracion(this, 'boton-${grupo}-${idInput}')"class="btn btn-primary btn-sm mx-auto my-4 ">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </div>
                </div>`)
	}
	capaValidar();
}

function eliminarItemConfiguracion(etiqueta, id) {
	let row = $(etiqueta).closest('tr')
	const tabla = $('#tabla_configurar').DataTable()

	tabla.row(row).remove().draw()
};

async function eliminarRegistro(etiqueta, id) {
	var datos = new FormData()
	var tabla = null
	console.log(id);
	confirmacion = await confirmarAccion("Se eliminará este registro", "¿Está seguro de realizar la acción?", "question");
	if (confirmacion) {
		if ($("#titulo-configurar").text() == "Componentes") {
			tabla = "componente"
		} else if ($("#titulo-configurar").text() == "Servicios") {
			tabla = "servicio"
		} else {
			tabla = "error";
		}
		datos.append('eliminar_item', 'eliminar_item');
		datos.append('tabla', tabla)
		datos.append('id', id);

		enviaAjax(datos);
	}
}

function inputGuardar() {
	let idS = [];
	let resultado = 1
	let bool;

	idS = Array.from(document.querySelectorAll('.input-nombre'))
		.map(input => input.id);

	prefijoid = Array.from(document.querySelectorAll('input.prefijo-input[type="text"][id]'))
		.map(input => input.id)

	idS.forEach((id, index) => {
		console.log($(`#${id}`));
		bool = validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,45}$/, ($(`#${id}`)), $(`#s${id}`), "")
		if (bool === 0) {
			resultado = 0;
		}
	})

	prefijoid.forEach((id, index) => {
		console.log($(`#${id}`));
		if ($(`checkbox-${id}`).prop('checked')) {
			bool = validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ - ]{5,45}$/, ($(`#${id}`)), $(`#s${id}`), "")
			if (bool === 0) {
				resultado = 0;
			}
		}
	})

	console.log(resultado);
	return resultado;
}

function itemServicio(datos, item) {
	let grupo = "";
	let clase = "";

	if (item == "servicio") {
		$("#titulo-configurar").text("Servicios");
		grupo = "servicio";
		clase = "btn-agregarS";

	} else if (item == "componente") {
		$("#titulo-configurar").text("Componentes");
		grupo = "componente";
		clase = "btn-agregarC";

	} else {
		$("#titulo-configurar").text("");
	}
	$("#modal1").modal("hide")
	$("#modalConfigurar").modal("show");
	$("#div-configurar").empty();
	if ($.fn.DataTable.isDataTable('#tabla_configurar')) {
		$('#tabla_configurar').DataTable().destroy();
	}
	if (Array.isArray(datos) && datos.length > 0) {

		$('#tabla_configurar').DataTable({
			data: datos,
			columns: [
				{
					data: null, render: function (row) {
						var funcionstring = ""
						var idValue = null
						var check = ""
						var readonly = "readOnly"
						var prefijoval = ""
						const idRegex = /^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/
						if (idRegex.test(row.id) || null) {
							funcionstring = ""
							idValue = row.id
						} else {
							funcionstring = "(ID Generado)"
							idValue = ""
						}

						if (row.bool_texto == 1) {
							check = 'checked'
							readonly = ""
							prefijoval = row.prefijo
						}

						const html = `<div id="${row.id}" class="row text-center d-flex align-items-center row-${clase}">
                <div class="col-xl-1">
                      <input placeholder="${funcionstring}" value="${idValue}" readOnly class="form-control input-grupo input-id" name="id"  type="text" id="id-${row.id}"
                        maxlength="24">
                      <span id="sid-${row.id}"></span>
                  </div>  
				<div class="col-xl-4">
                      <input placeholder="Nombre" value="${row.nombre}" class="form-control input-grupo input-nombre grupo-${grupo}" name="nombre" data-id-item="${idValue}" type="text" id="nombre-${row.id}"
                        maxlength="65">
                      <span id="snombre-${row.id}"></span>
                  </div>
                  <div class="col-xl-2 align-self-center d-flex justify-content-center">
                    <div class="form-check form-switch d-flex justify-content-center flex-nowrap">
                      <input class="form-check-input" type="checkbox" ${check} role="switch" value="" id="checkbox-${row.id}" onchange="bloquearInputCheck(this, '${row.id}')"><br>
                      <label class="form-check-label d-flex justify-content-center" for="">Observación</label>
                    </div>
                  </div>
				  	<div class="col-xl-3">
                      <input placeholder="Prefijo" value="${prefijoval}" ${readonly} class="form-control prefijo-input prefijo-${grupo} grupo-${grupo}" name="prefijo" data-id-item="" type="text" id="prefijo-${row.id}"
                        maxlength="45">
                      <span id="sprefijo-${row.id}"></span>
                	</div>
				</div>`;
						return html;
					}
				},
				{
					data: null, render: function (row) {

						var funcionstring = ""
						const idRegex = /^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/
						if (idRegex.test(row.id) || null) {
							funcionstring = `eliminarRegistro('${row.id}')`
						} else {
							funcionstring = `eliminarItemConfiguracion(this, 'boton-${row.id}')`
						}

						const botones = `<div class="col-xl-2 align-self-center">
                    <button type="button" id="boton-quitar" onclick= "${funcionstring}" class="btn btn-primary btn-sm mx-auto my-4 ">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </div>`;
						return botones;
					}
				}],
			language: {
				url: idiomaTabla
			}
		})
	} else {
		$("#div-configurar").append(`<div class="row mt-5 text-center d-flex align-items-center" id="row-vacio">
                <div class="col-xl-12 align-self-center d-flex justify-content-center">
                	<div class="alert alert-danger d-flex align-items-center" role="alert">
  					<i class="fa-solid fa-triangle-exclamation"></i>
  						<div>
    						Alerta: No hay registros en esta sección
  						</div>
					</div>    
                </div>
				</div>`)
	}
	console.log(item);
	console.log(datos);
	$("#spinner-configuracion").addClass("d-none");
	$("#div-configurar").removeClass("d-none");
	capaValidar();
}

function eliminarItem(id) {
	const input = $("#" + id);
	const contenedorPadre = input.parent().parent();
	console.log(contenedorPadre);
	contenedorPadre.remove();
};

function selectTecnico(arreglo) {
	console.log(arreglo);
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

async function crearDataTable(arreglo) {

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
	await ConsultarPermisos();
	$("#spinnertabla1").addClass("d-none");
	$("#divtabla1").removeClass("d-none");
}

async function TablaServicio(arreglo) {
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
	await ConsultarPermisos();
	$("#spinnerServicios").addClass("d-none");
	$("#div-TablaServicio").removeClass("d-none");
}

async function TablaComponente(arreglo) {

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
	await ConsultarPermisos();
	$("#spinnerComponentes").addClass("d-none");
	$("#div-TablaComponente").removeClass("d-none");
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
		$("#senviar").text("Modificar");

		$("#btn-configuarS").prop("disabled", false).removeClass("d-none");
		$("#btn-configuarC").prop("disabled", false).removeClass("d-none");
	}
	else {
		$("#modalTitleId").text("Eliminar Tipo de Servicio")
		$("#senviar").text("Eliminar");
		$("#btn-configuarS").prop("disabled", true).addClass("d-none");
		$("#btn-configuarC").prop("disabled", true).addClass("d-none");
	}
	$('#enviar').prop('disabled', false);
	$("#modal1").modal("show");
}