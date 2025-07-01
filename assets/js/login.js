$(document).ready(function () {
    $("form").on("submit", function (e) {
        
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