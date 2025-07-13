<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <h1 class="logo">
      <img src="assets/img/logo.jpg" style="width: 1.5em; border-radius: 2px" alt="Logo" class="img-logo">
      <span class="ms-2" id="logo-text">OFITIC</span>
    </h1>
    <button id="collapse-btn" class="collapse-btn">
      <i class="fas fa-chevron-left"></i>
    </button>
  </div>

  <div class="sidebar-content">
    <nav class="sidebar-menu">
      <ul>
        <!-- Menú común para todos los usuarios -->
        <li class="menu-item <?php echo ($page == "home") ? "active" : "" ?>" title="Dashboard">
          <a href="?page=home">
            <i class="fas fa-home"></i>
            <span class="ms-2 me-2 menu-text">Dashboard</span>
          </a>
        </li>
        <?php if (isset($permisos['solicitud']['ver_mi_solicitud']['estado']) && $permisos['solicitud']['ver_mi_solicitud']['estado'] == "1") { ?>
          <li class="menu-item <?php echo ($page == "mis_servicios") ? "active" : "" ?>" title="Mis Solicitudes">
            <a href="?page=estadistica">
              <i class="fa-solid fa-list-check"></i>
              <span class="ms-2 me-2 menu-text">Reportes estadisticos</span>
            </a>
          </li>
        <?php } ?>
        <?php if (isset($permisos['solicitud']['ver_mi_solicitud']['estado']) && $permisos['solicitud']['ver_mi_solicitud']['estado'] == "1") { ?>
          <li class="menu-item <?php echo ($page == "mis_servicios") ? "active" : "" ?>" title="Mis Solicitudes">
            <a href="?page=mis_servicios">
              <i class="fa-solid fa-list-check"></i>
              <span class="ms-2 me-2 menu-text">Mis Solicitudes</span>
            </a>
          </li>
        <?php }
        $permiso_hoja_servicio = isset($permisos['hoja_servicio']['ver']['estado']) && $permisos['hoja_servicio']['ver']['estado'] == "1";
        $permiso_solicitud = isset($permisos['solicitud']['ver_solicitud']['estado']) && $permisos['solicitud']['ver_solicitud']['estado'] == "1";
        if ($permiso_solicitud || $permiso_hoja_servicio) { ?>
          <span>Servicios</span>
        <?php } ?>
        <?php if (isset($permisos['hoja_servicio']['ver']['estado']) && $permisos['hoja_servicio']['ver']['estado'] == "1") { ?>
          <li class="menu-item <?php echo ($page == "servicios") ? "active" : "" ?>" title="Servicios">
            <a href="?page=servicios">
              <i class="fa-solid fa-clipboard-check"></i>
              <span class="ms-2 me-2 menu-text">Servicios</span>
            </a>
          </li>
        <?php } ?>
        <!-- CATEGORÍA: SERVICIOS -->
        <li class="menu-title">
        </li>
        </li>

        <?php if (isset($permisos['solicitud']['ver_solicitud']['estado']) && $permisos['solicitud']['ver_solicitud']['estado'] == "1") { ?>
          <li class="menu-item <?php echo ($page == "solicitud") ? "active" : "" ?>" title="Solicitudes">
            <a href="?page=solicitud">
              <i class="fa-solid fa-clipboard-list"></i>
              <span class="ms-2 me-2 menu-text">Solicitudes</span>
            </a>
          </li>
        <?php }
        $permiso_equipo = isset($permisos['equipo']['ver']['estado']) && $permisos['equipo']['ver']['estado'] == "1";
        $permiso_bien = isset($permisos['bien']['ver']['estado']) && $permisos['bien']['ver']['estado'] == "1";
        $permiso_material = isset($permisos['material']['ver']['estado']) && $permisos['material']['ver']['estado'] == "1";
        if ($permiso_equipo || $permiso_bien || $permiso_material) { ?>
          <!-- CATEGORÍA: EQUIPOS -->
          <li class="menu-title">
            <span>Equipos</span>
          </li>

          <li class="menu-item <?php echo in_array($page, ["bien", "equipo", "material"]) ? "active" : "" ?>" title="Gestión de Equipos">
            <a class="nav-link collapsed" data-bs-target="#equipos-submenu" data-bs-toggle="collapse" href="#">
              <i class="fas fa-laptop"></i>
              <span class="ms-2 me-2 menu-text">Gestión de Equipos</span>
              <i class="fa-solid fa-angle-right"></i>
            </a>
          </li>
          <ul id="equipos-submenu" style="margin-left: 1em;"
            class="nav-content collapse<?php echo in_array($page, ["bien", "equipo", "material"]) ? " show" : "" ?>"
            data-bs-parent="#sidebar-nav">
            <?php if (isset($permisos['bien']['ver']['estado']) && $permisos['bien']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "bien") ? "active" : "" ?>" title="Bienes">
                <a href="?page=bien">
                  <i class="fas fa-box"></i>
                  <span class="ms-2 me-2 menu-text">Bienes</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['equipo']['ver']['estado']) && $permisos['equipo']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "equipo") ? "active" : "" ?>" title="Equipos">
                <a href="?page=equipo">
                  <i class="fa-solid fa-computer"></i>
                  <span class="ms-2 me-2 menu-text">Equipos</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['material']['ver']['estado']) && $permisos['material']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "material") ? "active" : "" ?>" title="Gestión de Materiales">
                <a href="?page=material">
                  <i class="fa-solid fa-toolbox"></i>
                  <span class="ms-2 me-2 menu-text">Materiales</span>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php }
        $permiso_switch = isset($permisos['switch']['ver']['estado']) && $permisos['switch']['ver']['estado'] == "1";
        $permiso_interconexion = isset($permisos['interconexion']['ver']['estado']) && $permisos['interconexion']['ver']['estado'] == "1";
        $permiso_patch_panel = isset($permisos['patch_panel']['ver']['estado']) && $permisos['patch_panel']['ver']['estado'] == "1";
        $permiso_punto_conexion = isset($permisos['punto_conexion']['ver']['estado']) && $permisos['punto_conexion']['ver']['estado'] == "1";
        $permiso_piso = isset($permisos['piso']['ver']['estado']) && $permisos['piso']['ver']['estado'] == "1";
        $permiso_oficina = isset($permisos['oficina']['ver']['estado']) && $permisos['oficina']['ver']['estado'] == "1";
        if ($permiso_switch || $permiso_interconexion || $permiso_patch_panel || $permiso_punto_conexion || $permiso_piso || $permiso_oficina) { ?>
          <!-- CATEGORÍA: INFRAESTRUCTURA -->
          <li class="menu-title">
            <span>Infraestructura</span>
          </li>
          <?php if ($permiso_switch || $permiso_interconexion || $permiso_patch_panel || $permiso_punto_conexion) { ?>
            <li class="menu-item <?php echo in_array($page, ["Switch_", "interconexion", "patch_panel", "punto_conexion"]) ? "active" : "" ?>"
              title="Gestión de Redes">
              <a class="nav-link collapsed" data-bs-target="#redes-submenu" data-bs-toggle="collapse" href="#">
                <i class="fas fa-network-wired"></i>
                <span class="ms-2 me-2 menu-text">Redes</span>
                <i class="fa-solid fa-angle-right"></i>
              </a>
            </li>
            <ul id="redes-submenu" style="margin-left: 1em"
              class="nav-content collapse<?php echo in_array($page, ["switch_", "interconexion", "patch_panel", "punto_conexion"]) ? " show" : "" ?>"
              data-bs-parent="#sidebar-nav">
              <?php if (isset($permisos['switch']['ver']['estado']) && $permisos['switch']['ver']['estado'] == "1") { ?>
                <li class="menu-item <?php echo ($page == "switch_") ? "active" : "" ?>" title="Gestión de Switches">
                  <a href="?page=switch_">
                    <i class="fa-solid fa-server"></i>
                    <span class="ms-2 me-2 menu-text">Switches</span>
                  </a>
                </li>
              <?php } ?>
              <?php if (isset($permisos['interconexion']['ver']['estado']) && $permisos['interconexion']['ver']['estado'] == "1") { ?>
                <li class="menu-item <?php echo ($page == "interconexion") ? "active" : "" ?>" title="Gestión de Interconexiones">
                  <a href="?page=interconexion">
                    <i class="fa-solid fa-network-wired"></i>
                    <span class="ms-2 me-2 menu-text">Interconexiones</span>
                  </a>
                </li>
              <?php } ?>
              <?php if (isset($permisos['patch_panel']['ver']['estado']) && $permisos['patch_panel']['ver']['estado'] == "1") { ?>
                <li class="menu-item <?php echo ($page == "patch_panel") ? "active" : "" ?>" title="Gestión de Patch Panel">
                  <a href="?page=patch_panel">
                    <i class="fa-solid fa-plug"></i>
                    <span class="ms-2 me-2 menu-text">Patch Panels</span>
                  </a>
                </li>
              <?php } ?>
              <?php if (isset($permisos['punto_conexion']['ver']['estado']) && $permisos['punto_conexion']['ver']['estado'] == "1") { ?>
                <li class="menu-item <?php echo ($page == "punto_conexion") ? "active" : "" ?>" title="Gestión de Puntos de Conexión">
                  <a href="?page=punto_conexion">
                    <i class="fa-solid fa-ethernet"></i>
                    <span class="ms-2 me-2 menu-text">Puntos de Conexión</span>
                  </a>
                </li>
              <?php } ?>
            </ul>
          <?php } ?>
          <?php if ($permiso_piso || $permiso_oficina) { ?>
            <li class="menu-item <?php echo in_array($page, ["piso", "oficina"]) ? "active" : "" ?>"
              title="Edificios y Oficinas">
              <a class="nav-link collapsed" data-bs-target="#edificios-submenu" data-bs-toggle="collapse" href="#">
                <i class="fas fa-building"></i>
                <span class="ms-2 me-2 menu-text">Edificios</span>
                <i class="fa-solid fa-angle-right"></i>
              </a>
            </li>
            <ul id="edificios-submenu" style="margin-left: 1em"
              class="nav-content collapse<?php echo in_array($page, ["piso", "oficina"]) ? " show" : "" ?>"
              data-bs-parent="#sidebar-nav">
              <?php if (isset($permisos['piso']['ver']['estado']) && $permisos['piso']['ver']['estado'] == "1") { ?>
                <li class="menu-item <?php echo ($page == "piso") ? "active" : "" ?>" title="Gestión de Pisos">
                  <a href="?page=piso">
                    <i class="fa-solid fa-stairs"></i>
                    <span class="ms-2 me-2 menu-text">Pisos</span>
                  </a>
                </li>
              <?php } ?>
              <?php if (isset($permisos['oficina']['ver']['estado']) && $permisos['oficina']['ver']['estado'] == "1") { ?>
                <li class="menu-item <?php echo ($page == "oficina") ? "active" : "" ?>">
                  <a href="?page=oficina">
                    <i class="fa-solid fa-building-user"></i>
                    <span class="ms-2 me-2 menu-text">Oficinas</span>
                  </a>
                </li>
              <?php } ?>
            </ul>
          <?php } ?>
        <?php }
        $permiso_empleado = isset($permisos['empleado']['ver']['estado']) && $permisos['empleado']['ver']['estado'] == "1";
        $permiso_tecnico = isset($permisos['tecnico']['ver']['estado']) && $permisos['tecnico']['ver']['estado'] == "1";
        if ($permiso_empleado || $permiso_tecnico) { ?>
          <!-- CATEGORÍA: PERSONAL -->
          <li class="menu-title">
            <span>Personal</span>
          </li>

          <li class="menu-item <?php echo in_array($page, ["empleado", "tecnico"]) ? "active" : "" ?>"
            title="Gestión de Personal">
            <a class="nav-link collapsed" data-bs-target="#personal-submenu" data-bs-toggle="collapse" href="#">
              <i class="fas fa-users"></i>
              <span class="ms-2 me-2 menu-text">Personal</span>
              <i class="fa-solid fa-angle-right"></i>
            </a>
          </li>

          <ul id="personal-submenu" style="margin-left: 1em"
            class="nav-content collapse<?php echo in_array($page, ["empleado", "tecnico"]) ? " show" : "" ?>" data-bs-parent="#sidebar-nav">
            <?php if (isset($permisos['empleado']['ver']['estado']) && $permisos['empleado']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "empleado") ? "active" : "" ?>" title="Gestión de Empleados">
                <a href="?page=empleado">
                  <i class="fa-solid fa-user-tie"></i>
                  <span class="ms-2 me-2 menu-text">Empleados</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['tecnico']['ver']['estado']) && $permisos['tecnico']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "tecnico") ? "active" : "" ?>" title="Gestión de Técnicos">
                <a href="?page=tecnico">
                  <i class="fa-solid fa-user-gear"></i>
                  <span class="ms-2 me-2 menu-text">Técnicos</span>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php }
        $permiso_unidad = isset($permisos['unidad']['ver']['estado']) && $permisos['unidad']['ver']['estado'] == "1";
        $permiso_dependencia = isset($permisos['dependencia']['ver']['estado']) && $permisos['dependencia']['ver']['estado'] == "1";
        $permiso_ente = isset($permisos['ente']['ver']['estado']) && $permisos['ente']['ver']['estado'] == "1";
        $permiso_marca = isset($permisos['marca']['ver']['estado']) && $permisos['marca']['ver']['estado'] == "1";
        $permiso_tipo_servicio = isset($permisos['tipo_servicio']['ver']['estado']) && $permisos['tipo_servicio']['ver']['estado'] == "1";
        $permiso_cargo = isset($permisos['cargo']['ver']['estado']) && $permisos['cargo']['ver']['estado'] == "1";
        $permiso_tipo_bien = isset($permisos['tipo_bien']['ver']['estado']) && $permisos['tipo_bien']['ver']['estado'] == "1";
        if ($permiso_unidad || $permiso_dependencia || $permiso_ente || $permiso_marca || $permiso_tipo_servicio || $permiso_cargo || $permiso_tipo_bien) { ?>
          <!-- CATEGORÍA: CONFIGURACIÓN (SOLO SUPERUSUARIO/ADMIN) -->
          <li class="menu-title">
            <span>Configuración</span>
          </li>

          <li class="menu-item <?php echo in_array($page, ["unidad", "dependencia", "ente", "marca", "tipo_servicio", "cargo", "tipo_bien"]) ? "active" : "" ?>"
            title="Configuración General">
            <a class="nav-link collapsed" data-bs-target="#config-submenu" data-bs-toggle="collapse" href="#">
              <i class="fas fa-cog"></i>
              <span class="ms-2 me-2 menu-text">Configuración</span>
              <i class="fa-solid fa-angle-right"></i>
            </a>
          </li>

          <ul id="config-submenu" style="margin-left: 1em"
            class="nav-content collapse<?php echo in_array($page, ["unidad", "dependencia", "ente", "marca", "tipo_servicio", "cargo", "tipo_bien"]) ? " show" : "" ?>" data-bs-parent="#sidebar-nav">
            <?php if (isset($permisos['unidad']['ver']['estado']) && $permisos['unidad']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "unidad") ? "active" : "" ?>" title="Unidad">
                <a href="?page=unidad">
                  <i class="fa-solid fa-sitemap"></i>
                  <span class="ms-2 me-2 menu-text">Unidades</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['dependencia']['ver']['estado']) && $permisos['dependencia']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "dependencia") ? "active" : "" ?>" title="Dependencia">
                <a href="?page=dependencia">
                  <i class="fa-solid fa-diagram-project"></i>
                  <span class="ms-2 me-2 menu-text">Dependencias</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['ente']['ver']['estado']) && $permisos['ente']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "ente") ? "active" : "" ?>">
                <a href="?page=ente">
                  <i class="fa-solid fa-building"></i>
                  <span class="ms-2 me-2 menu-text">Entes</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['marca']['ver']['estado']) && $permisos['marca']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "marca") ? "active" : "" ?>" title="Marca">
                <a href="?page=marca">
                  <i class="fa-solid fa-trademark"></i>
                  <span class="ms-2 me-2 menu-text">Marcas</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['tipo_servicio']['ver']['estado']) && $permisos['tipo_servicio']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "tipo_servicio") ? "active" : "" ?>" title="Tipo de Servicio">
                <a href="?page=tipo_servicio">
                  <i class="fa-solid fa-screwdriver-wrench"></i>
                  <span class="ms-2 me-2 menu-text">Tipos de Servicio</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['cargo']['ver']['estado']) && $permisos['cargo']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "cargo") ? "active" : "" ?>" title="Cargo">
                <a href="?page=cargo">
                  <i class="fa-solid fa-id-badge"></i>
                  <span class="ms-2 me-2 menu-text">Cargos</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['tipo_bien']['ver']['estado']) && $permisos['tipo_bien']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "tipo_bien") ? "active" : "" ?>" title="Tipo de Bien">
                <a href="?page=tipo_bien">
                  <i class="fa-solid fa-tag"></i>
                  <span class="ms-2 me-2 menu-text">Tipos de Bien</span>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php }
        $permiso_usuario = isset($permisos['usuario']['ver']['estado']) && $permisos['usuario']['ver']['estado'] == "1";
        $permiso_rol = isset($permisos['rol']['ver']['estado']) && $permisos['rol']['ver']['estado'] == "1";
        $permiso_modulo = isset($permisos['modulo_sistema']['ver']['estado']) && $permisos['modulo_sistema']['ver']['estado'] == "1";
        $permiso_bitacora = isset($permisos['bitacora']['ver']['estado']) && $permisos['bitacora']['ver']['estado'] == "1";
        $permiso_mantenimiento = isset($permisos['mantenimiento']['ver']['estado']) && $permisos['mantenimiento']['ver']['estado'] == "1";
        if ($permiso_usuario || $permiso_rol || $permiso_modulo || $permiso_bitacora || $permiso_mantenimiento) { ?>
          <!-- CATEGORÍA: SEGURIDAD (SOLO SUPERUSUARIO/ADMIN) -->
          <li class="menu-title">
            <span>Seguridad</span>
          </li>

          <li class="menu-item <?php echo in_array($page, ["usuario", "rol", "modulo_sistema", "bitacora", "backup"]) ? "active" : "" ?>"
            title="Módulo de Seguridad">
            <a class="nav-link collapsed" data-bs-target="#seguridad-submenu" data-bs-toggle="collapse" href="#">
              <i class="fa-solid fa-shield-halved"></i>
              <span class="ms-2 me-2 menu-text">Seguridad</span>
              <i class="fa-solid fa-angle-right"></i>
            </a>
          </li>

          <ul id="seguridad-submenu" style="margin-left: 1em;"
            class="nav-content collapse<?php echo in_array($page, ["usuario", "rol", "modulo_sistema", "bitacora", "backup"]) ? " show" : "" ?>" data-bs-parent="#sidebar-nav">
            <?php if (isset($permisos['usuario']['ver']['estado']) && $permisos['usuario']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "usuario") ? "active" : "" ?>" title="Gestión de Usuarios">
                <a href="?page=usuario">
                  <i class="fa-solid fa-user-shield"></i>
                  <span class="ms-2 me-2 menu-text">Usuarios</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['rol']['ver']['estado']) && $permisos['rol']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "rol") ? "active" : "" ?>" title="Roles y Permisos">
                <a href="?page=rol">
                  <i class="fa-solid fa-user-lock"></i>
                  <span class="ms-2 me-2 menu-text">Roles y Permisos</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['modulo_sistema']['ver']['estado']) && $permisos['modulo_sistema']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "modulo_sistema") ? "active" : "" ?>" title="Módulos del Sistema">
                <a href="?page=modulo_sistema">
                  <i class="fa-solid fa-microchip"></i>
                  <span class="ms-2 me-2 menu-text">Módulos del Sistema</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['bitacora']['ver']['estado']) && $permisos['bitacora']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "bitacora") ? "active" : "" ?>" title="Bitácora">
                <a href="?page=bitacora">
                  <i class="fa-solid fa-clipboard-list"></i>
                  <span class="ms-2 me-2 menu-text">Bitácora</span>
                </a>
              </li>
            <?php } ?>
            <?php if (isset($permisos['mantenimiento']['ver']['estado']) && $permisos['mantenimiento']['ver']['estado'] == "1") { ?>
              <li class="menu-item <?php echo ($page == "backup") ? "active" : "" ?>" title="BackUp">
                <a href="?page=backup">
                  <i class="fa-solid fa-database"></i>
                  <span class="ms-2 me-2 menu-text">Backups</span>
                </a>
              </li>
            <?php } ?>
          </ul>
        <?php } ?>
      </ul>
    </nav>
  </div>

  <div class="sidebar-footer">
    <ul>
      <li class="menu-item <?php echo ($page == "ayuda") ? "active" : "" ?>" title="Ayuda">
        <a href="?page=ayuda">
          <i class="fas fa-question-circle"></i>
          <span class="ms-2 me-2 menu-text">Ayuda</span>
        </a>
      </li>
    </ul>
  </div>
