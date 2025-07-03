$(document).ready(function () {

	consultar();
	registrarEntrada();
	capaValidar();
	ConsultarPermisos();

	$("#enviar").on("click", async function () {

		
		$('#enviar').prop('disabled', false);

		let confirmacion = false;

		switch ($(this).text()) {

			case "Registrar":

				if (validarenvio()) {

					confirmacion = await confirmarAccion("Se registrará un Patch Panel", "¿Está seguro?", "question");

					if (confirmacion) {

						var datos = new FormData();
						datos.append('registrar', 'registrar');
						datos.append('id_equipo', $("#id_equipo").val());
						datos.append('codigo_patch_panel', $("#codigo_patch_panel").val());
						datos.append('puerto_patch_panel', $("#puerto_patch_panel").val());
						
						enviaAjax(datos);

					}
				}

			break;

			case "Modificar":

				if (validarenvio()) {

					confirmacion = await confirmarAccion("Se modificará el Punto de Conexióc", "¿Está seguro?", "question");

					if (confirmacion) {

						var datos = new FormData();
						datos.append('modificar', 'modificar');
						datos.append('id_punto_conexion', $("#id_punto_conexion").val());
						datos.append('id_equipo', $("#id_equipo").val());
						datos.append('codigo_patch_panel', $("#codigo_patch_panel").val());
						datos.append('puerto_patch_panel', $("#puerto_patch_panel").val());
						
						enviaAjax(datos);

					}

				}

			break;

			case "Eliminar":

				if (validarenvio()) {

					confirmacion = await confirmarAccion("Se eliminará el Punto de Conexión", "¿Está seguro?", "warning");

					if (confirmacion) {

						var datos = new FormData();
						datos.append('eliminar', 'eliminar');
						datos.append('id_punto_conexion', $("#id_punto_conexion").val());

						enviaAjax(datos);

					}

				}

			break;

			default:

				mensajes("question", 10000, "Error", "Acción desconocida: " + $(this).text()); //;

		}


		if (!validarenvio()) {

			$('#enviar').prop('disabled', false);

		} else {

			$('#enviar').prop('disabled', true);

		};

		if (!confirmacion) {

			$('#enviar').prop('disabled', false);

		}

	});

	$("#btn-registrar").on("click", function () { 

		limpia();
		cargarEquiposDisponibles();
		
		$("#modalTitleId").text("Registrar Punto de Conexión");
		$("#enviar").text("Registrar");
		$("#enviar").prop('disabled', false);
		$("#enviar").attr("title", "Registrar Punto de Conexión");
		$("#modal1").modal("show");

	}); 

});

function vistaPermiso(permisos = null) {

    if (!permisos || Object.keys(permisos).length == 0) {

        $('.modificar').remove();
        $('.eliminar').remove();

    } else {

        if (permisos['punto_conexion']['modificar']['estado'] == '0') {
            $('.modificar').remove();
        }

        if (permisos['punto_conexion']['eliminar']['estado'] == '0') {
            $('.eliminar').remove();
        }

    }
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

function crearDataTable(arreglo) {

	console.log(arreglo);

	if ($.fn.DataTable.isDataTable('#tabla1')) {

		$('#tabla1').DataTable().destroy();

	}

	$('#tabla1').DataTable({

		data: arreglo,

		columns: [
			{ data: 'id_punto_conexion' },
			{ data: 'codigo_patch_panel' },
			{ data: 'id_equipo' },
			{ data: 'puerto_patch_panel' },
			{
				data: null, render: function () {
					const botones = `<button onclick="rellenar(this, 0)" class="btn btn-update modificar" title="Modificar Punto de Conexión"><i class="fa-solid fa-pen-to-square"></i></button>
					<button onclick="rellenar(this, 1)" class="btn btn-danger eliminar" title="Eliminar Punto de Conexión"><i class="fa-solid fa-trash"></i></button>`;
					return botones;
				}
			}],
		order: [
			[1, 'asc'],
			[2, 'asc']
		],
		language: {
			url: idiomaTabla,
		}
	});
}

function capaValidar() {
  
    $("#id_equipo").on("change", function () {
        if ($(this).val() == "default") {
            $(this).addClass("is-invalid").removeClass("is-valid");
            $("#sid_equipo").text("Debe seleccionar un equipo");
        } else {
            $(this).addClass("is-valid").removeClass("is-invalid");
            $("#sid_equipo").text("");
        }
    });

  
    $("#codigo_patch_panel").on("change", function () {
        let codigo_patch_panel = $(this).val();
        if (codigo_patch_panel && codigo_patch_panel !== "default") {
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    get_puertos_patch_panel: true,
                    codigo_patch_panel: codigo_patch_panel
                },
                dataType: "json",
                success: function (puertos) {
                    let $select = $("#puerto_patch_panel");
                    $select.empty();
                    $select.append('<option selected value="default" disabled>Selecciona un Puerto</option>');
                    if (puertos.length > 0) {
                        puertos.forEach(function (puerto) {
                            $select.append('<option value="' + puerto + '">' + puerto + '</option>');
                        });
                    }
                }
            });
        } else {
            $("#puerto_patch_panel").empty().append('<option selected value="default" disabled>Selecciona un Puerto</option>');
        }
    });

    
    $("#puerto_patch_panel").on("input", function () {
        let val = $(this).val();
        if (!/^\d{1,2}$/.test(val) || parseInt(val) < 1) {
            $(this).addClass("is-invalid").removeClass("is-valid");
            $("#spuerto_patch_panel").text("Debe ser un número entre 1 y 99");
        } else {
            $(this).addClass("is-valid").removeClass("is-invalid");
            $("#spuerto_patch_panel").text("");
        }
    });
}

