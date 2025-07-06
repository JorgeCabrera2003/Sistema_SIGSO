<div class="modal fade" id="modalSolicitud" tabindex="-1" aria-labelledby="modalSolicitudLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalSolicitudLabel">Nueva Solicitud</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form id="formSolicitud" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" id="nroSolicitud" name="nrosol">
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="dependencia" class="form-label">Dependencia</label>
              <select class="form-select" id="dependencia" name="dependencia" required style="width:100%">
                <option value="" selected disabled>Seleccione una dependencia</option>
              </select>
              <div class="invalid-feedback">Seleccione una dependencia</div>
            </div>
            
            <div class="col-md-6">
              <label for="solicitante" class="form-label">Solicitante</label>
              <select class="form-select" id="solicitante" name="cedula" required style="width:100%">
                <option value="" selected disabled>Seleccione un solicitante</option>
              </select>
              <div class="invalid-feedback">Seleccione un solicitante</div>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="equipo" class="form-label">Equipo (Opcional)</label>
              <select class="form-select" id="equipo" name="serial" style="width:100%">
                <option value="" selected>No especificar equipo</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label for="area" class="form-label">Área de Servicio</label>
              <select class="form-select" id="area" name="area" required style="width:100%">
                <option value="" selected disabled>Seleccione un área</option>
                <option value="1">Soporte técnico</option>
                <option value="4">Electrónica</option>
                <option value="2">Redes</option>
                <option value="3">Telefonía</option>
              </select>
              <div class="invalid-feedback">Seleccione un área</div>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="motivo" class="form-label">Motivo de la Solicitud</label>
            <textarea class="form-control" id="motivo" name="motivo" rows="3" 
              placeholder="Describa detalladamente el motivo de la solicitud" required></textarea>
            <div class="invalid-feedback">El motivo debe tener entre 3 y 200 caracteres</div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>