</aside>

<!-- Resto del código permanece igual -->

<!-- Main Content -->
<div class="main-content">
  <!-- Header/Top Navigation -->
  <header class="top-nav">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-auto d-lg-none">
          <button id="sidebar-toggle" class="sidebar-toggle">
            <i class="fas fa-bars" style="pointer-events: none;"></i>
          </button>
        </div>

        <div class="col d-none d-md-block">
          <nav class="breadcrumb-nav">
            <a href="#" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="#" class="breadcrumb-item">Dashboard</a>
          </nav>
        </div>

        <div class="col-auto ms-auto">
          <div class="top-nav-actions">
            <!-- donde van las notificaciones por que se me pierden -->
            <div class="action-item notification-dropdown">
              <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge"></span>
              </button>
              <div class="dropdown-menu notification-menu">
                <div class="dropdown-header">
                  <h6>Notificaciones</h6>
                  <button class="close-dropdown">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <div class="dropdown-body" id="notificaciones-container">

                  <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Cargando...</span>
                    </div>
                  </div>
                </div>
                <div class="dropdown-footer">
                  <a href="?page=notificacion" class="btn btn-sm btn-primary w-100">Ver todas</a>
                </div>
              </div>
            </div>

            <div class="action-item">
              <button id="theme-toggle" class="theme-toggle">
                <i class="fas fa-moon dark-icon"></i>
                <i class="fas fa-sun light-icon"></i>
              </button>
            </div>

            <div class="action-item user-dropdown">
              <button class="user-dropdown-toggle">
                <div class="avatar">
                  <img src="<?php echo $foto; ?>" alt="User Avatar" />
                </div>
              </button>
              <div class="dropdown-menu user-menu">
                <div class="dropdown-header">
                  <h6><?php echo $datos["nombres"] . " " . $datos["apellidos"]; ?></h6>
                  <span><?php echo $datos["cedula"] . "/" . $datos["rol"]; ?></span>
                </div>
                <div class="dropdown-body">
                  <ul>
                    <li class="menu-item <?php echo ($page == "users-profile") ? "active" : "" ?>">
                      <a href="?page=users-profile">
                        <i class="menu-text-p fas fa-question-circle"></i>
                        <span class="menu-text-p">Perfil</span>
                      </a>
                    </li>

                    <li class="menu-item">
                      <a href="?page=closet">
                        <i class="menu-text-p fa-solid fa-arrow-right-to-bracket"></i>
                        <span class="menu-text-p">Cerrar Sesión</span>
                      </a>
                    </li>

                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Page Content -->
  <main class="page-content" style="flex: 1;">



    </head>
    <link rel="icon" href="assets/img/favicon.ico">

    <head></head>
    </head>