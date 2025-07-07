const temasAyuda = {
  usuarios: {
    titulo: "Gestión de Usuarios",
    contenido: "",
    subtitulos: [
      { id: "crear-usuario", texto: "Crear Usuario" },
      { id: "cambiar-clave", texto: "Cambiar Clave" },
      { id: "recuperar-acceso", texto: "Recuperar Acceso" },
      { id: "foto-perfil", texto: "Subir y/o Cambiar Foto de Perfil" }
    ]
  },
  solicitudes: {
    titulo: "Gestión de Solicitudes",
    contenido: "",
    subtitulos: [
      { id: "crear-solicitud", texto: "Crear Nueva Solicitud" },
      { id: "mis-solicitudes", texto: "Consultar Mis Solicitudes" },
      { id: "gestion-solicitudes", texto: "Gestión de Solicitudes" },
      { id: "hoja-servicios", texto: "Gestión de Hoja de Servicios" },
      { id: "tipo-servicio", texto: "Tipo de Servicio" }
    ]
  },
  seguridad: {
    titulo: "Seguridad del Sistema",
    contenido: "",
    subtitulos: [
      { id: "roles-permisos", texto: "Roles y Permisos" },
      { id: "bitacora", texto: "Bitácora" },
      { id: "backup", texto: "Backup" }
    ]
  },
  inventario: {
    titulo: "Gestión de Inventario",
    contenido: "",
    subtitulos: [
      { id: "bienes", texto: "Bienes" },
      { id: "tipo-bienes", texto: "Tipo Bienes" },
      { id: "equipos", texto: "Equipos" },
      { id: "patch-panel", texto: "Patch Panel" },
      { id: "switch", texto: "Switch" },
      { id: "marca", texto: "Marca" },
      { id: "materiales", texto: "Materiales" }
    ]
  },
  personal: {
    titulo: "Personal e Infraestructura",
    contenido: "",
    subtitulos: [
      { id: "empleado", texto: "Empleado" },
      { id: "ente", texto: "Ente" },
      { id: "dependencia", texto: "Dependencia" },
      { id: "unidad", texto: "Unidad" },
      { id: "cargo", texto: "Cargo" },
      { id: "tecnico", texto: "Técnico" },
      { id: "usuario-personal", texto: "Usuario" },
      { id: "piso", texto: "Piso" },
      { id: "edificio", texto: "Edificio" }
    ]
  },
  redes: {
    titulo: "Gestión de Redes",
    contenido: "",
    subtitulos: [
      { id: "patch-panel-red", texto: "Patch Panel" },
      { id: "switch-red", texto: "Switch" },
      { id: "asignacion-puertos", texto: "Asignación de Puertos" },
      { id: "punto-conexion", texto: "Punto de Conexión" },
      { id: "interconexion", texto: "Interconexión" }
    ]
  }
};

const buscador = document.getElementById('buscador');
const resultadoBusqueda = document.getElementById('resultado-busqueda');
buscador.addEventListener('input', function() {
  const texto = this.value.trim().toLowerCase();
  if (!texto) {
    resultadoBusqueda.innerHTML = '';
    return;
  }
  let resultados = [];

  // Buscar en los temas y subtítulos (como ya tienes)
  for (const [key, tema] of Object.entries(temasAyuda)) {
    if (tema.titulo.toLowerCase().includes(texto)) {
      resultados.push(`<li><a href="#" onclick="mostrarAyudaPorTema('${key}');return false;">${tema.titulo}</a></li>`);
    }
    if (tema.subtitulos) {
      for (const sub of tema.subtitulos) {
        if (sub.texto.toLowerCase().includes(texto)) {
          resultados.push(`<li><a href="#" onclick="mostrarAyudaPorTemaYSub('${key}','${sub.id}');return false;">${tema.titulo} - ${sub.texto}</a></li>`);
        }
      }
    }
  }

  // Buscar en las preguntas frecuentes del acordeón
  const acordeon = document.getElementById('faqAccordion');
  if (acordeon) {
    const items = acordeon.querySelectorAll('.accordion-item');
    items.forEach(item => {
      const btn = item.querySelector('.accordion-button');
      const body = item.querySelector('.accordion-body');
      if (btn && body) {
        const pregunta = btn.textContent.trim();
        const respuesta = body.textContent.trim();
        if (
          pregunta.toLowerCase().includes(texto) ||
          respuesta.toLowerCase().includes(texto)
        ) {
          // Generar un enlace que expanda la pregunta
          const target = btn.getAttribute('data-bs-target') || btn.getAttribute('aria-controls');
          resultados.push(`<li><a href="#" onclick="abrirPreguntaFrecuente('${target}');return false;">${pregunta}</a></li>`);
        }
      }
    });
  }

  if (resultados.length > 0) {
    resultadoBusqueda.innerHTML = `<ul style="list-style:none;padding-left:0">${resultados.join('')}</ul>`;
  } else {
    resultadoBusqueda.textContent = 'No se encontraron coincidencias';
  }
});

