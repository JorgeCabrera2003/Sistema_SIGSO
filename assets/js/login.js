$(document).ready(function () {

    $("form").on("submit", function (e) {
        
        var cedula = $("input[name='CI']").val().trim();
        var clave = $("input[name='password']").val().trim();

        
        if (cedula === "" || clave === "") {

            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Completa el formulario",
                text: "Debes ingresar su cédula y/o contraseña.",
                confirmButtonText: "Aceptar"
            });

            return false;
        }

        if (typeof grecaptcha !== "undefined" && grecaptcha.getResponse() === "") {

            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Completa el reCAPTCHA",
                text: "Verifica que No eres un Robot.",
                confirmButtonText: "Aceptar"
            });
            return false;
        }

    });

});