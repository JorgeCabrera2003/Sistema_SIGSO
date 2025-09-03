<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true"
  data-bs-backdrop="static">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content card">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="modalTitleId"></h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center" id="Fila1">
          <div class="col-lg-4">
            <div class="form-floating mb-3 mt-4">
              <input placeholder="" class="form-control" name="nombre" type="text" id="nombre" maxlength="200">
              <span id="snombre"></span>
              <label for="nombre" class="form-label">Nombre del Servicio</label>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-floating mb-3 mt-4">
              <select class="form-select" name="encargado" id="encargado">
              </select>
              <span id="sencargado"></span>
              <label for="encargado" class="form-label">Seleccione un Encargado</label>
            </div>
          </div>
        </div>
        <div class="row mt-4" id="inputs_servicios">
          <div class="col-lg-6">
            <fieldset class="permission-group" id="divServicio">
              <legend class="group-header">
                <label class="" for="servicios">Servicios Prestados: </label>
                <button type="button" class="btn btn-primary mx-auto my-4 " id="btn-agregarS">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </legend>
              <div class="container btn-agregarS" id="container-servicio">

              </div>
            </fieldset>
          </div>
          <div class="col-lg-6">
            <fieldset class="permission-group" id="divComponente">
              <legend class="group-header">
                <label class="" for="servicios">Componentes a Atender: </label>
                <button type="button" class="btn btn-primary mx-auto my-4 " id="btn-agregarC">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </legend>
              <div class="container btn-agregarC" id="container-componente">

              </div>
            </fieldset>
          </div>
        </div>
        <div class="row mt-4" id="inputs_tablas">
          <div class="col-lg-6">
            <fieldset class="permission-group">
              <legend class="group-header">
                <div class="form-check form-check-inline text-center">
                  <label class="" for="servicios">Servicios Prestados</label>
                </div>
              </legend>
              <button type="button" class="btn btn-primary mx-auto my-4" id="btn-configuarS">
                Configurar
              </button>
              <div class="table-responsive">
                <table class="table" id="tabla_servicio">
                  <thead>
                    <tr>
                      <th scope='col'>#</th>
                      <th scope='col'>Nombre del Servico</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </fieldset>
          </div>
          <div class="col-lg-6">
            <fieldset class="permission-group">
              <legend class="group-header">
                <div class="form-check form-check-inline text-center">
                  <label class="" for="servicios">Componentes a Atender</label>
                </div>
              </legend>
              <button type="button" class="btn btn-primary mx-auto my-4" id="btn-configuarC">
                Configurar
              </button>
              <div class="table-responsive">
                <table class="table" id="tabla_componentes">
                  <thead>
                    <tr>
                      <th scope='col'>#</th>
                      <th scope='col'>Componente</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </fieldset>
          </div>
        </div>
      </div>


      <div class="row d-none" id="tabla_servicios">
        <div class="col-md-12">
          <fieldset class="permission-group">
            <legend class="group-header">
              <div class="form-check form-check-inline text-center">
                <label class="" for="servicios">Servicios Prestados</label>
              </div>
            </legend>
            <button type="button" class="btn btn-primary mx-auto my-4" id="btn-configuarS">
              Configurar
            </button>
            <div class="table-responsive">
              <table class="table" id="tabla_servicio">
                <thead>
                  <tr>
                    <th scope='col'>#</th>
                    <th scope='col'>Nombre del Servico</th>
                    <th scope='col'>Modificar/Eliminar</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </fieldset>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar" name="" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>