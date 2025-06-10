<!-- Modal -->
<div class="modal fade modal-xl card" id="solicitud" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mostra" aria-hidden="true">
  <form method="post" class="modal-dialog" autocomplete="off" id="formSolicitud">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="FallaLabel">Solicitud</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <label for="motivo" class="form-label">Motivo</label>
            <input class="form-control" placeholder="Describa el servicio solicitado" name="motivo" type="text" id="motivo2" required maxlength="200">
            <div class="invalid-feedback" id="smotivo2">El motivo debe tener entre 3 y 200 caracteres</div>
          </div>
          <div class="col-4">
            <label for="nro" class="form-label">N° Solicitud</label>
            <input type="text" readonly class="form-control-plaintext" id="nro" name="nrosol">
          </div>
          <div class="col-4">
            <label for="solicitante" class="form-label">Solicitante</label>
            <select class="form-control" id="solicitante2" name="cedula" required>
              <option value="" selected hidden>Seleccione un solicitante</option>
            </select>
            <div class="invalid-feedback">Seleccione un solicitante</div>
          </div>
          <div class="col-4">
            <label for="area" class="form-label">Area</label>
            <select class="form-control" name="area" id="area2" required>
              <option selected hidden value="">Seleccione una opcion</option>
              <option value="1">Soporte técnico</option>
              <option value="4">Electrónica</option>
              <option value="2">Redes</option>
              <option value="3">telefonía</option>
            </select>
            <div class="invalid-feedback">Seleccione un área</div>
          </div>
        </div>

        <div class="nav-content row">
          <div class="col-sm-6">
            <label for="dependencia" class="form-label">Dependencia</label>
            <select name="dependencia" class="form-select" id="dependencia2" required>
              <option value="" selected hidden>Seleccionar</option>
            </select>
            <div class="invalid-feedback">Seleccione una dependencia</div>
          </div>

          <div class="col-sm-6">
            <label class="input-label" for="serial" class="form-label">Serial del equipo</label>
            <select class="form-control my-3" id="equipo2" name="serial">
              <option value="" selected>Seleccionar</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="close" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" name="registrar" class="btn btn-primary registrar" id="enviar2" disabled>Enviar</button>
      </div>
    </div>
  </form>
</div>