// Función para expandir la pregunta frecuente encontrada
function abrirPreguntaFrecuente(targetId) {
  // Cierra todas las preguntas abiertas
  document.querySelectorAll('.accordion-collapse.show').forEach(el => el.classList.remove('show'));
  // Abre la pregunta encontrada
  const target = document.querySelector(targetId.startsWith('#') ? targetId : '#' + targetId);
  if (target) {
    target.classList.add('show');
    // Scroll al acordeón
    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
}

function mostrarAyuda(card) {
  const tema = card.getAttribute('data-tema');
  let titulo = "Ayuda";
  let contenido = "";

  if (tema === "usuarios") {
    titulo = "Gestión de Usuarios";
    contenido = `
    <div class="row">
      <div class="col-md-4">
        <nav id="navbar-usuarios" class="navbar navbar-light bg-light px-3 mb-3">
          <span class="navbar-brand mb-2 h5">Gestión de Usuarios</span>
          <nav class="nav nav-pills flex-column">
            <a class="nav-link" href="#crear-usuario">Crear Usuario</a>
            <a class="nav-link" href="#cambiar-clave">Cambiar Clave</a>
            <a class="nav-link" href="#recuperar-acceso">Recuperar Acceso</a>
            <a class="nav-link" href="#foto-perfil">Subir/Cambiar Foto de Perfil</a>
          </nav>
        </nav>
      </div>
      <div class="col-md-8" style="max-height:60vh; overflow-y:auto;" data-bs-spy="scroll" data-bs-target="#navbar-usuarios" data-bs-offset="0" tabindex="0">
        <h4 id="crear-usuario">Crear Usuario</h4>
        <p>El proceso de creación de usuarios en SIGSO requiere permisos administrativos y sigue un flujo de validación estricto.</p>
        <p><strong>Requisitos previos:</strong></p>
        <ul>
          <li>Tener permisos de "registrar" en el módulo de usuarios</li>
          <li>Acceso al módulo de Seguridad → Usuarios</li>
          <li>Información completa del nuevo usuario</li>
        </ul>
        <p><strong>Proceso paso a paso:</strong></p>
        <ol>
          <li><strong>Acceso al módulo:</strong>
            <ul>
              <li>Navegar al menú "Seguridad" en la barra lateral</li>
              <li>Seleccionar "Usuarios" del submenú</li>
              <li>Verificar que aparezca el botón "Registrar Usuario"</li>
            </ul>
          </li>
          <li><strong>Iniciar registro:</strong>
            <ul>
              <li>Hacer clic en "Registrar Usuario"</li>
              <li>Se abrirá el modal de registro</li>
            </ul>
          </li>
          <li><strong>Completar información personal:</strong>
            <ul>
              <li><strong>Nombre de usuario:</strong> 4-45 caracteres alfanuméricos (incluye acentos y guión bajo)</li>
              <li><strong>Cédula:</strong> Formato V-12345678 o E-12345678 (7-10 dígitos)</li>
              <li><strong>Nombre:</strong> 4-45 caracteres (incluye acentos)</li>
              <li><strong>Apellido:</strong> 4-45 caracteres (incluye acentos)</li>
            </ul>
          </li>
          <li><strong>Información de contacto:</strong>
            <ul>
              <li><strong>Correo electrónico:</strong> Formato válido terminado en .com</li>
              <li><strong>Teléfono:</strong> Formato 0000-0000000 (4 dígitos, guión, 7 dígitos)</li>
            </ul>
          </li>
          <li><strong>Configuración de acceso:</strong>
            <ul>
              <li><strong>Rol:</strong> Seleccionar de la lista de roles disponibles</li>
              <li><strong>Contraseña:</strong> 8-45 caracteres (letras, números, símbolos especiales)</li>
              <li><strong>Confirmar contraseña:</strong> Debe coincidir exactamente</li>
            </ul>
          </li>
          <li><strong>Validación y guardado:</strong>
            <ul>
              <li>El sistema valida todos los campos automáticamente</li>
              <li>Se muestra mensaje de error si hay datos inválidos</li>
              <li>Al guardar exitosamente, se registra en la bitácora</li>
              <li>El nuevo usuario aparece en la tabla de usuarios</li>
            </ul>
          </li>
        </ol>
        <h4 id="cambiar-clave">Cambiar Clave</h4>
        <p>Los usuarios pueden cambiar su contraseña desde su perfil personal. El sistema fuerza el cambio si la contraseña es igual a la cédula.</p>
        <p><strong>Acceso al cambio de contraseña:</strong></p>
        <ol>
          <li><strong>Desde el perfil de usuario:</strong>
            <ul>
              <li>Hacer clic en tu avatar en la esquina superior derecha</li>
              <li>Seleccionar "Mi Perfil" del menú desplegable</li>
              <li>Ir a la pestaña "Cambiar Contraseña"</li>
            </ul>
          </li>
          <li><strong>Cambio forzado (primera vez):</strong>
            <ul>
              <li>Si tu contraseña es igual a tu cédula, serás redirigido automáticamente</li>
              <li>La pestaña "Cambiar Contraseña" estará activa por defecto</li>
            </ul>
          </li>
        </ol>
        <p><strong>Proceso de cambio:</strong></p>
        <ol>
          <li><strong>Ingresar nueva contraseña:</strong>
            <ul>
              <li>Mínimo 8 caracteres, máximo 20</li>
              <li>Puede incluir letras mayúsculas y minúsculas</li>
              <li>Números y símbolos especiales permitidos</li>
            </ul>
          </li>
          <li><strong>Confirmar nueva contraseña:</strong>
            <ul>
              <li>Repetir exactamente la contraseña anterior</li>
              <li>El sistema valida que ambas coincidan</li>
            </ul>
          </li>
          <li><strong>Guardar cambios:</strong>
            <ul>
              <li>Hacer clic en "Cambiar contraseña"</li>
              <li>Aparece confirmación con SweetAlert</li>
              <li>La contraseña se encripta con password_hash()</li>
              <li>Se muestra mensaje de éxito</li>
              <li>Se registra la acción en bitácora</li>
            </ul>
          </li>
        </ol>
        <h4 id="recuperar-acceso">Recuperar Acceso</h4>
        <p>El sistema SIGSO maneja la recuperación de acceso a través de diferentes métodos según la situación del usuario.</p>
        <p><strong>Métodos de recuperación:</strong></p>
        <p><strong>1. Recuperación por administrador:</strong></p>
        <ul>
          <li><strong>Cuándo usar:</strong> Cuando olvidas completamente tu contraseña</li>
          <li><strong>Proceso:</strong>
            <ul>
              <li>Contactar al administrador del sistema</li>
              <li>Proporcionar tu cédula y nombre completo</li>
              <li>El administrador verifica tu identidad</li>
              <li>El administrador puede modificar tu usuario y asignar nueva contraseña</li>
              <li>Recibirás las nuevas credenciales de forma segura</li>
            </ul>
          </li>
        </ul>
        <p><strong>2. Cambio automático por contraseña por defecto:</strong></p>
        <ul>
          <li><strong>Cuándo ocurre:</strong> Cuando tu contraseña es igual a tu cédula</li>
          <li><strong>Proceso automático:</strong>
            <ul>
              <li>Al iniciar sesión, el sistema detecta la contraseña por defecto</li>
              <li>Te redirige automáticamente a la página de perfil</li>
              <li>La pestaña "Cambiar Contraseña" se activa automáticamente</li>
              <li>Debes cambiar la contraseña antes de usar el sistema</li>
            </ul>
          </li>
        </ul>
        <p><strong>3. Verificación de sesión:</strong></p>
        <ul>
          <li><strong>Protección automática:</strong>
            <ul>
              <li>El sistema verifica tu sesión en cada página</li>
              <li>Si la sesión expira o es inválida, te redirige al login</li>
              <li>Se muestra mensaje "Sesión Finalizada"</li>
              <li>Debes iniciar sesión nuevamente</li>
            </ul>
          </li>
        </ul>
        <h4 id="foto-perfil">Subir y/o Cambiar Foto de Perfil</h4>
        <p>El sistema permite gestionar fotos de perfil personalizadas con validación de archivos y almacenamiento seguro.</p>
        <p><strong>Acceso a gestión de fotos:</strong></p>
        <ol>
          <li>Ir a tu perfil de usuario (avatar → Mi Perfil)</li>
          <li>Seleccionar la pestaña "Editar Perfil"</li>
          <li>Localizar la sección "Foto de Perfil"</li>
        </ol>
        <p><strong>Subir nueva foto:</strong></p>
        <ol>
          <li><strong>Seleccionar archivo:</strong>
            <ul>
              <li>Hacer clic en el botón azul con icono de flecha hacia arriba</li>
              <li>Se abre el explorador de archivos</li>
              <li>Seleccionar imagen (JPG, PNG, GIF)</li>
              <li>El nombre del archivo aparece debajo de los botones</li>
            </ul>
          </li>
          <li><strong>Procesamiento automático:</strong>
            <ul>
              <li>El archivo se renombra automáticamente con tu cédula</li>
              <li>Se guarda en la carpeta assets/img/foto-perfil/</li>
              <li>Formato final: cedula.extension (ej: V12345678.jpg)</li>
            </ul>
          </li>
          <li><strong>Confirmar cambios:</strong>
            <ul>
              <li>Hacer clic en "Guardar cambios"</li>
              <li>El sistema procesa la imagen</li>
              <li>Se actualiza la base de datos</li>
              <li>Aparece mensaje de confirmación</li>
              <li>La nueva foto se muestra inmediatamente</li>
            </ul>
          </li>
        </ol>
        <p><strong>Eliminar foto actual:</strong></p>
        <ol>
          <li><strong>Proceso de eliminación:</strong>
            <ul>
              <li>Hacer clic en el botón rojo con icono de basura</li>
              <li>Aparece confirmación con SweetAlert</li>
              <li>Confirmar "Sí, eliminar"</li>
            </ul>
          </li>
          <li><strong>Resultado:</strong>
            <ul>
              <li>Se elimina el archivo físico del servidor</li>
              <li>Se restaura la imagen por defecto (default-profile.jpg)</li>
              <li>Se actualiza la base de datos</li>
              <li>Aparece mensaje de confirmación</li>
            </ul>
          </li>
        </ol>
        <p><strong>Consideraciones técnicas:</strong></p>
        <ul>
          <li><strong>Formatos soportados:</strong> JPG, JPEG, PNG, GIF</li>
          <li><strong>Ubicación:</strong> assets/img/foto-perfil/</li>
          <li><strong>Nomenclatura:</strong> cedula.extension</li>
          <li><strong>Imagen por defecto:</strong> assets/img/default-profile.jpg</li>
          <li><strong>Validación:</strong> Se verifica que el archivo exista antes de eliminar</li>
        </ul>
        <!-- <hr>
        <h5>Preguntas Frecuentes - Usuarios</h5>
        <p><strong>¿Cómo puedo editar mi información personal?</strong></p>
        <p>Ve a tu perfil (avatar → Mi Perfil), selecciona "Editar Perfil" y modifica los campos nombre, apellido, correo y teléfono. Luego haz clic en "Guardar cambios".</p>
        <p><strong>¿Por qué no puedo crear usuarios?</strong></p>
        <p>La creación de usuarios requiere permisos específicos. Solo usuarios con rol de administrador o con permisos de "registrar" en el módulo de usuarios pueden crear nuevas cuentas.</p>
        <p><strong>¿Qué hago si mi contraseña no cumple los requisitos?</strong></p>
        <p>Las contraseñas deben tener entre 8-45 caracteres e incluir letras, números y símbolos especiales. Evita usar solo tu nombre o cédula.</p>
        <p><strong>¿Puedo usar cualquier formato de imagen para mi perfil?</strong></p>
        <p>El sistema acepta JPG, JPEG, PNG y GIF. La imagen se renombra automáticamente con tu cédula para mantener la organización.</p>
        <p><strong>¿Qué pasa si olvido mi contraseña?</strong></p>
        <p>Debes contactar al administrador del sistema quien puede restablecer tu contraseña. No existe recuperación automática por email.</p>
        <p><strong>¿Por qué me obliga a cambiar la contraseña al iniciar sesión?</strong></p>
        <p>Esto ocurre cuando tu contraseña es igual a tu cédula (contraseña por defecto). Es una medida de seguridad obligatoria.</p> -->
      </div>
    </div>
    `;
  }

  if (tema === "solicitudes") {
  titulo = "Gestión de Solicitudes";
  contenido = `
  <div class="row">
    <div class="col-md-4">
      <nav id="navbar-solicitudes" class="navbar navbar-light bg-light px-3 mb-3">
        <span class="navbar-brand mb-2 h5">Gestión de Solicitudes</span>
        <nav class="nav nav-pills flex-column">
          <a class="nav-link" href="#crear-solicitud">Crear Nueva Solicitud</a>
          <a class="nav-link" href="#mis-solicitudes">Consultar Mis Solicitudes</a>
          <a class="nav-link" href="#gestion-solicitudes">Gestión de Solicitudes</a>
          <a class="nav-link" href="#hoja-servicios">Gestión de Hoja de Servicios</a>
          <a class="nav-link" href="#tipo-servicio">Tipo de Servicio</a>
        </nav>
      </nav>
    </div>
    <div class="col-md-8" style="max-height:60vh; overflow-y:auto;" data-bs-spy="scroll" data-bs-target="#navbar-solicitudes" data-bs-offset="0" tabindex="0">

      <h4 id="crear-solicitud">Crear Nueva Solicitud</h4>
      <p>El sistema permite crear solicitudes de servicios técnicos de manera organizada, con asignación automática de técnicos y generación de hojas de servicio.</p>
      <p><strong>Métodos de creación disponibles:</strong></p>
      <p><strong>1. Desde "Mis Solicitudes" (Usuario final):</strong></p>
      <ol>
        <li><strong>Acceso:</strong>
          <ul>
            <li>Ir al menú "Mis Solicitudes" en la barra lateral</li>
            <li>Hacer clic en el botón "Hacer Solicitud"</li>
            <li>Se abre el modal de solicitud</li>
          </ul>
        </li>
        <li><strong>Completar información básica:</strong>
          <ul>
            <li><strong>Motivo:</strong> Descripción del problema (3-30 caracteres, solo letras, números, acentos y algunos símbolos)</li>
            <li><strong>Equipo (opcional):</strong> Seleccionar equipo afectado de tu lista personal</li>
          </ul>
        </li>
        <li><strong>Procesamiento automático:</strong>
          <ul>
            <li>El sistema asigna automáticamente tu cédula como solicitante</li>
            <li>Se crea la solicitud con estado "Pendiente"</li>
            <li>Se registra la fecha y hora actual</li>
            <li>Se genera notificación al área técnica</li>
          </ul>
        </li>
      </ol>
      <p><strong>2. Desde "Gestión de Solicitudes" (Administrador):</strong></p>
      <ol>
        <li><strong>Acceso administrativo:</strong>
          <ul>
            <li>Navegar a "Servicios" → "Solicitudes"</li>
            <li>Hacer clic en "Nueva Solicitud"</li>
            <li>Se abre el modal completo de solicitud</li>
          </ul>
        </li>
        <li><strong>Selección organizacional:</strong>
          <ul>
            <li><strong>Dependencia:</strong> Seleccionar dependencia organizacional</li>
            <li><strong>Solicitante:</strong> Elegir empleado (se carga según dependencia seleccionada)</li>
            <li><strong>Equipo:</strong> Opcional, seleccionar equipo específico</li>
          </ul>
        </li>
        <li><strong>Configuración del servicio:</strong>
          <ul>
            <li><strong>Área de servicio:</strong> Soporte técnico, Electrónica, Redes, o Telefonía</li>
            <li><strong>Técnico asignado:</strong> Seleccionar técnico específico (se cargan por área con balanceo de carga)</li>
            <li><strong>Motivo:</strong> Descripción detallada del problema</li>
          </ul>
        </li>
        <li><strong>Creación automática de hoja de servicio:</strong>
          <ul>
            <li>Se genera automáticamente una hoja de servicio vinculada</li>
            <li>Se asigna al técnico seleccionado o queda disponible para tomar</li>
            <li>Se establece el tipo de servicio según el área</li>
          </ul>
        </li>
      </ol>

      <h4 id="mis-solicitudes">Consultar Mis Solicitudes</h4>
      <p>Panel personal para que cada usuario pueda monitorear sus solicitudes de servicio y su progreso.</p>
      <p><strong>Acceso y visualización:</strong></p>
      <ol>
        <li><strong>Navegación:</strong>
          <ul>
            <li>Ir al menú "Mis Solicitudes"</li>
            <li>Se carga automáticamente la tabla con tus solicitudes</li>
            <li>Filtrado automático por tu cédula de usuario</li>
          </ul>
        </li>
        <li><strong>Información mostrada:</strong>
          <ul>
            <li><strong>ID:</strong> Número único de la solicitud</li>
            <li><strong>Motivo:</strong> Descripción del problema reportado</li>
            <li><strong>Fecha Reporte:</strong> Cuándo se creó la solicitud</li>
            <li><strong>Estado:</strong> Pendiente, En proceso, o Finalizado</li>
            <li><strong>Resultado:</strong> Descripción de la solución (cuando esté disponible)</li>
          </ul>
        </li>
        <li><strong>Estados de solicitud:</strong>
          <ul>
            <li><strong>Pendiente:</strong> Solicitud creada, esperando asignación de técnico</li>
            <li><strong>En proceso:</strong> Técnico asignado, trabajando en la solución</li>
            <li><strong>Finalizado:</strong> Servicio completado con resultado documentado</li>
          </ul>
        </li>
      </ol>
      <p><strong>Funcionalidades disponibles:</strong></p>
      <ul>
        <li><strong>Actualización automática:</strong> La tabla se actualiza dinámicamente</li>
        <li><strong>Historial completo:</strong> Puedes ver todas tus solicitudes pasadas y presentes</li>
        <li><strong>Seguimiento en tiempo real:</strong> El estado se actualiza conforme avanza el técnico</li>
      </ul>

      <h4 id="gestion-solicitudes">Gestión de Solicitudes</h4>
      <p>Panel administrativo completo para gestionar todas las solicitudes del sistema con funciones avanzadas de administración.</p>
      <p><strong>Acceso y permisos:</strong></p>
      <ul>
        <li>Requiere permisos de "ver_solicitud" en el módulo de solicitudes</li>
        <li>Acceso desde "Servicios" → "Solicitudes"</li>
        <li>Vista completa de todas las solicitudes del sistema</li>
      </ul>
      <p><strong>Funciones administrativas disponibles:</strong></p>
      <p><strong>1. Visualización completa:</strong></p>
      <ul>
        <li><strong>Tabla DataTable:</strong> Con paginación, búsqueda y ordenamiento</li>
        <li><strong>Columnas mostradas:</strong> ID, Solicitante, Cédula, Dependencia, Equipo, Motivo, Estado, Fecha, Resultado, Acciones</li>
        <li><strong>Filtros avanzados:</strong> Por estado, fecha, dependencia, técnico</li>
      </ul>
      <p><strong>2. Operaciones CRUD:</strong></p>
      <ol>
        <li><strong>Modificar solicitudes:</strong>
          <ul>
            <li>Editar motivo, solicitante, equipo asociado</li>
            <li>Cambiar área de servicio y técnico asignado</li>
            <li>Actualización automática de hoja de servicio vinculada</li>
          </ul>
        </li>
        <li><strong>Eliminar solicitudes:</strong>
          <ul>
            <li>Eliminación lógica (soft delete) manteniendo historial</li>
            <li>Se marca con estatus = 0 en la base de datos</li>
            <li>Registro en bitácora de la acción</li>
          </ul>
        </li>
        <li><strong>Restaurar solicitudes:</strong>
          <ul>
            <li>Recuperar solicitudes eliminadas</li>
            <li>Vista especial de "Solicitudes Eliminadas"</li>
            <li>Restauración con un clic</li>
          </ul>
        </li>
      </ol>
      <p><strong>3. Funciones especiales:</strong></p>
      <ul>
        <li><strong>Redirección de hojas:</strong> Reasignar servicios a diferentes técnicos o áreas</li>
        <li><strong>Balanceo de carga:</strong> Asignación automática basada en carga de trabajo</li>
        <li><strong>Generación de reportes:</strong> Reportes PDF por rango de fechas</li>
      </ul>

      <h4 id="hoja-servicios">Gestión de Hoja de Servicios</h4>
      <p>Sistema técnico para que los técnicos gestionen la ejecución real de los servicios solicitados.</p>
      <p><strong>Flujo de trabajo técnico:</strong></p>
      <p><strong>1. Tomar hoja de servicio:</strong></p>
      <ol>
        <li><strong>Acceso:</strong>
          <ul>
            <li>Ir a "Servicios" → "Servicios" (Gestión de Hojas)</li>
            <li>Ver hojas disponibles según tu área de especialización</li>
            <li>Filtrar por estado "Activo" y sin técnico asignado</li>
          </ul>
        </li>
        <li><strong>Asignación:</strong>
          <ul>
            <li>Hacer clic en "Tomar" en la hoja deseada</li>
            <li>Se asigna automáticamente tu cédula como técnico responsable</li>
            <li>La solicitud cambia a estado "En proceso"</li>
          </ul>
        </li>
      </ol>
      <p><strong>2. Ejecutar el servicio:</strong></p>
      <ol>
        <li><strong>Registrar detalles técnicos:</strong>
          <ul>
            <li>Documentar componentes trabajados</li>
            <li>Describir procedimientos realizados</li>
            <li>Registrar materiales utilizados con cantidades</li>
          </ul>
        </li>
        <li><strong>Control de inventario:</strong>
          <ul>
            <li>Selección automática de materiales del stock</li>
            <li>Validación de disponibilidad antes de usar</li>
            <li>Actualización automática de inventario</li>
            <li>Generación de movimientos de materiales</li>
          </ul>
        </li>
      </ol>
      <p><strong>3. Finalizar servicio:</strong></p>
      <ol>
        <li><strong>Completar documentación:</strong>
          <ul>
            <li>Escribir resultado final del servicio</li>
            <li>Agregar observaciones técnicas</li>
            <li>Confirmar que el problema está resuelto</li>
          </ul>
        </li>
        <li><strong>Cierre automático:</strong>
          <ul>
            <li>La hoja cambia a estado "Inactivo"</li>
            <li>La solicitud se marca como "Finalizado"</li>
            <li>Se registra fecha y hora de finalización</li>
            <li>Se notifica al solicitante</li>
          </ul>
        </li>
      </ol>

      <h4 id="tipo-servicio">Tipo de Servicio</h4>
      <p>Clasificación de servicios técnicos disponibles en el sistema para organizar y asignar correctamente las solicitudes.</p>
      <p><strong>Tipos de servicio disponibles:</strong></p>
      <p><strong>1. Soporte Técnico (ID: 1):</strong></p>
      <ul>
        <li><strong>Descripción:</strong> Problemas de software, sistemas operativos, aplicaciones</li>
        <li><strong>Ejemplos:</strong> Instalación de software, configuración de sistemas, resolución de errores</li>
        <li><strong>Técnicos especializados:</strong> Personal con conocimientos en sistemas y software</li>
      </ul>
      <p><strong>2. Redes (ID: 2):</strong></p>
      <ul>
        <li><strong>Descripción:</strong> Problemas de conectividad, infraestructura de red</li>
        <li><strong>Ejemplos:</strong> Configuración de switches, patch panels, puntos de conexión</li>
        <li><strong>Técnicos especializados:</strong> Personal con conocimientos en redes y telecomunicaciones</li>
      </ul>
      <p><strong>3. Telefonía (ID: 3):</strong></p>
      <ul>
        <li><strong>Descripción:</strong> Sistemas telefónicos, comunicaciones</li>
        <li><strong>Ejemplos:</strong> Instalación de líneas, configuración de centrales telefónicas</li>
        <li><strong>Técnicos especializados:</strong> Personal especializado en sistemas de telefonía</li>
      </ul>
      <p><strong>4. Electrónica (ID: 4):</strong></p>
      <ul>
        <li><strong>Descripción:</strong> Reparación de equipos, componentes electrónicos</li>
        <li><strong>Ejemplos:</strong> Reparación de computadoras, mantenimiento de equipos</li>
        <li><strong>Técnicos especializados:</strong> Personal con conocimientos en electrónica y hardware</li>
      </ul>
      <p><strong>Asignación automática:</strong></p>
      <ul>
        <li><strong>Filtrado por especialización:</strong> Solo técnicos del área correspondiente pueden tomar hojas</li>
        <li><strong>Balanceo de carga:</strong> Prioriza técnicos con menos servicios activos</li>
        <li><strong>Encargados de área:</strong> Supervisores pueden ver todos los servicios de su área</li>
      </ul>
      <!-- <hr>
      <h5>Preguntas Frecuentes - Solicitudes</h5>
      <p><strong>¿Qué hago luego de hacer la solicitud?</strong></p>
      <p>Después de crear tu solicitud, puedes monitorear su progreso en "Mis Solicitudes". El sistema te mostrará cuando un técnico tome tu caso y cuando esté finalizado. No necesitas hacer nada más, solo esperar.</p> -->
    </div>
  </div>
  `;
  }

  if (tema === "seguridad") {
  titulo = "Seguridad del Sistema";
  contenido = `
  <div class="row">
    <div class="col-md-4">
      <nav id="navbar-seguridad" class="navbar navbar-light bg-light px-3 mb-3">
        <span class="navbar-brand mb-2 h5">Seguridad del Sistema</span>
        <nav class="nav nav-pills flex-column">
          <a class="nav-link" href="#roles-permisos">Roles y Permisos</a>
          <a class="nav-link" href="#bitacora">Bitácora</a>
          <a class="nav-link" href="#backup">Backup</a>
        </nav>
      </nav>
    </div>
    <div class="col-md-8" style="max-height:60vh; overflow-y:auto;" data-bs-spy="scroll" data-bs-target="#navbar-seguridad" data-bs-offset="0" tabindex="0">

      <h4 id="roles-permisos">Roles y Permisos</h4>
      <p>El sistema SIGSO implementa un control de acceso basado en roles (RBAC) que permite gestionar de manera granular qué funciones puede realizar cada usuario.</p>
      <p><strong>Estructura del sistema de permisos:</strong></p>
      <ul>
        <li><strong>Usuario:</strong> Cuenta individual con credenciales de acceso</li>
        <li><strong>Rol:</strong> Conjunto de permisos agrupados por función organizacional</li>
        <li><strong>Permiso:</strong> Acción específica sobre un módulo del sistema</li>
      </ul>
      <p><strong>Gestión de Roles:</strong></p>
      <p><strong>1. Crear nuevo rol:</strong></p>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Navegar a "Seguridad" → "Roles y Permisos"</li>
            <li>Verificar permisos de "registrar" en módulo de roles</li>
            <li>Hacer clic en "Registrar Rol"</li>
          </ul>
        </li>
        <li><strong>Configuración básica:</strong>
          <ul>
            <li><strong>Nombre del rol:</strong> 4-45 caracteres (letras, números, acentos, espacios, guiones y puntos)</li>
            <li><strong>Descripción:</strong> Función organizacional que cumplirá el rol</li>
          </ul>
        </li>
        <li><strong>Asignación de permisos por categorías:</strong>
          <ul>
            <li><strong>Seguridad:</strong> Usuario, Rol, Bitácora, Mantenimiento</li>
            <li><strong>Personal:</strong> Empleado, Técnico</li>
            <li><strong>Servicios:</strong> Solicitud, Hoja de Servicio</li>
            <li><strong>Organización:</strong> Ente, Dependencia, Unidad, Cargo, Piso, Oficina</li>
            <li><strong>Inventario:</strong> Bien, Tipo Bien, Equipo, Marca, Material</li>
            <li><strong>Infraestructura:</strong> Patch Panel, Switch, Punto Conexión</li>
          </ul>
        </li>
      </ol>
      <p><strong>2. Tipos de permisos disponibles:</strong></p>
      <ul>
        <li><strong>Ver:</strong> Consultar información del módulo</li>
        <li><strong>Registrar:</strong> Crear nuevos registros</li>
        <li><strong>Modificar:</strong> Editar registros existentes</li>
        <li><strong>Eliminar:</strong> Eliminar registros (soft delete)</li>
        <li><strong>Restaurar:</strong> Recuperar registros eliminados</li>
        <li><strong>Exportar:</strong> Generar reportes y respaldos</li>
        <li><strong>Importar:</strong> Cargar datos desde archivos externos</li>
      </ul>
      <p><strong>3. Modificar roles existentes:</strong></p>
      <ol>
        <li><strong>Selección del rol:</strong>
          <ul>
            <li>Localizar el rol en la tabla de roles</li>
            <li>Hacer clic en el botón "Modificar"</li>
            <li>Se carga el modal con la configuración actual</li>
          </ul>
        </li>
        <li><strong>Actualización de permisos:</strong>
          <ul>
            <li>Modificar el nombre del rol si es necesario</li>
            <li>Activar/desactivar permisos según los nuevos requerimientos</li>
            <li>Los cambios se aplican inmediatamente a todos los usuarios con ese rol</li>
          </ul>
        </li>
        <li><strong>Validación y guardado:</strong>
          <ul>
            <li>El sistema valida que el nombre del rol sea único</li>
            <li>Se actualiza la configuración de permisos en la base de datos</li>
            <li>Se registra la acción en la bitácora del sistema</li>
          </ul>
        </li>
      </ol>
      <p><strong>4. Roles predefinidos del sistema:</strong></p>
      <ul>
        <li><strong>SUPERUSUARIO:</strong> Acceso completo a todas las funciones</li>
        <li><strong>ADMINISTRADOR:</strong> Gestión completa excepto configuraciones críticas</li>
        <li><strong>TÉCNICO:</strong> Gestión de servicios y hojas de trabajo</li>
        <li><strong>SECRETARIA:</strong> Gestión administrativa y de personal</li>
        <li><strong>SOLICITANTE:</strong> Creación y seguimiento de solicitudes</li>
        <li><strong>VISITANTE:</strong> Solo consulta de información básica</li>
      </ul>
      <p><strong>Funcionamiento del control de acceso:</strong></p>
      <ul>
        <li><strong>Verificación automática:</strong> Cada página verifica permisos antes de mostrar contenido</li>
        <li><strong>Menú dinámico:</strong> Solo se muestran opciones para las que el usuario tiene permisos</li>
        <li><strong>Protección de acciones:</strong> Todas las operaciones CRUD validan permisos</li>
        <li><strong>Registro de intentos:</strong> Los accesos denegados se registran en bitácora</li>
      </ul>

      <h4 id="bitacora">Bitácora</h4>
      <p>Sistema de auditoría que registra todas las acciones realizadas por los usuarios en el sistema, proporcionando trazabilidad completa de las operaciones.</p>
      <p><strong>Información registrada automáticamente:</strong></p>
      <ul>
        <li><strong>Usuario:</strong> Nombre de usuario que realizó la acción</li>
        <li><strong>Módulo:</strong> Sección del sistema donde se realizó la acción</li>
        <li><strong>Acción:</strong> Descripción detallada de lo que se hizo</li>
        <li><strong>Fecha:</strong> Día en que se realizó la acción</li>
        <li><strong>Hora:</strong> Momento exacto de la acción</li>
      </ul>
      <p><strong>Acceso a la bitácora:</strong></p>
      <ol>
        <li><strong>Navegación:</strong>
          <ul>
            <li>Ir a "Seguridad" → "Bitácora"</li>
            <li>Requiere permisos de "ver" en módulo de bitácora</li>
            <li>Se carga automáticamente la tabla de registros</li>
          </ul>
        </li>
        <li><strong>Visualización de registros:</strong>
          <ul>
            <li>Tabla paginada con todos los eventos del sistema</li>
            <li>Ordenamiento por fecha y hora (más recientes primero)</li>
            <li>Búsqueda por usuario, módulo o texto de acción</li>
          </ul>
        </li>
      </ol>
      <p><strong>Tipos de eventos registrados:</strong></p>
      <p><strong>1. Eventos de autenticación:</strong></p>
      <ul>
        <li>Inicios de sesión exitosos y fallidos</li>
        <li>Cambios de contraseña</li>
        <li>Cierres de sesión</li>
        <li>Intentos de acceso con contraseñas por defecto</li>
      </ul>
      <p><strong>2. Eventos de gestión de datos:</strong></p>
      <ul>
        <li>Creación de nuevos registros</li>
        <li>Modificaciones de datos existentes</li>
        <li>Eliminaciones (soft delete)</li>
        <li>Restauraciones de registros eliminados</li>
      </ul>
      <p><strong>3. Eventos de seguridad:</strong></p>
      <ul>
        <li>Intentos de acceso denegados por permisos</li>
        <li>Cambios en roles y permisos</li>
        <li>Accesos a módulos administrativos</li>
        <li>Generación de respaldos</li>
      </ul>
      <p><strong>4. Eventos operacionales:</strong></p>
      <ul>
        <li>Creación y gestión de solicitudes</li>
        <li>Asignación de técnicos</li>
        <li>Finalización de servicios</li>
        <li>Movimientos de inventario</li>
      </ul>
      <p><strong>Función de registro automático:</strong></p>
      <p>El sistema utiliza la función <code>Bitacora()</code> que se ejecuta automáticamente en todas las operaciones importantes:</p>
      <ul>
        <li><strong>Parámetros:</strong> Mensaje descriptivo y nombre del módulo</li>
        <li><strong>Información automática:</strong> Usuario actual, fecha y hora del sistema</li>
        <li><strong>Almacenamiento:</strong> Registro inmediato en la base de datos</li>
      </ul>

      <h4 id="backup">Backup</h4>
      <p>Sistema de respaldos que permite generar copias de seguridad de las bases de datos del sistema para proteger la información crítica.</p>
      <p><strong>Tipos de respaldo disponibles:</strong></p>
      <ul>
        <li><strong>Base de datos del sistema:</strong> Estructura y datos operacionales</li>
        <li><strong>Base de datos de usuarios:</strong> Información de autenticación y perfiles</li>
      </ul>
      <p><strong>Proceso de generación de respaldos:</strong></p>
      <p><strong>1. Acceso al módulo:</strong></p>
      <ol>
        <li><strong>Navegación:</strong>
          <ul>
            <li>Ir a "Seguridad" → "Backups"</li>
            <li>Requiere permisos de "ver" en módulo de mantenimiento</li>
            <li>Se muestra la interfaz de gestión de respaldos</li>
          </ul>
        </li>
      </ol>
      <p><strong>2. Generar nuevo respaldo:</strong></p>
      <ol>
        <li><strong>Selección de base de datos:</strong>
          <ul>
            <li>Elegir entre "Sistema" o "Usuario"</li>
            <li>Cada base contiene diferentes tipos de información</li>
          </ul>
        </li>
        <li><strong>Proceso de generación:</strong>
          <ul>
            <li>Hacer clic en "Generar Backup"</li>
            <li>El sistema crea un archivo SQL con timestamp</li>
            <li>Se incluyen estructura de tablas y datos</li>
            <li>Se genera automáticamente el nombre del archivo</li>
          </ul>
        </li>
        <li><strong>Descarga automática:</strong>
          <ul>
            <li>El archivo se descarga automáticamente al navegador</li>
            <li>Formato: backup_[base]_[fecha]_[hora].sql</li>
            <li>Se registra la acción en la bitácora</li>
          </ul>
        </li>
      </ol>
      <p><strong>3. Restaurar desde respaldo:</strong></p>
      <ol>
        <li><strong>Preparación:</strong>
          <ul>
            <li>Tener el archivo de respaldo disponible</li>
            <li>Verificar que sea compatible con la versión actual</li>
            <li>Realizar respaldo actual antes de restaurar</li>
          </ul>
        </li>
        <li><strong>Proceso de restauración:</strong>
          <ul>
            <li>Seleccionar archivo de respaldo</li>
            <li>Confirmar la operación (irreversible)</li>
            <li>El sistema ejecuta las instrucciones SQL</li>
            <li>Se restauran datos y estructura</li>
          </ul>
        </li>
        <li><strong>Verificación:</strong>
          <ul>
            <li>Comprobar que los datos se restauraron correctamente</li>
            <li>Verificar funcionalidad del sistema</li>
            <li>Revisar logs de errores si los hay</li>
          </ul>
        </li>
      </ol>
      <p><strong>Consideraciones importantes:</strong></p>
      <ul>
        <li><strong>Frecuencia recomendada:</strong> Respaldos diarios o semanales según criticidad</li>
        <li><strong>Almacenamiento:</strong> Guardar respaldos en ubicaciones seguras y externas</li>
        <li><strong>Pruebas:</strong> Verificar restauración periódicamente</li>
      </ul>
    </div>
  </div>
  `;
  }

  if (tema === "inventario") {
  titulo = "Gestión de Inventario";
  contenido = `
  <div class="row">
    <div class="col-md-4">
      <nav id="navbar-inventario" class="navbar navbar-light bg-light px-3 mb-3">
        <span class="navbar-brand mb-2 h5">Gestión de Inventario</span>
        <nav class="nav nav-pills flex-column">
          <a class="nav-link" href="#bienes">Bienes</a>
          <a class="nav-link" href="#tipo-bienes">Tipo Bienes</a>
          <a class="nav-link" href="#equipos">Equipos</a>
          <a class="nav-link" href="#patch-panel">Patch Panel</a>
          <a class="nav-link" href="#switch">Switch</a>
          <a class="nav-link" href="#marca">Marca</a>
          <a class="nav-link" href="#materiales">Materiales</a>
        </nav>
      </nav>
    </div>
    <div class="col-md-8" style="max-height:60vh; overflow-y:auto;" data-bs-spy="scroll" data-bs-target="#navbar-inventario" data-bs-offset="0" tabindex="0">

      <h4 id="bienes">Bienes</h4>
      <p>El módulo de bienes es la base del sistema de inventario, permitiendo registrar y gestionar todos los activos físicos de la organización.</p>
      <p><strong>Características principales:</strong></p>
      <ul>
        <li><strong>Registro único:</strong> Cada bien tiene un código único identificador</li>
        <li><strong>Clasificación:</strong> Se categoriza por tipo de bien y marca</li>
        <li><strong>Asignación:</strong> Se puede asignar a empleados específicos</li>
        <li><strong>Ubicación:</strong> Se registra la oficina donde se encuentra</li>
        <li><strong>Estado:</strong> Control del estado físico del bien</li>
      </ul>
      <p><strong>Proceso de registro de bienes:</strong></p>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Navegar a "Gestión de Equipos" → "Bienes"</li>
            <li>Verificar permisos de "ver" en módulo de bienes</li>
            <li>Se carga la tabla con todos los bienes registrados</li>
          </ul>
        </li>
        <li><strong>Crear nuevo bien:</strong>
          <ul>
            <li>Hacer clic en "Registrar Bien"</li>
            <li>Se abre el modal de registro</li>
            <li>Opción de registrar equipo asociado simultáneamente</li>
          </ul>
        </li>
        <li><strong>Información básica requerida:</strong>
          <ul>
            <li><strong>Código del bien:</strong> 3-20 caracteres alfanuméricos con guiones</li>
            <li><strong>Tipo de bien:</strong> Seleccionar de catálogo predefinido</li>
            <li><strong>Marca:</strong> Seleccionar marca del fabricante</li>
            <li><strong>Descripción:</strong> 3-100 caracteres descriptivos</li>
            <li><strong>Estado:</strong> Nuevo, Usado, En reparación, Dañado</li>
          </ul>
        </li>
        <li><strong>Asignación y ubicación:</strong>
          <ul>
            <li><strong>Empleado responsable:</strong> Opcional, formato de cédula V-12345678</li>
            <li><strong>Oficina:</strong> Ubicación física del bien</li>
          </ul>
        </li>
        <li><strong>Registro simultáneo de equipo:</strong>
          <ul>
            <li>Activar checkbox "Registrar equipo asociado"</li>
            <li>Completar información técnica adicional</li>
            <li>Serial del equipo y tipo específico</li>
            <li>Asignación a unidad organizacional</li>
          </ul>
        </li>
      </ol>
      <p><strong>Funciones administrativas:</strong></p>
      <ul>
        <li><strong>Modificar bienes:</strong> Actualizar información, cambiar asignaciones</li>
        <li><strong>Eliminar bienes:</strong> Eliminación lógica manteniendo historial</li>
        <li><strong>Restaurar bienes:</strong> Recuperar bienes eliminados</li>
        <li><strong>Consultar eliminados:</strong> Vista especial de bienes inactivos</li>
      </ul>

      <h4 id="tipo-bienes">Tipo Bienes</h4>
      <p>Catálogo de clasificación que permite categorizar los bienes según su naturaleza y función organizacional.</p>
      <p><strong>Tipos predefinidos en el sistema:</strong></p>
      <ul>
        <li><strong>Electrónico:</strong> Equipos tecnológicos, computadoras, dispositivos</li>
        <li><strong>Mueble:</strong> Mobiliario de oficina, escritorios, sillas</li>
        <li><strong>Otros tipos:</strong> Según necesidades organizacionales</li>
      </ul>
      <p><strong>Gestión de tipos de bien:</strong></p>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Requiere permisos específicos de administración</li>
            <li>Navegación desde módulos administrativos</li>
          </ul>
        </li>
        <li><strong>Crear nuevo tipo:</strong>
          <ul>
            <li><strong>Nombre:</strong> 4-45 caracteres descriptivos</li>
            <li><strong>Validación:</strong> Nombres únicos en el sistema</li>
            <li><strong>Estado:</strong> Activo por defecto</li>
          </ul>
        </li>
        <li><strong>Operaciones disponibles:</strong>
          <ul>
            <li>Modificar nombres existentes</li>
            <li>Eliminar tipos no utilizados</li>
            <li>Restaurar tipos eliminados</li>
          </ul>
        </li>
      </ol>

      <h4 id="equipos">Equipos</h4>
      <p>Especialización de bienes que requieren información técnica adicional para su gestión y mantenimiento.</p>
      <p><strong>Relación con bienes:</strong></p>
      <ul>
        <li><strong>Herencia:</strong> Todo equipo debe estar asociado a un bien</li>
        <li><strong>Información adicional:</strong> Datos técnicos específicos</li>
        <li><strong>Gestión especializada:</strong> Mantenimiento y servicios técnicos</li>
      </ul>
      <p><strong>Proceso de registro de equipos:</strong></p>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Ir a "Gestión de Equipos" → "Equipos"</li>
            <li>Verificar permisos de "registrar" en módulo de equipos</li>
          </ul>
        </li>
        <li><strong>Información técnica requerida:</strong>
          <ul>
            <li><strong>Código de bien:</strong> Debe existir previamente en el sistema</li>
            <li><strong>Tipo de equipo:</strong> Clasificación técnica (3-45 caracteres)</li>
            <li><strong>Serial:</strong> Número de serie único del fabricante</li>
            <li><strong>Unidad organizacional:</strong> Área responsable del equipo</li>
          </ul>
        </li>
        <li><strong>Validaciones del sistema:</strong>
          <ul>
            <li>Código de bien debe existir y estar activo</li>
            <li>Serial único en el sistema</li>
            <li>Unidad organizacional válida</li>
          </ul>
        </li>
      </ol>
      <p><strong>Funciones especiales de equipos:</strong></p>
      <ul>
        <li><strong>Historial de servicios:</strong> Registro completo de mantenimientos</li>
        <li><strong>Asignación a solicitudes:</strong> Vinculación con servicios técnicos</li>
        <li><strong>Consulta por empleado:</strong> Equipos asignados a usuarios específicos</li>
        <li><strong>Gestión de garantías:</strong> Control de períodos de garantía</li>
      </ul>

      <h4 id="patch-panel">Patch Panel</h4>
      <p>Equipos especializados de infraestructura de red que requieren gestión específica de puertos y conexiones.</p>
      <p><strong>Características específicas:</strong></p>
      <ul>
        <li><strong>Tipos disponibles:</strong> Red y Telefonía</li>
        <li><strong>Capacidades:</strong> 8, 12, 16, 24, 32, 48, 96 puertos</li>
        <li><strong>Ubicación física:</strong> Asignación a pisos específicos</li>
        <li><strong>Gestión de puertos:</strong> Control de ocupación y disponibilidad</li>
      </ul>
      <p><strong>Proceso de registro:</strong></p>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Navegar a "Infraestructura" → "Patch Panels"</li>
            <li>Verificar permisos específicos del módulo</li>
          </ul>
        </li>
        <li><strong>Selección de bien base:</strong>
          <ul>
            <li>Elegir de bienes disponibles no asignados</li>
            <li>El sistema filtra bienes no utilizados por otros equipos</li>
          </ul>
        </li>
        <li><strong>Configuración técnica:</strong>
          <ul>
            <li><strong>Tipo:</strong> Red o Telefonía</li>
            <li><strong>Cantidad de puertos:</strong> Según especificaciones</li>
            <li><strong>Serial:</strong> Identificador único del fabricante</li>
            <li><strong>Piso:</strong> Ubicación física en el edificio</li>
          </ul>
        </li>
      </ol>
      <p><strong>Gestión de conectividad:</strong></p>
      <ul>
        <li><strong>Puntos de conexión:</strong> Vinculación con equipos finales</li>
        <li><strong>Interconexiones:</strong> Conexiones con switches</li>
        <li><strong>Reportes de ocupación:</strong> Estadísticas de uso de puertos</li>
        <li><strong>Documentación de cableado:</strong> Registro de conexiones físicas</li>
      </ul>

      <h4 id="switch">Switch</h4>
      <p>Equipos de red centrales que gestionan la conectividad y distribución de datos en la infraestructura de red.</p>
      <p><strong>Características técnicas:</strong></p>
      <ul>
        <li><strong>Capacidad de puertos:</strong> Configuración según modelo</li>
        <li><strong>Ubicación estratégica:</strong> Distribución por pisos</li>
        <li><strong>Gestión de interconexiones:</strong> Conexiones con patch panels</li>
        <li><strong>Monitoreo de uso:</strong> Control de puertos ocupados</li>
      </ul>
      <p><strong>Proceso de configuración:</strong></p>
      <ol>
        <li><strong>Registro básico:</strong>
          <ul>
            <li>Asociación con bien existente</li>
            <li>Información del fabricante y modelo</li>
            <li>Serial único del equipo</li>
          </ul>
        </li>
        <li><strong>Configuración de red:</strong>
          <ul>
            <li><strong>Cantidad de puertos:</strong> Según especificaciones técnicas</li>
            <li><strong>Ubicación física:</strong> Piso del edificio</li>
            <li><strong>Configuración lógica:</strong> Parámetros de red</li>
          </ul>
        </li>
        <li><strong>Gestión de conectividad:</strong>
          <ul>
            <li>Configuración de interconexiones</li>
            <li>Asignación de puertos específicos</li>
            <li>Documentación de topología de red</li>
          </ul>
        </li>
      </ol>

      <h4 id="marca">Marca</h4>
      <p>Catálogo de fabricantes y marcas comerciales para la clasificación y organización del inventario.</p>
      <p><strong>Marcas predefinidas en el sistema:</strong></p>
      <ul>
        <li><strong>Tecnología:</strong> Lenovo, HP, Samsung, Apple</li>
        <li><strong>Telecomunicaciones:</strong> VIT, OPPO</li>
        <li><strong>Otras marcas:</strong> Según necesidades organizacionales</li>
      </ul>
      <p><strong>Gestión de marcas:</strong></p>
      <ol>
        <li><strong>Administración del catálogo:</strong>
          <ul>
            <li>Agregar nuevas marcas según necesidades</li>
            <li>Modificar nombres existentes</li>
            <li>Desactivar marcas no utilizadas</li>
          </ul>
        </li>
        <li><strong>Validaciones del sistema:</strong>
          <ul>
            <li>Nombres únicos de marcas</li>
            <li>Control de estado activo/inactivo</li>
            <li>Verificación antes de eliminación</li>
          </ul>
        </li>
        <li><strong>Integración con bienes:</strong>
          <ul>
            <li>Las marcas se asocian a bienes y equipos para su clasificación</li>
          </ul>
        </li>
      </ol>

      <h4 id="materiales">Materiales</h4>
      <p>Gestión de insumos y materiales utilizados en servicios técnicos y mantenimiento.</p>
      <ul>
        <li><strong>Registro de materiales:</strong> Catálogo de insumos disponibles</li>
        <li><strong>Asignación a servicios:</strong> Control de uso por hoja de servicio</li>
        <li><strong>Inventario en tiempo real:</strong> Actualización automática al consumir materiales</li>
        <li><strong>Reportes de consumo:</strong> Estadísticas de uso por período y área</li>
      </ul>
    </div>
  </div>
  `;
  }

  if (tema === "personal") {
  titulo = "Personal e Infraestructura";
  contenido = `
  <div class="row">
    <div class="col-md-4">
      <nav id="navbar-personal" class="navbar navbar-light bg-light px-3 mb-3">
        <span class="navbar-brand mb-2 h5">Personal e Infraestructura</span>
        <nav class="nav nav-pills flex-column">
          <a class="nav-link" href="#empleado">Empleado</a>
          <a class="nav-link" href="#ente">Ente</a>
          <a class="nav-link" href="#dependencia">Dependencia</a>
          <a class="nav-link" href="#unidad">Unidad</a>
          <a class="nav-link" href="#cargo">Cargo</a>
          <a class="nav-link" href="#tecnico">Técnico</a>
          <a class="nav-link" href="#usuario-personal">Usuario</a>
          <a class="nav-link" href="#piso">Piso</a>
          <a class="nav-link" href="#edificio">Edificio</a>
        </nav>
      </nav>
    </div>
    <div class="col-md-8" style="max-height:60vh; overflow-y:auto;" data-bs-spy="scroll" data-bs-target="#navbar-personal" data-bs-offset="0" tabindex="0">

      <h4 id="empleado">Empleado</h4>
      <p>El módulo de empleados es el núcleo del sistema de gestión de personal, permitiendo registrar y administrar toda la información del recurso humano de la organización.</p>
      <ul>
        <li><strong>Identificación única:</strong> Cada empleado se identifica por su cédula</li>
        <li><strong>Estructura organizacional:</strong> Asignación a unidades y dependencias</li>
        <li><strong>Roles funcionales:</strong> Asignación de cargos específicos</li>
        <li><strong>Integración con usuarios:</strong> Vinculación opcional con cuentas del sistema</li>
        <li><strong>Especialización técnica:</strong> Asignación de áreas de servicio para técnicos</li>
      </ul>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Navegar a "Personal" → "Empleados"</li>
            <li>Verificar permisos de "ver" en módulo de empleados</li>
            <li>Se carga la tabla con información completa de empleados</li>
          </ul>
        </li>
        <li><strong>Crear nuevo empleado:</strong>
          <ul>
            <li>Hacer clic en "Registrar Empleado"</li>
            <li>Se abre el modal de registro con validaciones</li>
          </ul>
        </li>
        <li><strong>Información personal requerida:</strong>
          <ul>
            <li><strong>Cédula:</strong> Formato V-12345678 o E-12345678 (único en el sistema)</li>
            <li><strong>Nombre:</strong> 4-45 caracteres (incluye acentos)</li>
            <li><strong>Apellido:</strong> 4-45 caracteres (incluye acentos)</li>
            <li><strong>Teléfono:</strong> Formato 0000-0000000</li>
            <li><strong>Correo:</strong> Formato válido terminado en .com</li>
          </ul>
        </li>
        <li><strong>Asignación organizacional:</strong>
          <ul>
            <li><strong>Dependencia:</strong> Seleccionar dependencia organizacional</li>
            <li><strong>Unidad:</strong> Se cargan unidades según dependencia seleccionada</li>
            <li><strong>Cargo:</strong> Asignar posición funcional del empleado</li>
          </ul>
        </li>
        <li><strong>Creación opcional de usuario:</strong>
          <ul>
            <li>Activar checkbox "Crear usuario del sistema"</li>
            <li>Se crea automáticamente cuenta con cédula como usuario y contraseña</li>
            <li>El empleado deberá cambiar la contraseña en el primer acceso</li>
          </ul>
        </li>
      </ol>
      <ul>
        <li><strong>Consulta por dependencia:</strong> Filtrar empleados por área organizacional</li>
        <li><strong>Listado de técnicos:</strong> Vista especializada para personal técnico</li>
        <li><strong>Asignación de bienes:</strong> Vincular equipos bajo responsabilidad</li>
        <li><strong>Historial de servicios:</strong> Seguimiento de solicitudes realizadas</li>
      </ul>

      <h4 id="ente">Ente</h4>
      <p>Los entes representan las organizaciones de nivel superior en la jerarquía administrativa del sistema.</p>
      <ul>
        <li><strong>Interno:</strong> Organizaciones propias de la institución</li>
        <li><strong>Externo:</strong> Organizaciones externas con las que se colabora</li>
      </ul>
      <ul>
        <li><strong>Nombre:</strong> 4-90 caracteres descriptivos</li>
        <li><strong>Responsable:</strong> Nombre del responsable principal (4-65 caracteres)</li>
        <li><strong>Teléfono:</strong> Formato ####-#######</li>
        <li><strong>Dirección:</strong> Ubicación física (10-100 caracteres)</li>
        <li><strong>Tipo:</strong> Interno o Externo</li>
      </ul>

      <h4 id="dependencia">Dependencia</h4>
      <p>Las dependencias son divisiones organizacionales que pertenecen a un ente específico, representando departamentos o áreas funcionales.</p>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Navegar a "Configuración" → "Dependencias"</li>
            <li>Requiere permisos específicos de administración</li>
          </ul>
        </li>
        <li><strong>Crear nueva dependencia:</strong>
          <ul>
            <li><strong>Seleccionar ente:</strong> Elegir ente al que pertenecerá</li>
            <li><strong>Nombre:</strong> 4-45 caracteres descriptivos</li>
            <li><strong>Validación:</strong> Nombres únicos dentro del mismo ente</li>
          </ul>
        </li>
        <li><strong>Relaciones jerárquicas:</strong>
          <ul>
            <li>Una dependencia pertenece a un solo ente</li>
            <li>Puede contener múltiples unidades organizacionales</li>
            <li>Se muestra como "Ente - Dependencia" en las interfaces</li>
          </ul>
        </li>
      </ol>

      <h4 id="unidad">Unidad</h4>
      <p>Las unidades son el nivel más específico de la estructura organizacional, representando equipos de trabajo o áreas especializadas.</p>
      <ul>
        <li><strong>Pertenencia:</strong> Cada unidad pertenece a una dependencia específica</li>
        <li><strong>Empleados asignados:</strong> Los empleados se asignan directamente a unidades</li>
        <li><strong>Equipos asociados:</strong> Los equipos se asignan a unidades para control</li>
      </ul>
      <ol>
        <li><strong>Crear nueva unidad:</strong>
          <ul>
            <li>Seleccionar dependencia padre</li>
            <li>Ingresar nombre descriptivo (4-45 caracteres)</li>
            <li>El sistema valida unicidad dentro de la dependencia</li>
          </ul>
        </li>
        <li><strong>Visualización jerárquica:</strong>
          <ul>
            <li>Se muestra como "Ente - Dependencia" en los selectores</li>
            <li>Facilita la navegación y comprensión organizacional</li>
          </ul>
        </li>
      </ol>

      <h4 id="cargo">Cargo</h4>
      <p>Los cargos definen las posiciones funcionales que pueden ocupar los empleados dentro de la organización.</p>
      <ul>
        <li><strong>Técnico (ID: 1):</strong> Personal especializado en servicios técnicos</li>
        <li><strong>Director de Telefonía (ID: 2):</strong> Responsable del área de telefonía</li>
        <li><strong>Secretaria (ID: 3):</strong> Personal administrativo</li>
      </ul>
      <ul>
        <li><strong>Crear nuevos cargos:</strong> Según necesidades organizacionales</li>
        <li><strong>Modificar existentes:</strong> Actualizar nombres y descripciones</li>
        <li><strong>Control de estado:</strong> Activar/desactivar cargos</li>
        <li><strong>Validación de uso:</strong> Verificar antes de eliminar si hay empleados asignados</li>
      </ul>

      <h4 id="tecnico">Técnico</h4>
      <p>Los técnicos son empleados especializados con cargo técnico (ID: 1) y asignación a áreas específicas de servicio.</p>
      <ul>
        <li><strong>Especialización:</strong> Asignados a áreas específicas (Soporte, Redes, Telefonía, Electrónica)</li>
        <li><strong>Gestión de servicios:</strong> Pueden tomar y ejecutar hojas de servicio</li>
        <li><strong>Balanceo de carga:</strong> El sistema distribuye trabajo según disponibilidad</li>
      </ul>
      <ol>
        <li><strong>Acceso especializado:</strong>
          <ul>
            <li>Navegar a "Personal" → "Técnicos"</li>
            <li>Vista filtrada solo para personal técnico</li>
          </ul>
        </li>
        <li><strong>Información técnica adicional:</strong>
          <ul>
            <li><strong>Área de servicio:</strong> Seleccionar especialización técnica</li>
            <li><strong>Cargo automático:</strong> Se asigna automáticamente cargo "Técnico"</li>
            <li><strong>Validación especializada:</strong> Solo cédulas venezolanas (V-)</li>
          </ul>
        </li>
        <li><strong>Funciones especiales:</strong>
          <ul>
            <li>Aparecen en listados de asignación de servicios</li>
            <li>Pueden tomar hojas de servicio de su área</li>
            <li>Sistema de balanceo automático de carga de trabajo</li>
          </ul>
        </li>
      </ol>

      <h4 id="usuario-personal">Usuario</h4>
      <p>Integración entre el personal y las cuentas de acceso al sistema, permitiendo que los empleados tengan acceso digital.</p>
      <ul>
        <li><strong>Creación automática:</strong> Al registrar empleado con checkbox activado</li>
        <li><strong>Credenciales iniciales:</strong> Usuario y contraseña igual a la cédula</li>
        <li><strong>Cambio obligatorio:</strong> Debe cambiar contraseña en primer acceso</li>
        <li><strong>Sincronización:</strong> Datos personales se mantienen sincronizados</li>
      </ul>
      <ol>
        <li><strong>Durante registro de empleado:</strong>
          <ul>
            <li>Activar "Crear usuario del sistema"</li>
            <li>Se crea cuenta automáticamente</li>
            <li>Rol por defecto según configuración</li>
          </ul>
        </li>
        <li><strong>Vinculación posterior:</strong>
          <ul>
            <li>Crear usuario manualmente con misma cédula</li>
            <li>El sistema reconoce la vinculación automáticamente</li>
          </ul>
        </li>
      </ol>

      <h4 id="piso">Piso</h4>
      <p>Los pisos representan los niveles físicos de los edificios donde se ubican las oficinas y equipos.</p>
      <ul>
        <li><strong>Sótano:</strong> Niveles subterráneos (numeración 1-10)</li>
        <li><strong>Planta Baja:</strong> Nivel principal (numeración 0)</li>
        <li><strong>Piso:</strong> Niveles superiores (numeración 1-10)</li>
      </ul>

      <h4 id="edificio">Edificio</h4>
      <p>Los edificios agrupan los pisos y oficinas, permitiendo la gestión de la infraestructura física de la organización.</p>
      <ul>
        <li><strong>Identificación única:</strong> Cada edificio tiene un nombre y código</li>
        <li><strong>Relación jerárquica:</strong> Un edificio contiene varios pisos</li>
        <li><strong>Asignación de oficinas:</strong> Las oficinas se ubican en pisos específicos de un edificio</li>
      </ul>

    </div>
  </div>
  `;
  }

  if (tema === "redes") {
  titulo = "Gestión de Redes";
  contenido = `
  <div class="row">
    <div class="col-md-4">
      <nav id="navbar-redes" class="navbar navbar-light bg-light px-3 mb-3">
        <span class="navbar-brand mb-2 h5">Gestión de Redes</span>
        <nav class="nav nav-pills flex-column">
          <a class="nav-link" href="#patch-panel-red">Patch Panel</a>
          <a class="nav-link" href="#switch-red">Switch</a>
          <a class="nav-link" href="#asignacion-puertos">Asignación de Puertos</a>
          <a class="nav-link" href="#punto-conexion">Punto de Conexión</a>
          <a class="nav-link" href="#interconexion">Interconexión</a>
        </nav>
      </nav>
    </div>
    <div class="col-md-8" style="max-height:60vh; overflow-y:auto;" data-bs-spy="scroll" data-bs-target="#navbar-redes" data-bs-offset="0" tabindex="0">

      <h4 id="patch-panel-red">Patch Panel</h4>
      <p>Los patch panels son elementos fundamentales de la infraestructura de red que permiten organizar y gestionar las conexiones de cableado estructurado.</p>
      <ul>
        <li><strong>Tipos disponibles:</strong> Red (datos) y Telefonía (voz)</li>
        <li><strong>Capacidades estándar:</strong> 8, 12, 16, 24, 32, 48, 96 puertos</li>
        <li><strong>Ubicación física:</strong> Asignados a pisos específicos del edificio</li>
        <li><strong>Gestión de puertos:</strong> Control individual de cada puerto</li>
      </ul>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Navegar a "Infraestructura" → "Redes" → "Patch Panels"</li>
            <li>Verificar permisos de "ver" en módulo patch_panel</li>
            <li>Se carga la tabla con todos los patch panels registrados</li>
          </ul>
        </li>
        <li><strong>Registrar nuevo patch panel:</strong>
          <ul>
            <li>Hacer clic en "Registrar Patch Panel"</li>
            <li>Seleccionar bien disponible (no asignado a otros equipos)</li>
            <li>El sistema filtra automáticamente bienes disponibles</li>
          </ul>
        </li>
        <li><strong>Configuración técnica:</strong>
          <ul>
            <li><strong>Código de bien:</strong> Seleccionar de lista filtrada</li>
            <li><strong>Tipo:</strong> "Red" para datos o "Telefonía" para voz</li>
            <li><strong>Cantidad de puertos:</strong> Según especificaciones del fabricante</li>
            <li><strong>Serial:</strong> Número de serie único (3-45 caracteres alfanuméricos)</li>
          </ul>
        </li>
        <li><strong>Validaciones del sistema:</strong>
          <ul>
            <li>Verificación de bien disponible y no duplicado</li>
            <li>Validación de formato de serial</li>
            <li>Confirmación de cantidad de puertos válida</li>
            <li>Registro en bitácora de la acción</li>
          </ul>
        </li>
      </ol>
      <ul>
        <li><strong>Modificar configuración:</strong> Actualizar tipo, puertos o serial</li>
        <li><strong>Eliminar patch panel:</strong> Eliminación lógica manteniendo historial</li>
        <li><strong>Restaurar eliminados:</strong> Recuperar patch panels dados de baja</li>
        <li><strong>Consultar disponibilidad:</strong> Ver puertos libres y ocupados</li>
      </ul>

      <h4 id="switch-red">Switch</h4>
      <p>Los switches son equipos centrales de red que gestionan el tráfico de datos y proporcionan conectividad entre diferentes segmentos de red.</p>
      <ul>
        <li><strong>Capacidades de puerto:</strong> 8, 10, 16, 24, 28, 48, 52 puertos</li>
        <li><strong>Ubicación estratégica:</strong> Distribución por pisos según topología</li>
        <li><strong>Gestión centralizada:</strong> Control de configuración y monitoreo</li>
        <li><strong>Interconexiones:</strong> Conexiones con patch panels y otros switches</li>
      </ul>
      <ol>
        <li><strong>Registro básico:</strong>
          <ul>
            <li>Acceder a "Infraestructura" → "Redes" → "Switches"</li>
            <li>Seleccionar bien disponible para asociar</li>
            <li>El sistema verifica que no esté asignado a otros equipos</li>
          </ul>
        </li>
        <li><strong>Configuración de red:</strong>
          <ul>
            <li><strong>Cantidad de puertos:</strong> Según modelo del switch</li>
            <li><strong>Serial del equipo:</strong> Identificador único del fabricante</li>
            <li><strong>Ubicación física:</strong> Piso donde se instalará</li>
          </ul>
        </li>
        <li><strong>Validaciones técnicas:</strong>
          <ul>
            <li>Verificación de puertos válidos según estándares</li>
            <li>Validación de serial único en el sistema</li>
            <li>Confirmación de bien disponible</li>
          </ul>
        </li>
      </ol>
      <ul>
        <li><strong>Modificar configuración:</strong> Actualizar puertos, serial o ubicación</li>
        <li><strong>Gestión de interconexiones:</strong> Configurar enlaces con otros equipos</li>
        <li><strong>Monitoreo de puertos:</strong> Control de uso y disponibilidad</li>
        <li><strong>Reportes de conectividad:</strong> Estadísticas de uso por piso</li>
      </ul>

      <h4 id="asignacion-puertos">Asignación de Puertos</h4>
      <p>Sistema de gestión que controla la asignación y uso de puertos en patch panels y switches para optimizar la conectividad.</p>
      <ul>
        <li><strong>Disponibilidad dinámica:</strong> Cálculo automático de puertos libres</li>
        <li><strong>Prevención de conflictos:</strong> Validación antes de asignar</li>
        <li><strong>Reportes de ocupación:</strong> Estadísticas por equipo y piso</li>
        <li><strong>Optimización de uso:</strong> Sugerencias de asignación eficiente</li>
      </ul>
      <ol>
        <li><strong>Consulta de disponibilidad:</strong>
          <ul>
            <li>El sistema consulta puertos ocupados en tiempo real</li>
            <li>Calcula automáticamente puertos disponibles</li>
            <li>Muestra información actualizada al usuario</li>
          </ul>
        </li>
        <li><strong>Asignación inteligente:</strong>
          <ul>
            <li>Selección automática de puerto libre</li>
            <li>Validación de no duplicación</li>
            <li>Actualización inmediata del estado</li>
          </ul>
        </li>
        <li><strong>Control de liberación:</strong>
          <ul>
            <li>Liberación automática al eliminar conexiones</li>
            <li>Reasignación de puertos según necesidades</li>
            <li>Mantenimiento de historial de uso</li>
          </ul>
        </li>
      </ol>

      <h4 id="punto-conexion">Punto de Conexión</h4>
      <p>Los puntos de conexión establecen la relación física entre equipos finales y la infraestructura de patch panels.</p>
      <ul>
        <li><strong>Patch panel origen:</strong> Equipo de infraestructura que proporciona el puerto</li>
        <li><strong>Equipo destino:</strong> Dispositivo final que se conecta</li>
        <li><strong>Puerto específico:</strong> Número de puerto utilizado en el patch panel</li>
        <li><strong>Documentación:</strong> Registro completo de la conexión física</li>
      </ul>
      <ol>
        <li><strong>Acceso al módulo:</strong>
          <ul>
            <li>Ir a "Infraestructura" → "Redes" → "Puntos de Conexión"</li>
            <li>Verificar permisos de "registrar" en módulo punto_conexion</li>
          </ul>
        </li>
        <li><strong>Selección de componentes:</strong>
          <ul>
            <li><strong>Patch panel:</strong> Elegir de lista de patch panels activos</li>
            <li><strong>Equipo:</strong> Seleccionar equipo disponible (no conectado)</li>
            <li><strong>Puerto:</strong> Sistema muestra puertos disponibles automáticamente</li>
          </ul>
        </li>
        <li><strong>Validaciones automáticas:</strong>
          <ul>
            <li>Verificación de puerto disponible en patch panel</li>
            <li>Confirmación de equipo no conectado previamente</li>
            <li>Validación de compatibilidad técnica</li>
          </ul>
        </li>
        <li><strong>Registro de conexión:</strong>
          <ul>
            <li>Creación del punto de conexión en base de datos</li>
            <li>Actualización automática de disponibilidad de puertos</li>
            <li>Registro en bitácora de la acción</li>
          </ul>
        </li>
      </ol>
      <ul>
        <li><strong>Modificar conexiones:</strong> Cambiar puerto o equipo asignado</li>
        <li><strong>Eliminar conexiones:</strong> Liberar puerto y equipo</li>
        <li><strong>Consultar estado:</strong> Ver todas las conexiones activas</li>
        <li><strong>Reportes de cableado:</strong> Documentación completa de infraestructura</li>
      </ul>

      <h4 id="interconexion">Interconexión</h4>
      <p>Las interconexiones establecen enlaces entre switches y patch panels para crear la topología de red completa.</p>
      <ul>
        <li><strong>Switch a Patch Panel:</strong> Conexión directa para distribución</li>
        <li><strong>Switch a Switch:</strong> Enlaces troncales entre equipos</li>
        <li><strong>Patch Panel a Patch Panel:</strong> Extensiones de cableado</li>
        <li><strong>Enlaces redundantes:</strong> Conexiones de respaldo</li>
      </ul>
      <ol>
        <li><strong>Planificación de topología:</strong>
          <ul>
            <li>Diseño de la arquitectura de red</li>
            <li>Identificación de puntos de interconexión</li>
            <li>Definición de rutas principales y alternativas</li>
          </ul>
        </li>
        <li><strong>Implementación física:</strong>
          <ul>
            <li>Selección de equipos origen y destino</li>
            <li>Asignación de puertos específicos</li>
            <li>Configuración de parámetros de enlace</li>
          </ul>
        </li>
        <li><strong>Documentación completa:</strong>
          <ul>
            <li>Registro de todos los enlaces</li>
            <li>Mapeo de topología de red</li>
            <li>Mantenimiento de diagramas actualizados</li>
          </ul>
        </li>
      </ol>
      <ul>
        <li><strong>Estado de enlaces:</strong> Monitoreo de conectividad</li>
        <li><strong>Rendimiento de red:</strong> Análisis de tráfico y latencia</li>
        <li><strong>Detección de fallos:</strong> Identificación automática de problemas</li>
        <li><strong>Optimización continua:</strong> Mejoras en la topología</li>
      </ul>
      <!-- <hr>
      <h5>Preguntas Frecuentes - Redes</h5>
      <p><strong>¿Cómo sé qué puertos están disponibles en un patch panel?</strong></p>
      <p>El sistema calcula automáticamente los puertos disponibles. Al seleccionar un patch panel, se muestran solo los puertos libres. El sistema considera la cantidad total de puertos menos los ya asignados en puntos de conexión.</p>
      <p><strong>¿Puedo conectar cualquier equipo a cualquier patch panel?</strong></p>
      <p>Técnicamente sí, pero se recomienda respetar el tipo de patch panel. Los de "Red" para equipos de datos y los de "Telefonía" para sistemas de voz. El sistema no impide conexiones cruzadas pero es mejor práctica mantener la separación.</p>
      <p><strong>¿Qué pasa si elimino un punto de conexión?</strong></p>
      <p>Al eliminar un punto de conexión, el puerto del patch panel se libera automáticamente y queda disponible para nuevas conexiones.</p> -->
    </div>
  </div>
  `;
  }









  document.getElementById('modalAyudaLabel').textContent = titulo;
  document.getElementById('modalAyudaBody').innerHTML = contenido;





  
  if (tema === "usuarios") {
    var scrollSpyEl = document.querySelector('[data-bs-spy="scroll"]');
    if (scrollSpyEl) {
      bootstrap.ScrollSpy.getInstance(scrollSpyEl)?.dispose();
      new bootstrap.ScrollSpy(scrollSpyEl, {
        target: '#navbar-usuarios'
      });
    }
  }

  if (tema === "solicitudes") {
    var scrollSpyEl = document.querySelector('[data-bs-spy="scroll"]');
    if (scrollSpyEl) {
      bootstrap.ScrollSpy.getInstance(scrollSpyEl)?.dispose();
      new bootstrap.ScrollSpy(scrollSpyEl, {
        target: '#navbar-solicitudes'
      });
    }
  }

  if (tema === "seguridad") {
  var scrollSpyEl = document.querySelector('[data-bs-spy="scroll"]');
  if (scrollSpyEl) {
    bootstrap.ScrollSpy.getInstance(scrollSpyEl)?.dispose();
    new bootstrap.ScrollSpy(scrollSpyEl, {
      target: '#navbar-seguridad'
    });
  }
  }

  if (tema === "inventario") {
    var scrollSpyEl = document.querySelector('[data-bs-spy="scroll"]');
    if (scrollSpyEl) {
      bootstrap.ScrollSpy.getInstance(scrollSpyEl)?.dispose();
      new bootstrap.ScrollSpy(scrollSpyEl, {
        target: '#navbar-inventario'
      });
    }
  }

  if (tema === "personal") {
  var scrollSpyEl = document.querySelector('[data-bs-spy="scroll"]');
  if (scrollSpyEl) {
    bootstrap.ScrollSpy.getInstance(scrollSpyEl)?.dispose();
    new bootstrap.ScrollSpy(scrollSpyEl, {
      target: '#navbar-personal'
    });
  }
  }

  if (tema === "redes") {
  var scrollSpyEl = document.querySelector('[data-bs-spy="scroll"]');
  if (scrollSpyEl) {
    bootstrap.ScrollSpy.getInstance(scrollSpyEl)?.dispose();
    new bootstrap.ScrollSpy(scrollSpyEl, {
      target: '#navbar-redes'
    });
  }
  }










  var modal = new bootstrap.Modal(document.getElementById('modalAyuda'));
  modal.show();
}

function mostrarAyudaPorTema(tema) {
  
  const card = document.querySelector(`.ayuda-card[data-tema="${tema}"]`);
  if (card) {
    mostrarAyuda(card);
  }
}

function mostrarAyudaPorTemaYSub(tema, subId) {
  mostrarAyudaPorTema(tema);
  setTimeout(() => {
    const modalBody = document.getElementById('modalAyudaBody');
    const sub = modalBody.querySelector(`#${subId}`);
    if (sub) sub.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, 500);
}




