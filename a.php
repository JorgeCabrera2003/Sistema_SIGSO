<?php

$clave = "123456789";

$hash = password_hash($clave, PASSWORD_DEFAULT);

echo $hash;

?>