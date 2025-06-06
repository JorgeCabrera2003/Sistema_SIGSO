$(document).ready(function () {
	consultar();
	registrarEntrada();
	capaValidar();
	cargarRol();

	$("#enviar").on("click", function () {
		switch ($(this).text()) {

			case "Registrar":
				if (validarenvio()) {
					var datos = new FormData();
					datos.append('registrar', 'registrar');
					datos.append('cedula', $("#cedula").val());
					datos.append('nombre', $("#nombre").val());
					datos.append('apellido', $("#apellido").val());
					datos.append('telefono', $("#telefono").val());
					datos.append('correo', $("#correo").val());
					datos.append('unidad', $("#unidad").val());
					datos.append('rol', $("#rol").val());
					enviaAjax(datos);
				}
				break;
			case "Modificar":
				if (validarenvio()) {
					var datos = new FormData();
					datos.append('modificar', 'modificar');
					datos.append('cedula', $("#cedula").val());
					datos.append('nombre', $("#nombre").val());
					datos.append('apellido', $("#apellido").val());
					datos.append('telefono', $("#telefono").val());
					datos.append('correo', $("#correo").val());
					datos.append('unidad', $("#unidad").val());
					datos.append('rol', $("#rol").val());
					enviaAjax(datos);
				}
				break;
			case "Eliminar":
				if (validarenvio()) {
					var datos = new FormData();
					datos.append('eliminar', 'eliminar');
					datos.append('cedula', $("#cedula").val());
					enviaAjax(datos);
				}
				break;

			default:
				mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text());;
		}
		$('#enviar').prop('disabled', true);
	});

	$("#btn-registrar").on("click", function () { //<---- Evento del Boton Registrar
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


	$("#cedula").on("keypress", function (e) {
		validarKeyPress(/^[-0-9V\B]*$/, e);
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
		validarKeyUp(
			/^[-0-9a-zç_]{4,15}[@]{1}[0-9a-z]{5,10}[.]{1}[com]{3}$/, $(this), $("#scorreo"),
			"El formato del correo electrónico es: usuario@servidor.com"
		);
	});

	$("#telefono").on("keypress", function (e) {
		validarKeyPress(/^[-0-9\b]*$/, e);
	});
	$("#telefono").on("keyup", function () {
		validarKeyUp(
			/^[0-9]{4}[-]{1}[0-9]{10}$/, $(this), $("#stelefono"),
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

}

function validarenvio() {
	//OJO TAREA, AGREGAR LA VALIDACION DEL nro	
	if (validarKeyUp(/^[0-9 a-zA-ZáéíóúüñÑçÇ -.]{3,30}$/, $("#motivo"),
		$("#smotivo"), "El motivo debe de tener 3 letras minimo") == 0) {
		mensajes("error", 10000, "Verifica", "El motivo debe tener entre 3 y 30 caracteres");
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

var tabla;

function iniciarTabla(arreglo) {
	if (tabla == null) {
		crearDataTable(arreglo);
	} else {
		tabla.destroy();
		crearDataTable(arreglo);
	}
};

function crearDataTable(arreglo) {

	console.log(arreglo);
	tabla = $('#tabla1').DataTable({
		data: arreglo,
		columns: [
			{ data: 'nombre_usuario' },
			{ data: 'cedula' },
			{ data: 'nombres' },
			{ data: 'apellidos' },
			{ data: 'rol' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>`;
					return botones;
				}
			}
		]
	});

}

z
function limpia() {
	$("#motivo").last().removeClass("is-valid");
	$("#motivo").last().removeClass("is-invalid");
	$("#motivo").val("");
}

/*
function habilitarBotonRegistrar1() {
	$(".registrar").prop("disabled", $("#motivo").val().trim() === "" || $("#motivo").val().length <= 2 );
  } */