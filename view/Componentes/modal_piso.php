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

        </div>
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="form-floating mb-3">
              <select class="form-select" name="tipo_piso" id="tipo_piso">
                <option value="" disabled selected hidden>Seleccione el Tipo de Piso</option>
                <option value="Sótano">Sótano</option>
                <option value="Planta Baja">Planta Baja</option>
                <option value="Piso">Piso</option>
                <option value="Terraza">Terraza</option>
              </select>
              <span id="stipo_piso"></span>
              <label for="tipo_piso">Tipo de Piso</label>
            </div>
          </div>
          <div class="col-4">
            <div class="form-floating mb-3">
              <select class="form-select" name="nro_piso" id="nro_piso">
              <option value="" disabled selected hidden>Seleccione un Nro de Piso</option>
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              </select>
              <span id="snro_piso"></span>
              <label for="nro_piso">Nro de Piso</label>
            </div>
          </div>
        </div>
      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar" name="" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>