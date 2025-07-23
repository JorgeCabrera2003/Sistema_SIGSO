<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-lg dialog-scrollable" role="document">
        <div class="modal-content card">
            <div class="modal-header card-header">
                <h5 class="modal-title" id="modalTitleId"></h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Checkbox para activar el registro de equipo (solo visible en Registrar) -->
                <div class="row" id="row-registro-equipo" style="display:none;">
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkRegistrarEquipo">
                            <label class="form-check-label" for="checkRegistrarEquipo">
                                Registrar equipo asociado a este bien
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Carrusel para registrar equipo -->
                <div id="carruselEquipo" class="carousel slide mb-4" data-bs-ride="carousel" style="display:none;">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="serial_equipo" placeholder="Serial del equipo">
                                        <label for="serial_equipo">Serial del equipo</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="tipo_equipo" placeholder="Tipo de equipo">
                                        <label for="tipo_equipo">Tipo de equipo</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="id_unidad_equipo">
                                            <option value="default">Seleccione una unidad</option>
                                        </select>
                                        <span id="sid_unidad_equipo"></span>
                                        <label for="id_unidad_equipo">Unidad</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Puedes agregar más .carousel-item aquí si necesitas más pasos/campos -->
                    </div>
                </div>
                <div class="row justify-content-center" id="Fila1">
                    <div class="col-4">
                        <div class="form-floating mb-3 mt-4">
                            <input placeholder="" class="form-control" name="codigo_bien" type="text" id="codigo_bien">
                            <span id="scodigo_bien"></span>
                            <label for="codigo_bien" class="form-label">Código del Bien</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-floating mb-3 mt-4">
                            <input placeholder="" class="form-control" name="descripcion" type="text" id="descripcion" maxlength="100">
                            <span id="sdescripcion"></span>
                            <label for="descripcion" class="form-label">Descripción</label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-6">
                        <div class="form-floating mb-3 mt-4">
                            <select class="form-select" name="id_categoria" id="id_categoria">
                                <option value="default">Seleccione un tipo</option>

                            </select>
                            <span id="sid_categoria"></span>
                            <label for="id_categoria" class="form-label">Categoria</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3 mt-4">
                            <select class="form-select" name="id_marca" id="id_marca">
                                <option value="default">Seleccione una marca</option>

                            </select>
                            <span id="sid_marca"></span>
                            <label for="id_marca" class="form-label">Marca</label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-6">
                        <div class="form-floating mb-3 mt-4">
                            <select class="form-select" name="estado" id="estado">
                                <option value="default">Seleccione un estado</option>
                                <option value="Nuevo">Nuevo</option>
                                <option value="Usado">Usado</option>
                                <option value="Dañado">Dañado</option>
                                <option value="En Reparación">En Reparación</option>
                                <option value="Obsoleto">Obsoleto</option>
                            </select>
                            <span id="sestado"></span>
                            <label for="estado" class="form-label">Estado</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3 mt-4">
                            <select class="form-select" name="id_oficina" id="id_oficina">
                                <option value="default">Seleccione una oficina</option>
                            </select>
                            <span id="sid_oficina"></span>
                            <label for="id_oficina" class="form-label">Oficina</label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="form-floating mb-3 mt-4">
                            <select class="form-select" name="cedula_empleado" id="cedula_empleado">
                                <option value="default">Seleccione un empleado</option>
                            </select>
                            <span id="scedula_empleado"></span>
                            <label for="cedula_empleado" class="form-label">Empleado Asignado</label>
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