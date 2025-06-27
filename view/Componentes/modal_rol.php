<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center" id="Fila1">
          <div class="col-md-8">
            <div class=" input-group">
              <button class="btn btn-success" disabled>
                <i class="fa-solid fa-user-tie"></i>
              </button>
              <div class="form-floating ">
                <input placeholder="" class="form-control" name="nombre" type="text" id="nombre" maxlength="45">
                <span id="snombre"></span>
                <label for="nombre" class="form-label">Nombre del Rol</label>
              </div>
            </div>
          </div>
        </div>

        <div class="permissions-section row">
          <!-- Grupo Usuario -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-usuario" data-modulo="1">
                <label class="form-check-label" for="group-usuario">Gestionar Usuario</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="1">
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="usuario-registrar">
                  <label class="form-check-label" for="usuario-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="usuario-ver">
                  <label class="form-check-label" for="usuario-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="usuario-modificar">
                  <label class="form-check-label" for="usuario-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="usuario-eliminar">
                  <label class="form-check-label" for="usuario-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="usuario-restaurar">
                  <label class="form-check-label" for="usuario-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Rol -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-rol" data-modulo="2">
                <label class="form-check-label" for="group-rol">Gestionar Rol</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="2">
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="rol-registrar">
                  <label class="form-check-label" for="rol-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="rol-ver">
                  <label class="form-check-label" for="rol-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="rol-modificar">
                  <label class="form-check-label" for="rol-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="rol-eliminar">
                  <label class="form-check-label" for="rol-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="rol-restaurar">
                  <label class="form-check-label" for="rol-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Bitácora -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-bitacora" data-modulo="3">
                <label class="form-check-label" for="group-bitacora">Gestionar Bitácora</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="3">
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="bitacora-ver">
                  <label class="form-check-label" for="bitacora-ver">Ver</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Mantenimiento -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-mantenimiento" data-modulo="4">
                <label class="form-check-label" for="group-mantenimiento">Gestionar Mantenimiento</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="4">
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="mantenimiento-ver">
                  <label class="form-check-label" for="mantenimiento-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="exportar" id="mantenimiento-exportar">
                  <label class="form-check-label" for="mantenimiento-exportar">Exportar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="importar" id="mantenimiento-importar">
                  <label class="form-check-label" for="mantenimiento-importar">Importar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="mantenimiento-eliminar">
                  <label class="form-check-label" for="mantenimiento-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Empleado -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-empleado" data-modulo="5">
                <label class="form-check-label" for="group-empleado">Gestionar Empleado</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="5">
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="empleado-registrar">
                  <label class="form-check-label" for="empleado-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="empleado-ver">
                  <label class="form-check-label" for="empleado-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="empleado-modificar">
                  <label class="form-check-label" for="empleado-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="empleado-eliminar">
                  <label class="form-check-label" for="empleado-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="empleado-restaurar">
                  <label class="form-check-label" for="empleado-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Técnico -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-tecnico" data-modulo="6">
                <label class="form-check-label" for="group-tecnico">Gestionar Técnico</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="6">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="tecnico-registrar">
                  <label class="form-check-label" for="tecnico-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="tecnico-ver">
                  <label class="form-check-label" for="tecnico-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="tecnico-modificar">
                  <label class="form-check-label" for="tecnico-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="tecnico-eliminar">
                  <label class="form-check-label" for="tecnico-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="tecnico-restaurar">
                  <label class="form-check-label" for="tecnico-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Solitud -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-solicitud" data-modulo="7">
                <label class="form-check-label" for="group-solicitud">Gestionar Solicitud</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="7">
              <!-- Opciones de permisos -->
              <div class="col-lg-12">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="solicitud-registrar">
                  <label class="form-check-label" for="solicitud-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver_solicitud" id="solicitudes-ver">
                  <label class="form-check-label" for="solicitudes-ver">Solicitudes</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver_mi_solicitud" id="mis_solicitudes-ver">
                  <label class="form-check-label" for="solicitudes-ver">Mis Solicitudes</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="solicitud-modificar">
                  <label class="form-check-label" for="solicitud-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="solicitud-eliminar">
                  <label class="form-check-label" for="solicitud-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="solicitud-restaurar">
                  <label class="form-check-label" for="solicitud-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Hoja de Servicio -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-hoja_servicio" data-modulo="8">
                <label class="form-check-label" for="group-hoja_servicio">Gestionar Hoja de Servicio</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="8">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="hoja_servicio-registrar">
                  <label class="form-check-label" for="hoja_servicio-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="hoja_servicio-ver">
                  <label class="form-check-label" for="hoja_servicio-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="hoja_servicio-modificar">
                  <label class="form-check-label" for="hoja_servicio-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="hoja_servicio-eliminar">
                  <label class="form-check-label" for="hoja_servicio-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="hoja_servicio-restaurar">
                  <label class="form-check-label" for="hoja_servicio-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Ente -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-ente" data-modulo="9">
                <label class="form-check-label" for="group-ente">Gestionar Ente</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="9">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="ente-registrar">
                  <label class="form-check-label" for="ente-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="ente-ver">
                  <label class="form-check-label" for="ente-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="ente-modificar">
                  <label class="form-check-label" for="ente-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="ente-eliminar">
                  <label class="form-check-label" for="ente-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="ente-restaurar">
                  <label class="form-check-label" for="ente-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Dependencia -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-dependencia" data-modulo="10">
                <label class="form-check-label" for="group-dependencia">Gestionar Dependencia</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="10">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="dependencia-registrar">
                  <label class="form-check-label" for="dependencia-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="dependencia-ver">
                  <label class="form-check-label" for="dependencia-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="dependencia-modificar">
                  <label class="form-check-label" for="dependencia-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="dependencia-eliminar">
                  <label class="form-check-label" for="dependencia-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="dependencia-restaurar">
                  <label class="form-check-label" for="dependencia-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Unidad -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-unidad" data-modulo="11">
                <label class="form-check-label" for="group-unidad">Gestionar Unidad</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="11">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="unidad-registrar">
                  <label class="form-check-label" for="unidad-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="unidad-ver">
                  <label class="form-check-label" for="unidad-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="unidad-modificar">
                  <label class="form-check-label" for="unidad-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="unidad-eliminar">
                  <label class="form-check-label" for="unidad-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="unidad-restaurar">
                  <label class="form-check-label" for="unidad-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Cargo -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-cargo" data-modulo="12">
                <label class="form-check-label" for="group-cargo">Gestionar Cargo</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="12">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="cargo-registrar">
                  <label class="form-check-label" for="cargo-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="cargo-ver">
                  <label class="form-check-label" for="cargo-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="cargo-modificar">
                  <label class="form-check-label" for="cargo-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="cargo-eliminar">
                  <label class="form-check-label" for="cargo-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="cargo-restaurar">
                  <label class="form-check-label" for="cargo-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Tipo de Servicio -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-tipo_servicio"
                  data-modulo="13">
                <label class="form-check-label" for="group-tipo_servicio">Gestionar Tipo de Servicio</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="13">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="tipo_servicio-registrar">
                  <label class="form-check-label" for="tipo_servicio-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="tipo_servicio-ver">
                  <label class="form-check-label" for="tipo_servicio-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="tipo_servicio-modificar">
                  <label class="form-check-label" for="tipo_servicio-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="tipo_servicio-eliminar">
                  <label class="form-check-label" for="tipo_servicio-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="cargo-restaurar">
                  <label class="form-check-label" for="cargo-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Bien -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-bien" data-modulo="14">
                <label class="form-check-label" for="group-bien">Gestionar Bien</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="14">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="bien-registrar">
                  <label class="form-check-label" for="bien-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="bien-ver">
                  <label class="form-check-label" for="bien-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="bien-modificar">
                  <label class="form-check-label" for="bien-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="bien-eliminar">
                  <label class="form-check-label" for="bien-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="bien-restaurar">
                  <label class="form-check-label" for="bien-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Tipo de Bien -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-tipo_bien" data-modulo="15">
                <label class="form-check-label" for="group-bien">Gestionar Tipo de Bien</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="15">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="tipo_bien-registrar">
                  <label class="form-check-label" for="tipo_bien-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="tipo_bien-ver">
                  <label class="form-check-label" for="tipo_bien-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="tipo_bien-modificar">
                  <label class="form-check-label" for="tipo_bien-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="tipo_bien-eliminar">
                  <label class="form-check-label" for="tipo_bien-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="tipo_bien-restaurar">
                  <label class="form-check-label" for="tipo_bien-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Marca -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-marca" data-modulo="16">
                <label class="form-check-label" for="group-bien">Gestionar Marca</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="16">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="marca-registrar">
                  <label class="form-check-label" for="marca-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="marca-ver">
                  <label class="form-check-label" for="marca-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="marca-modificar">
                  <label class="form-check-label" for="marca-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="marca-eliminar">
                  <label class="form-check-label" for="marca-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="marca-restaurar">
                  <label class="form-check-label" for="marca-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Equipo -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-equipo" data-modulo="17">
                <label class="form-check-label" for="group-bien">Gestionar Equipo</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="17">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="equipo-registrar">
                  <label class="form-check-label" for="equipo-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="equipo-ver">
                  <label class="form-check-label" for="equipo-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="equipo-modificar">
                  <label class="form-check-label" for="equipo-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="equipo-eliminar">
                  <label class="form-check-label" for="equipo-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="equipo-restaurar">
                  <label class="form-check-label" for="equipo-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Switch -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-switch" data-modulo="18">
                <label class="form-check-label" for="group-switch">Gestionar Switch</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="18">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="switch-registrar">
                  <label class="form-check-label" for="switch-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="switch-ver">
                  <label class="form-check-label" for="switch-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="switch-modificar">
                  <label class="form-check-label" for="switch-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="switch-eliminar">
                  <label class="form-check-label" for="switch-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="switch-restaurar">
                  <label class="form-check-label" for="switch-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Patch Panel -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-patch_panel" data-modulo="19">
                <label class="form-check-label" for="group-patch_panel">Gestionar Patch Panel</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="19">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="patch_panel-registrar">
                  <label class="form-check-label" for="patch_panel-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="patch_panel-ver">
                  <label class="form-check-label" for="patch_panel-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="patch_panel-modificar">
                  <label class="form-check-label" for="patch_panel-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="patch_panel-eliminar">
                  <label class="form-check-label" for="patch_panel-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="patch_panel-restaurar">
                  <label class="form-check-label" for="patch_panel-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Interconexión -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-interconexion"
                  data-modulo="20">
                <label class="form-check-label" for="group-interconexion">Gestionar Interconexión</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="20">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="interconexion-registrar">
                  <label class="form-check-label" for="interconexion-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="interconexion-ver">
                  <label class="form-check-label" for="interconexion-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="interconexion-modificar">
                  <label class="form-check-label" for="interconexion-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="interconexion-eliminar">
                  <label class="form-check-label" for="interconexion-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Punto de Conexión -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-punto_conexion"
                  data-modulo="21">
                <label class="form-check-label" for="group-punto_conexion">Gestionar Punto de Conexión</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="21">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="punto_conexion-registrar">
                  <label class="form-check-label" for="punto_conexion-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="punto_conexion-ver">
                  <label class="form-check-label" for="punto_conexion-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="punto_conexion-modificar">
                  <label class="form-check-label" for="punto_conexion-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="punto_conexion-eliminar">
                  <label class="form-check-label" for="punto_conexion-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Piso -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-piso" data-modulo="22">
                <label class="form-check-label" for="group-piso">Gestionar Piso</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="22">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="piso-registrar">
                  <label class="form-check-label" for="piso-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="piso-ver">
                  <label class="form-check-label" for="piso-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="piso-modificar">
                  <label class="form-check-label" for="piso-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="piso-eliminar">
                  <label class="form-check-label" for="piso-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="piso-restaurar">
                  <label class="form-check-label" for="piso-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Oficina -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-oficina" data-modulo="23">
                <label class="form-check-label" for="group-oficina">Gestionar Oficina</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="23">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="oficina-registrar">
                  <label class="form-check-label" for="oficina-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="oficina-ver">
                  <label class="form-check-label" for="oficina-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="oficina-modificar">
                  <label class="form-check-label" for="oficina-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="oficina-eliminar">
                  <label class="form-check-label" for="oficina-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="oficina-restaurar">
                  <label class="form-check-label" for="oficina-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Material -->
          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-material" data-modulo="24">
                <label class="form-check-label" for="group-material">Gestionar Material</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="24">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="registrar" id="material-registrar">
                  <label class="form-check-label" for="material-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="ver" id="material-ver">
                  <label class="form-check-label" for="material-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="modificar" id="material-modificar">
                  <label class="form-check-label" for="material-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="eliminar" id="material-eliminar">
                  <label class="form-check-label" for="material-eliminar">Eliminar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox" role="switch"
                    value="restaurar" id="material-restaurar">
                  <label class="form-check-label" for="material-restaurar">Restaurar</label>
                </div>
              </div>
            </div>
          </fieldset>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar" name="" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>

<script>
  // Script para manejar la selección de grupos completos
  document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar/deseleccionar todos los permisos de un grupo
    document.querySelectorAll('.group-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        const modulo = this.getAttribute('data-modulo');
        const isChecked = this.checked;

        document.querySelectorAll(`.permission-options[data-modulo-string="${modulo}"] .permission-checkbox`).forEach(perm => {
          perm.checked = isChecked;
        });
      });
    });

    // Actualizar el checkbox del grupo cuando cambian los permisos individuales
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        const groupId = this.closest('.permission-options').getAttribute('data-modulo-string');
        const groupCheckbox = document.querySelector(`.group-checkbox[data-modulo="${groupId}"]`);
        const allCheckboxes = document.querySelectorAll(`.permission-options[data-modulo-string="${groupId}"] .permission-checkbox`);

        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(allCheckboxes).some(cb => cb.checked);

        groupCheckbox.checked = allChecked;
        groupCheckbox.indeterminate = someChecked && !allChecked;
      });
    });
  });
</script>