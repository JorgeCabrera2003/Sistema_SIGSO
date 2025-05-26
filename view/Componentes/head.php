<!DOCTYPE html>
<html lang="es">
  <head>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $titulo?></title>
    <!-- Bootstrap CSS (for structure only) -->
    <link
      href="assets/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Custom Ya estoy en esta wea!!!!! -->
    <link rel="stylesheet" href="assets/css/main.css" />
    <!-- Font Awesome for icons -->
    <link
      rel="stylesheet"
      href="vendor/fortawesome/font-awesome/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/DataTables/datatables.css"
    />
    <script>
      const htmlElement = document.documentElement;

      const savedTheme = localStorage.getItem("theme");
      if (
        savedTheme === "dark" ||
        (!savedTheme && window.matchMedia("(prefers-color-scheme: dark)").matches)
      ) {
        htmlElement.classList.add("dark");
      }
    </script>
    <script src="vendor/components/jquery/jquery.min.js"></script>
    <script defer src="assets/js/main.js"></script>
    <script src="vendor/fortawesome/font-awesome/js/all.min.js"></script>
    <script src="assets/js/Chart.min.js"></script>
    <script defer src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/utils.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script src="vendor/datatables.net/datatables.net/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>