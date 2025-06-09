<div class="modal fade" id="modalPermiso" tabindex="-1" role="dialog" aria-labelledby="modalTitleIdPermiso"
  aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleIdPermiso"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="formulario_permiso">
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
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="usuario-registrar">
                  <label class="form-check-label" for="usuario-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="usuario-ver">
                  <label class="form-check-label" for="usuario-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="usuario-modificar">
                  <label class="form-check-label" for="usuario-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="usuario-eliminar">
                  <label class="form-check-label" for="usuario-eliminar">Eliminar</label>
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
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="rol-registrar">
                  <label class="form-check-label" for="rol-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="rol-ver">
                  <label class="form-check-label" for="rol-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="rol-modificar">
                  <label class="form-check-label" for="rol-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="rol-eliminar">
                  <label class="form-check-label" for="rol-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

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
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="bitacora-ver">
                  <label class="form-check-label" for="bitacora-ver">Ver</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-mantenimiento"
                  data-modulo="20">
                <label class="form-check-label" for="group-mantenimiento">Gestionar Mantenimiento</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="20">
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="mantenimiento-ver">
                  <label class="form-check-label" for="mantenimiento-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="exportar"
                    id="mantenimiento-exportar">
                  <label class="form-check-label" for="mantenimiento-exportar">Exportar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="importar"
                    id="mantenimiento-importar">
                  <label class="form-check-label" for="mantenimiento-importar">Importar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="mantenimiento-eliminar">
                  <label class="form-check-label" for="mantenimiento-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-empleado" data-modulo="4">
                <label class="form-check-label" for="group-empleado">Gestionar Empleado</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="4">
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="empleado-registrar">
                  <label class="form-check-label" for="empleado-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="empleado-ver">
                  <label class="form-check-label" for="empleado-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="empleado-modificar">
                  <label class="form-check-label" for="empleado-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="empleado-eliminar">
                  <label class="form-check-label" for="empleado-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-tecnico" data-modulo="5">
                <label class="form-check-label" for="group-tecnico">Gestionar Técnico</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="5">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="tecnico-registrar">
                  <label class="form-check-label" for="tecnico-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="tecnico-ver">
                  <label class="form-check-label" for="tecnico-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="tecnico-modificar">
                  <label class="form-check-label" for="tecnico-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="tecnico-eliminar">
                  <label class="form-check-label" for="tecnico-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>


          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-solicitud" data-modulo="6">
                <label class="form-check-label" for="group-solicitud">Gestionar Solicitud</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="6">
              <!-- Opciones de permisos -->
              <div class="col-lg-12">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="solicitud-registrar">
                  <label class="form-check-label" for="solicitud-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch"
                    value="ver_solicitud" id="solicitudes-ver">
                  <label class="form-check-label" for="solicitudes-ver">Solicitudes</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch"
                    value="ver_mi_solicitud" id="mis_solicitudes-ver">
                  <label class="form-check-label" for="solicitudes-ver">Mis Solicitudes</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="solicitud-modificar">
                  <label class="form-check-label" for="solicitud-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="solicitud-eliminar">
                  <label class="form-check-label" for="solicitud-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-hoja_servicio" data-modulo="7">
                <label class="form-check-label" for="group-hoja_servicio">Gestionar Hoja de Servicio</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="7">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="hoja_servicio-registrar">
                  <label class="form-check-label" for="hoja_servicio-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="hoja_servicio-ver">
                  <label class="form-check-label" for="hoja_servicio-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="hoja_servicio-modificar">
                  <label class="form-check-label" for="hoja_servicio-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="hoja_servicio-eliminar">
                  <label class="form-check-label" for="hoja_servicio-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-ente" data-modulo="8">
                <label class="form-check-label" for="group-ente">Gestionar Ente</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="8">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="ente-registrar">
                  <label class="form-check-label" for="ente-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="ente-ver">
                  <label class="form-check-label" for="ente-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="ente-modificar">
                  <label class="form-check-label" for="ente-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="ente-eliminar">
                  <label class="form-check-label" for="ente-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-dependencia" data-modulo="9">
                <label class="form-check-label" for="group-dependencia">Gestionar Dependencia</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="9">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="dependencia-registrar">
                  <label class="form-check-label" for="dependencia-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="dependencia-ver">
                  <label class="form-check-label" for="dependencia-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="dependencia-modificar">
                  <label class="form-check-label" for="dependencia-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="dependencia-eliminar">
                  <label class="form-check-label" for="dependencia-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-unidad" data-modulo="10">
                <label class="form-check-label" for="group-unidad">Gestionar Unidad</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="9">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="unidad-registrar">
                  <label class="form-check-label" for="unidad-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="unidad-ver">
                  <label class="form-check-label" for="unidad-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="unidad-modificar">
                  <label class="form-check-label" for="unidad-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="unidad-eliminar">
                  <label class="form-check-label" for="unidad-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-piso" data-modulo="11">
                <label class="form-check-label" for="group-piso">Gestionar Piso</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="11">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="piso-registrar">
                  <label class="form-check-label" for="piso-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="piso-ver">
                  <label class="form-check-label" for="piso-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="piso-modificar">
                  <label class="form-check-label" for="piso-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="piso-eliminar">
                  <label class="form-check-label" for="piso-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-md-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-oficina" data-modulo="12">
                <label class="form-check-label" for="group-oficina">Gestionar Oficina</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="12">
              <!-- Opciones de permisos -->
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar"
                    id="oficina-registrar">
                  <label class="form-check-label" for="oficina-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver"
                    id="oficina-ver">
                  <label class="form-check-label" for="oficina-ver">Ver</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar"
                    id="oficina-modificar">
                  <label class="form-check-label" for="oficina-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar"
                    id="oficina-eliminar">
                  <label class="form-check-label" for="oficina-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar_permiso" name="" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>

<style>

</style>

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