<div class="modal fade" id="modalCargo" tabindex="-1" role="dialog" aria-labelledby="modalCargoTitle" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-lg dialog-scrollable" role="document">
        <div class="modal-content card">
            <div class="modal-header card-header">
                <h5 class="modal-title" id="modalCargoTitle"></h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center" id= "Fila1">
                    <div class="col-8">
                        <div class="form-floating mb-3 mt-4">
                            <input placeholder="" class="form-control" name="nombre_cargo" type="text" id="nombre_cargo" maxlength="45">
                            <span id="snombre_cargo"></span>
                            <label for="nombre_cargo" class="form-label">Nombre del Cargo *</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="enviarCargo" name="" class="btn btn-primary"></button>
            </div>
        </div>
    </div>
</div>
