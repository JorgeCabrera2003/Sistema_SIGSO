let usuarioGlobal = null;
let timeoutCodigo = null;

$(document).on('input', 'input[name="CI"]', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 8);
});

$(document).on('input', '#nueva_clave, #confirmar_clave', function () {
    // Solo permite letras, números y los símbolos .-_*!@#$%=
    this.value = this.value.replace(/[^a-zA-Z0-9.\-_*!@#$%=]/g, '');
});

$(document).ready(function () {

    $("#recuperar-form").on("submit", function (e) {
        e.preventDefault();

        // Obtener cédula completa
        var particle = $("select[name='particle']").val();
        var ci = $("input[name='CI']").val().trim();
        

        // Validación básica
        if (ci === "") {

            Swal.fire({
                icon: "error",
                title: "Completa el formulario",
                text: "Debes ingresar la cédula.",
                confirmButtonText: "Aceptar"
            });
            
            return;
        }

        // Preparar datos para AJAX
        var datos = new FormData();
        datos.append('consultar', 'consultar');
        datos.append('particle', particle);
        datos.append('CI', ci);

        enviaAjax(datos);

    });

    $("#btn-volver-cedula").on("click", function () {
        $("input[name='CI']").val('');
        $("#codigo_recuperacion").val('');
        $("#scodigo_bien").text('');
        $("#perfil-preview").hide();
        // Mostrar formulario de cédula y ocultar el de código
        $("#form-cedula").slideDown();
        $("#form-codigo-recuperacion").slideUp();

        $("#btn-continuar").prop("disabled", true).removeClass("btn-primary").addClass("btn-light");

        if (timeoutCodigo) {
        clearTimeout(timeoutCodigo);
        timeoutCodigo = null;
        }
        $("#btn-enviar-codigo").prop("disabled", false);

    });

    $("#btn-volver-cedula2").on("click", function () {
        // Limpiar campos de clave
        $("#nueva_clave").val('');
        $("#confirmar_clave").val('');
        // Ocultar form de clave y mostrar el de cédula
        $("#form-nueva-clave").slideUp();
        $("#perfil-preview").hide();
        $("#form-cedula").slideDown();

        $("#btn-continuar").prop("disabled", true).removeClass("btn-primary").addClass("btn-light");

        if (timeoutCodigo) {
        clearTimeout(timeoutCodigo);
        timeoutCodigo = null;
        }
        $("#btn-enviar-codigo").prop("disabled", false);

    });

    $("#btn-enviar-codigo").on("click", function () {
        let $btn = $(this);
        let correo = usuarioGlobal.correo;
        let nombre = usuarioGlobal.nombres + " " + usuarioGlobal.apellidos;

        // Desactiva el botón de inmediato y agrega animación
        $btn.prop("disabled", true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...');

        $.ajax({
            url: '', // El mismo controlador
            type: 'POST',
            data: {
                enviar_codigo: true,
                correo: correo,
                nombre: nombre
            },
            success: function (respuesta) {
                let res = JSON.parse(respuesta);
                Swal.fire({
                    icon: res.estado === 1 ? "success" : "error",
                    title: res.estado === 1 ? "¡Listo!" : "Espera 1min antes de solicitar otro",
                    text: res.mensaje
                });
                // Espera 1 minuto antes de habilitar el botón
                setTimeout(function () {
                    $btn.prop("disabled", false)
                        .html('Solicitar código <i class="fa-solid fa-paper-plane"></i>');
                }, 1 * 60 * 1000); // 1 minuto en ms

                if (res.estado === 1) {
                    $("#btn-continuar").prop("disabled", false).removeClass("btn-light").addClass("btn-primary");
                }
            },
            error: function () {
                Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
                // Habilita el botón si hay error
                $btn.prop("disabled", false)
                    .html('Solicitar código <i class="fa-solid fa-paper-plane"></i>');
            }
        });
        

    });

    $("#btn-continuar").on("click", function () {
        // Obtener el código ingresado por el usuario
        let codigoIngresado = $("#codigo_recuperacion").val().trim();

        if (codigoIngresado === "") {
            Swal.fire({
                icon: "error",
                title: "Código vacío",
                text: "Por favor ingresa el código recibido en tu correo."
            });
            return;
        }

        // Enviar el código al backend para validación
        $.ajax({
            url: '', // El mismo controlador
            type: 'POST',
            data: {
                validar_codigo: true,
                codigo: codigoIngresado
            },
            success: function (respuesta) {
                let res = JSON.parse(respuesta);
                if (res.estado === 1) {
                    // Código correcto, mostrar formulario de nueva clave
                    $("#form-codigo-recuperacion").slideUp();
                    $("#form-nueva-clave").slideDown();

                    let html3 = `
                        <div class="text-center" style="text-align: justify;">
                            <span class="fw-bold" style="font-size:1.1rem;">
                                Introduzca su nueva contraseña para continuar.
                            </span>
                            <br><hr>
                        </div>
                    `;
                    $("#perfil-preview").html(html3).show();
                } else {
                    // Código incorrecto
                    Swal.fire({
                        icon: "error",
                        title: "Código incorrecto",
                        text: res.mensaje || "El código ingresado no es válido. Intenta nuevamente."
                    });
                }
            },
            error: function () {
                Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
            }
        });
    });

    $("#btn-guardar-clave").on("click", function () {
        let clave = $("#nueva_clave").val();
        let confirmar = $("#confirmar_clave").val();
        let regex = /^[a-zA-Z0-9.\-_*!@#$%=]+$/;

        if (!clave || !confirmar) {
            Swal.fire("Error", "Debes completar ambos campos.", "error");
            return;
        }
        if (!regex.test(clave)) {
            Swal.fire("Error", "La clave solo puede contener letras, números y los símbolos .-_*!@#$%=", "error");
            return;
        }
        if (clave !== confirmar) {
            Swal.fire("Error", "Las claves no coinciden.", "error");
            return;
        }

        // Enviar AJAX para guardar la nueva clave
        $.ajax({
            url: '', // El mismo controlador
            type: 'POST',
            data: {
                modificar: true,
                cedula: usuarioGlobal.cedula,
                clave: clave
            },
            success: function (respuesta) {
                let res = JSON.parse(respuesta);
                if (res.estado === 1) {
                    Swal.fire("¡Listo!", "Contraseña cambiada exitosamente.", "success").then(() => {
                        window.location.href = "?page=login";
                    });
                } else {
                    Swal.fire("Error", res.mensaje || "No se pudo cambiar la contraseña.", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
            }
        });
    });
    
});



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
		timeout: 10000,

            success: function (respuesta) {

                try {

                    var res = JSON.parse(respuesta);
                    if (res.bool === 1 && res.arreglo) {
                        mostrarPerfil(res.arreglo);
                        $("#btn-siguiente").prop("disabled", false);
                    } else {
                        mostrarMensaje("Usuario No encontrado.");
                        $("#btn-siguiente").prop("disabled", true);
                    }

                } catch (e) {

                    mostrarMensaje("Error al procesar la respuesta.");

                }
            },
            error: function () {
                mostrarMensaje("Error de conexión.");
            }
        });

}

// Función para mostrar los datos del usuario
function mostrarPerfil(usuario) {

    usuarioGlobal = usuario;

    // Construye el HTML con los datos del usuario
    let html = `
        <div class="alert alert-info mb-2">
            <strong>${usuario.nombres} ${usuario.apellidos}</strong><br>
            Usuario: ${usuario.nombre_usuario}<br>
            
        </div>
    `;

    let html2 = `
        <div class="text-center" style="text-align: justify;">
            <div class="alert alert-info mb-2">
                <span class="fw-bold display-6">${usuario.nombres} ${usuario.apellidos}</span><br>
                <span class="fw-bold" style="font-size:1.1rem;">${usuario.cedula}</span><br>
                <span class="text-muted" style="font-size:1rem;">Usuario: ${usuario.nombre_usuario}</span>
            </div>
            <hr>
            <span style="font-size:1.1rem;">
                Se enviará a su correo 
                <u>
                    <i title="su correo">${usuario.correo}</i>
                </u> 
                un código de confirmación para cambiar su contraseña.<br>
                Por favor de click en "Solicitar Código" e introduzcalo a continuación:
            </span>
            <br><hr>
        </div>
    `;


    // SweetAlert de confirmación con datos del usuario
    Swal.fire({

        title: "<strong>¿Eres Tú? </strong></br> Confirma tú Identidad",
        html: `${html}`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, soy yo",
        cancelButtonText: "No",
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-primary ms-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false

    }).then((result) => {

        if (result.isConfirmed) {

            $("#perfil-preview").html(html2).show();
            // Ocultar el grupo de cédula
            $("#form-cedula").slideUp();
            // Mostrar el formulario de código de recuperación
            $("#form-codigo-recuperacion").slideDown();
            // Limpiar el input del código y feedback
            $("#codigo_recuperacion").val('');
            $("#scodigo_bien").text('');
        } else {
            $("#perfil-preview").hide();
            $("#form-codigo-recuperacion").slideUp();
            $("#form-cedula").slideDown();
        }
    });
}

// Función para mostrar mensajes de error
function mostrarMensaje(msg) {
    Swal.fire({
        icon: "error",
        title: msg,
        text: "No se encontro un Usuario con esta cédula.",
        confirmButtonText: "Aceptar"
    });
    $("#perfil-preview").hide();
}