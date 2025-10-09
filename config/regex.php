<?php
const c_regex =
    [
        'Nombre_NaturalCorto' => '/^[0-9 a-zA-ZáéíóúüñÑçÇ]{3,45}$/', //Para Nombre de Persona
        'Nombre_NaturalLargo' => '/^[0-9 a-zA-ZáéíóúüñÑçÇ]{4,90}$/', //Para Nombre o Titulo de alguna Cosa 
        'Nombre_Descripcion' => '/^[0-9a-zA-ZáéíóúüñÑçÇ\/\-.,# ]{10,100}$/', //Para campos descriptivos o direcciones
        'Cedula' => '/^[VE]{1}[-]{1}[0-9]{7,10}$/', //Para Cédula de Identidad
        'ID_Generado' => '/^[A-Z0-9]{3,5}[A-Z0-9]{3}[0-9]{8}[0-9]{0,6}[0-9]{0,2}$/', //Para Validar IDs Generados por el SIstema
        'Telefono' => '/^[0-9]{4}[-]{1}[0-9]{7}$/', //Para validar NÚmero de Teléfono
        'Correo' => '/^[-0-9A-Za-zç_]{6,36}[@]{1}[0-9a-zA-Z]{5,25}[.]{1}[com]{3}$/' //Validar Correo Electrónico
    ]
?>