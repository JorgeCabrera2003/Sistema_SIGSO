<!-- Modal para Registrar/Modificar Empleado -->
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-xl dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEmpleado">
          <!-- Fila 1: Nacionalidad y Cédula -->
          <div class="row justify-content-center" id="Fila1">
            <div class="col-sm-2">
              <div class="form-floating mb-3">
                <select class="form-select" id="particle" name="particle" required>
                  <option value="" disabled hidden>Seleccione</option>
                  <option selected value="V-">V-</option>
                  <option value="E-">E-</option>
                </select>
                <label for="particle" class="form-label">Nacionalidad</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-floating mb-3">
                <input placeholder="Cédula de Identidad" class="form-control" name="cedula" type="text" id="cedula"
                  maxlength="8" pattern="\d{8}" title="Debe contener exactamente 8 dígitos numéricos" required>
                <label for="cedula" class="form-label">Cédula de Identidad</label>
                <div class="invalid-feedback" id="scedula">La cédula debe contener solo números (máximo 9 dígitos)</div>
              </div>
            </div>
          </div>

          <!-- Fila 2: Nombre y Apellido -->
          <div class="row justify-content-center" id="Fila2">
            <div class="col-md-6">
              <div class="form-floating mb-3">
                <input placeholder="Nombre" class="form-control" name="nombre" type="text" id="nombre" maxlength="45" 
                  pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,}" title="Solo letras y espacios (mínimo 3 caracteres)" required>
                <label for="nombre" class="form-label">Nombre</label>
                <div class="invalid-feedback" id="snombre">El nombre debe contener solo letras y espacios (mínimo 2 caracteres)</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating mb-3">
                <input placeholder="Apellido" class="form-control" name="apellido" type="text" id="apellido" maxlength="45"
                  pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,}" title="Solo letras y espacios (mínimo 3 caracteres)" required>
                <label for="apellido" class="form-label">Apellido</label>
                <div class="invalid-feedback" id="sapellido">El apellido debe contener solo letras y espacios (mínimo 2 caracteres)</div>
              </div>
            </div>
          </div>

          <!-- Fila 3: Teléfono, Correo y Cargo -->
          <div class="row justify-content-center" id="Fila3">
            <div class="col-md-4">
              <div class="form-floating mb-3">
                <input placeholder="Teléfono" class="form-control" name="telefono" type="text" id="telefono" maxlength="12"
                  pattern="0\d{3}-\d{7}" title="Formato: 0412-1234567 (comience con 0, 11 dígitos en total)" required>
                <label for="telefono" class="form-label">Teléfono</label>
                <div class="invalid-feedback" id="stelefono">El formato debe ser: 0412-1234567</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating mb-3">
                <input placeholder="Correo" class="form-control" name="correo" type="email" id="correo" maxlength="45" required>
                <label for="correo" class="form-label">Correo Electrónico</label>
                <div class="invalid-feedback" id="scorreo">Ingrese un correo electrónico válido</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating mb-3">
                <select class="form-select" name="cargo" id="cargo" required>
                  <option value="" disabled selected hidden>Seleccione un Cargo</option>
                </select>
                <label for="cargo">Cargo</label>
                <div class="invalid-feedback" id="scargo">Debe seleccionar un cargo</div>
              </div>
            </div>
          </div>

          <!-- Fila 4: Estructura Organizativa -->
          <div class="row justify-content-center" id="Fila4">
            <div class="col-md-4">
              <div class="form-floating mb-3">
                <select class="form-select" name="ente" id="ente" required>
                  <option value="" disabled selected hidden>Seleccione un Ente</option>
                </select>
                <label for="ente" class="form-label">Ente</label>
                <div class="invalid-feedback" id="sente">Debe seleccionar un ente</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating mb-3">
                <select class="form-select" name="dependencia" id="dependencia" required>
                  <option value="" disabled selected hidden>Seleccione una Dependencia</option>
                </select>
                <label for="dependencia">Dependencia</label>
                <div class="invalid-feedback" id="sdependencia">Debe seleccionar una dependencia</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating mb-3">
                <select class="form-select" name="unidad" id="unidad" required>
                  <option value="" disabled selected hidden>Seleccione una Unidad</option>
                </select>
                <label for="unidad">Unidad</label>
                <div class="invalid-feedback" id="sunidad">Debe seleccionar una unidad</div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar" name="enviar" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>