function validarenvio() {
    let valido = true;

   
    if ($("#id_equipo").val() == "default") {
        $("#id_equipo").addClass("is-invalid").removeClass("is-valid");
        $("#sid_equipo").text("Debe seleccionar un equipo");
        valido = false;
    }

    
    if ($("#codigo_patch_panel").val() == "default") {
        $("#codigo_patch_panel").addClass("is-invalid").removeClass("is-valid");
        $("#scodigo_patch_panel").text("Debe seleccionar un patch panel");
        valido = false;
    }

    
    let puerto = $("#puerto_patch_panel").val();
    if (!/^\d{1,2}$/.test(puerto) || parseInt(puerto) < 1) {
        $("#puerto_patch_panel").addClass("is-invalid").removeClass("is-valid");
        $("#spuerto_patch_panel").text("Debe ser un número entre 1 y 99");
        valido = false;
    } else {
        
        let cantidad = parseInt($("#codigo_patch_panel option:selected").data("cantidad"));
        if (parseInt(puerto) > cantidad) {
            Swal.fire({
                icon: 'error',
                title: 'Puerto inválido',
                text: 'El número de puerto no puede ser mayor que la cantidad de puertos del Patch Panel seleccionado (' + cantidad + ')'
            });
            $("#puerto_patch_panel").addClass("is-invalid").removeClass("is-valid");
            $("#spuerto_patch_panel").text("Puerto fuera de rango");
            valido = false;
        }
    }

    return valido;
}
function cargarEquiposDisponibles(equipo_actual = "") {
    $.ajax({
        url: "",
        type: "POST",
        data: {
            get_equipos_disponibles: true,
            equipo_actual: equipo_actual
        },
        dataType: "json",
        success: function (equipos) {
            let $select = $("#id_equipo");
            $select.empty();
            $select.append('<option selected value="default" disabled>Selecciona un Equipo</option>');
            if (equipos.length > 0) {
                equipos.forEach(function (equipo) {
                    $select.append('<option value="' + equipo.id_equipo + '">' + equipo.id_equipo + ' - ' + equipo.tipo_equipo + '</option>');
                });
            }
        }
    });
}
function limpia() {

	$("#id_punto_conexion").val(""); 

    $("#id_equipo").val("default");
    $("#codigo_patch_panel").val("default");
    $("#puerto_patch_panel").val("");

    $('#id_equipo').prop('disabled', false);
    $('#codigo_patch_panel').prop('disabled', false);
    $('#puerto_patch_panel').prop('disabled', false);

    $("#id_equipo").removeClass("is-valid is-invalid");
    $("#codigo_patch_panel").removeClass("is-valid is-invalid");
    $("#puerto_patch_panel").removeClass("is-valid is-invalid");

}

function rellenar(pos, accion) {

    limpia();
    let linea = $(pos).closest('tr');
    let equipo_actual = $(linea).find("td:eq(2)").text().trim();

    // Cargar equipos disponibles y seleccionar el actual cuando termine el AJAX
    cargarEquiposDisponibles(equipo_actual);

    // Espera un poco para asegurar que el AJAX terminó antes de seleccionar el valor
    setTimeout(function() {
        $("#id_equipo").val(equipo_actual);
    }, 300);

    $("#id_punto_conexion").val($(linea).find("td:eq(0)").text().trim());
    $("#codigo_patch_panel").val($(linea).find("td:eq(1)").text().trim());
    $("#puerto_patch_panel").val($(linea).find("td:eq(3)").text().trim());

    let codigo_patch_panel = $("#codigo_patch_panel").val();
    let puerto_patch_panel = $(linea).find("td:eq(3)").text().trim();
    if (codigo_patch_panel && codigo_patch_panel !== "default") {
        $.ajax({
            url: "",
            type: "POST",
            data: {
                get_puertos_patch_panel: true,
                codigo_patch_panel: codigo_patch_panel,
                puerto_actual: puerto_patch_panel // para permitir el puerto actual al modificar/eliminar
            },
            dataType: "json",
            success: function (puertos) {
                let $select = $("#puerto_patch_panel");
                $select.empty();
                $select.append('<option selected value="default" disabled>Selecciona un Puerto</option>');
                if (puertos.length > 0) {
                    puertos.forEach(function (puerto) {
                        $select.append('<option value="' + puerto + '">' + puerto + '</option>');
                    });
                }
                $select.val(puerto_patch_panel);
            }
        });
    }

    if (accion == 0) {
        $('#codigo_patch_panel').prop('disabled', false);
        $('#id_equipo').prop('disabled', false);
        $('#puerto_patch_panel').prop('disabled', false);
        $("#modalTitleId").text("Modificar Punto de Conexión");
        $("#enviar").attr("title", "Modificar Punto de Conexión");
        $("#enviar").text("Modificar");
    } else {
        $('#codigo_patch_panel').prop('disabled', true);
        $('#id_equipo').prop('disabled', true);
        $('#puerto_patch_panel').prop('disabled', true);
        $("#modalTitleId").text("Eliminar Punto de Conexión");
        $("#enviar").attr("title", "Eliminar Punto de Conexión");
        $("#enviar").text("Eliminar");
    }

    $('#enviar').prop('disabled', false);
    $("#modal1").modal("show");
}
