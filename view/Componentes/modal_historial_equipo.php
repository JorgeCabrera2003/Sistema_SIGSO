<div class="modal fade" id="modal1_HistorialEquipo" tabindex="-1" role="dialog"
  aria-labelledby="modalTitleId_HistorialEquipo" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId_HistorialEquipo">Historial del Equipo</h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center mb-3" id="Fila1">
          <div class="col-6">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="id_equipoH" type="text" id="id_equipoH" maxlength="200">
              <span id="sid_equipo"></span>
              <label for="id_equipo" class="form-label">ID Equipo</label>
            </div>
          </div>
        </div>
        <div class="row justify-content-center mb-3" id="Fila1">
          <div class="col-6">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="tipo_equipoH" type="text" id="tipo_equipoH" maxlength="200">
              <span id="stipo_equipoH"></span>
              <label for="tipo_equipoH" class="form-label">Tipo de Equipo</label>
            </div>
          </div>
          <div class="col-6">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="serial" type="text" id="serialH" maxlength="200">
              <span id="sserialH"></span>
              <label for="serialH" class="form-label">Serial</label>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table" id="tablaDetalles">
            <thead>
              <tr>
                <th>Nro Solicitud</th>
                <th>Empleado</th>
                <th>Motivo</th>
                <th>ID Hoja de Servicio</th>
                <th>Servicio</th>
                <th>Observación</th>
                <th>Resultado</th>
              </tr>
            </thead>
            <tbody>
              <!-- Contenido dinámico -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>