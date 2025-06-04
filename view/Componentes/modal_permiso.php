<div class="modal fade" id="modalPermiso" tabindex="-1" role="dialog" aria-labelledby="modalTitleIdPermiso" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleIdPermiso"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="formulario_permiso">
        <div class="row justify-content-center" id="Fila1">
          <div class="col-8">
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
          <fieldset class="permission-group gap-2 col-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-usuario" data-modulo="usuario">
                <label class="form-check-label" for="group-usuario">Gestionar Usuario</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="usuario">
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar" id="usuario-registrar">
                  <label class="form-check-label" for="usuario-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver" id="usuario-ver">
                  <label class="form-check-label" for="usuario-ver">Ver</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar" id="usuario-modificar">
                  <label class="form-check-label" for="usuario-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-3">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar" id="usuario-eliminar">
                  <label class="form-check-label" for="usuario-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- Grupo Rol -->
          <fieldset class="permission-group col-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-rol" data-modulo="rol">
                <label class="form-check-label" for="group-rol">Gestionar Rol</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="rol">
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar" id="rol-registrar">
                  <label class="form-check-label" for="rol-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver" id="rol-ver">
                  <label class="form-check-label" for="rol-ver">Ver</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar" id="rol-modificar">
                  <label class="form-check-label" for="rol-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar" id="rol-eliminar">
                  <label class="form-check-label" for="rol-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-empleado" data-modulo="empleado">
                <label class="form-check-label" for="group-empleado">Gestionar Empleado</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="empleado">
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar" id="empleado-registrar">
                  <label class="form-check-label" for="empleado-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver" id="empleado-ver">
                  <label class="form-check-label" for="empleado-ver">Ver</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar" id="empleado-modificar">
                  <label class="form-check-label" for="empleado-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar" id="empleado-eliminar">
                  <label class="form-check-label" for="empleado-eliminar">Eliminar</label>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="permission-group col-6">
            <legend class="group-header">
              <div class="form-check form-check-inline">
                <input class="form-check-input group-checkbox" type="checkbox" id="group-tecnico" data-modulo="tecnico">
                <label class="form-check-label" for="group-tecnico">Gestionar Técnico</label>
              </div>
            </legend>
            <div class="row permission-options" data-modulo-string="tecnico">
              <!-- Opciones de permisos -->
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="registrar" id="tecnico-registrar">
                  <label class="form-check-label" for="tecnico-registrar">Registrar</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="ver" id="tecnico-ver">
                  <label class="form-check-label" for="tecnico-ver">Ver</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="modificar" id="tecnico-modificar">
                  <label class="form-check-label" for="tecnico-modificar">Modificar</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch">
                  <input class="form-check-input permission-checkbox" type="checkbox" role="switch" value="eliminar" id="tecnico-eliminar">
                  <label class="form-check-label" for="tecnico-eliminar">Eliminar</label>
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
  /* Estilos para los grupos de permisos */
  .permissions-section {
    padding: 15px;
  }

  .permission-group {
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
    background-color: #f8f9fa;
  }

  .permission-group legend.group-header {
    width: auto;
    padding: 0 10px;
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 0;
    border-bottom: none;
  }

  .permission-options {
    margin-top: 10px;
    padding: 10px;
    background-color: white;
    border-radius: 4px;
  }

  .form-check-inline .form-check-input {
    margin-right: 8px;
  }

  .group-checkbox {
    transform: scale(1.2);
    margin-right: 8px;
  }
</style>

<script>
  // Script para manejar la selección de grupos completos
  document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar/deseleccionar todos los permisos de un grupo
    document.querySelectorAll('.group-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        const modulo = this.getAttribute('data-modulo');
        const isChecked = this.checked;

        document.querySelectorAll(`.permission-options[data-modulo-string="${modulo}"] .permission-checkbox`).forEach(perm => {
          perm.checked = isChecked;
        });
      });
    });

    // Actualizar el checkbox del grupo cuando cambian los permisos individuales
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', function() {
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