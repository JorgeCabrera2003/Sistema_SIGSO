<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-lg dialog-scrollable" role="document">
        <div class="modal-content card">
            <div class="modal-header card-header">
                <h5 class="modal-title" id="modalTitleId"></h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="codigo_hoja_servicio">

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mt-4">
                            <input placeholder="" class="form-control" name="nro_solicitud" type="text" id="nro_solicitud" readonly>
                            <span id="snro_solicitud"></span>
                            <label for="nro_solicitud" class="form-label">Número de Solicitud</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mt-4">
                            <select class="form-select" name="id_tipo_servicio" id="id_tipo_servicio">
                                <option value="">Seleccione un tipo</option>
                                <!-- Opciones serán cargadas por JS -->
                            </select>
                            <span id="sid_tipo_servicio"></span>
                            <label for="id_tipo_servicio" class="form-label">Tipo de Servicio</label>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" id="fila-resultado" style="display: none;">
                    <div class="col-md-12">
                        <div class="form-floating mb-3 mt-4">
                            <select placeholder="" class="form-control" name="resultado_hoja_servicio" id="resultado_hoja_servicio">
                                <option value="">Seleccione un resultado</option>
                                <option value="Buen_funcionamiento">Buen funcionamiento</option>
                                <option value="Operativo">Operativo</option>
                                <option value="Sin_funcionar">Sin funcionar</option>
                            </select>
                            <span id="sresultado_hoja_servicio"></span>
                            <label for="resultado_hoja_servicio" class="form-label">Resultado</label>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" id="fila-observacion" style="display: none;">
                    <div class="col-md-12">
                        <div class="form-floating mb-3 mt-4">
                            <textarea class="form-control" name="observacion" id="observacion" style="height: 100px"></textarea>
                            <span id="sobservacion"></span>
                            <label for="observacion" class="form-label">Observación</label>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" id="fila-detalles" style="display: none;">
    <div class="col-md-12">
        <h5>Detalles Técnicos</h5>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Registre los componentes y materiales utilizados en este servicio
        </div>
        <div class="table-responsive">
            <table class="table" id="tablaDetallesModal">
                <thead>
                    <tr>
                        <th>Componente</th>
                        <th>Detalle</th>
                        <th class="text-center">¿Usa material?</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contenido dinámico -->
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-agregar-detalle">
            <i class="bi bi-plus-circle"></i> Agregar Detalle
        </button>
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