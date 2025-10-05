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
          <div class="col-md-8">
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

        <!-- Accordion de categorías de permisos -->
        <div class="mt-4 accordion permissions-section" id="accordionPermisos">

          <!-- Seguridad -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingSeguridad">
              <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseSeguridad" aria-expanded="true" aria-controls="collapseSeguridad">
                Seguridad
              </button>
            </h2>
            <div id="collapseSeguridad" class="accordion-collapse collapse show" aria-labelledby="headingSeguridad"
              data-bs-parent="#accordionPermisos">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Usuario -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-usuario"
                            data-modulo="USUAR00120251001">
                          <label class="form-check-label" for="group-usuario">Gestionar Usuario</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="USUAR00120251001">
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="usuario-registrar">
                            <label class="form-check-label" for="usuario-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="usuario-ver">
                            <label class="form-check-label" for="usuario-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="usuario-modificar">
                            <label class="form-check-label" for="usuario-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="usuario-eliminar">
                            <label class="form-check-label" for="usuario-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="usuario-reactivar">
                            <label class="form-check-label" for="usuario-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Rol -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-rol" data-modulo="ROL0000220251001">
                          <label class="form-check-label" for="group-rol">Gestionar Rol</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="ROL0000220251001">
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="rol-registrar">
                            <label class="form-check-label" for="rol-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="rol-ver">
                            <label class="form-check-label" for="rol-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="rol-modificar">
                            <label class="form-check-label" for="rol-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="rol-eliminar">
                            <label class="form-check-label" for="rol-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="rol-reactivar">
                            <label class="form-check-label" for="rol-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Bitácora -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-bitacora"
                            data-modulo="BITAC00320251001">
                          <label class="form-check-label" for="group-bitacora">Gestionar Bitácora</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="BITAC00320251001">
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="bitacora-ver">
                            <label class="form-check-label" for="bitacora-ver">Ver</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Mantenimiento -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-mantenimiento"
                            data-modulo="MANTE00420251001">
                          <label class="form-check-label" for="group-mantenimiento">Gestionar Mantenimiento</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="MANTE00420251001">
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="mantenimiento-ver">
                            <label class="form-check-label" for="mantenimiento-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="exportar" id="mantenimiento-exportar">
                            <label class="form-check-label" for="mantenimiento-exportar">Exportar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="importar" id="mantenimiento-importar">
                            <label class="form-check-label" for="mantenimiento-importar">Importar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="mantenimiento-eliminar">
                            <label class="form-check-label" for="mantenimiento-eliminar">Eliminar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Modulo del Sistema -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-modulo_sistema"
                            data-modulo="MODSI02520251001">
                          <label class="form-check-label" for="group-mantenimiento">Módulos del Sistema</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="MODSI02520251001">
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="modulo_sistema-ver">
                            <label class="form-check-label" for="modulo_sistema-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="cargar" id="modulo_sistema-cargar">
                            <label class="form-check-label" for="modulo_sistema-cargar">Cargar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="comprobar" id="modulo_sistema-comprobar">
                            <label class="form-check-label" for="modulo_sistema-comprobar">Comprobar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Personal -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingPersonal">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapsePersonal" aria-expanded="false" aria-controls="collapsePersonal">
                Personal
              </button>
            </h2>
            <div id="collapsePersonal" class="accordion-collapse collapse" aria-labelledby="headingPersonal"
              data-bs-parent="#accordionPermisos">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Empleado -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-empleado"
                            data-modulo="EMPLE00520251001">
                          <label class="form-check-label" for="group-empleado">Gestionar Empleado</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="EMPLE00520251001">
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="empleado-registrar">
                            <label class="form-check-label" for="empleado-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="empleado-ver">
                            <label class="form-check-label" for="empleado-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="empleado-modificar">
                            <label class="form-check-label" for="empleado-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="empleado-eliminar">
                            <label class="form-check-label" for="empleado-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="empleado-reactivar">
                            <label class="form-check-label" for="empleado-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Técnico -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-tecnico"
                            data-modulo="TECNI00620251001">
                          <label class="form-check-label" for="group-tecnico">Gestionar Técnico</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="TECNI00620251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="tecnico-registrar">
                            <label class="form-check-label" for="tecnico-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="tecnico-ver">
                            <label class="form-check-label" for="tecnico-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="tecnico-modificar">
                            <label class="form-check-label" for="tecnico-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="tecnico-eliminar">
                            <label class="form-check-label" for="tecnico-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="tecnico-reactivar">
                            <label class="form-check-label" for="tecnico-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Solicitudes y Servicios -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingSolicitudes">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseSolicitudes" aria-expanded="false" aria-controls="collapseSolicitudes">
                Solicitudes y Servicios
              </button>
            </h2>
            <div id="collapseSolicitudes" class="accordion-collapse collapse" aria-labelledby="headingSolicitudes"
              data-bs-parent="#accordionPermisos">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Solicitud -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-solicitud"
                            data-modulo="SOLIC00720251001">
                          <label class="form-check-label" for="group-solicitud">Gestionar Solicitud</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="SOLIC00720251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-12">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="solicitud-registrar">
                            <label class="form-check-label" for="solicitud-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver_solicitud" id="solicitudes-ver">
                            <label class="form-check-label" for="solicitudes-ver">Solicitudes</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver_mi_solicitud" id="mis_solicitudes-ver">
                            <label class="form-check-label" for="solicitudes-ver">Mis Solicitudes</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="solicitud-modificar">
                            <label class="form-check-label" for="solicitud-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="solicitud-eliminar">
                            <label class="form-check-label" for="solicitud-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="solicitud-reactivar">
                            <label class="form-check-label" for="solicitud-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Hoja de Servicio -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-hoja_servicio"
                            data-modulo="HOJAS00820251001">
                          <label class="form-check-label" for="group-hoja_servicio">Gestionar Hoja de Servicio</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="HOJAS00820251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="hoja_servicio-registrar">
                            <label class="form-check-label" for="hoja_servicio-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="hoja_servicio-ver">
                            <label class="form-check-label" for="hoja_servicio-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="hoja_servicio-modificar">
                            <label class="form-check-label" for="hoja_servicio-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="hoja_servicio-eliminar">
                            <label class="form-check-label" for="hoja_servicio-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="hoja_servicio-reactivar">
                            <label class="form-check-label" for="hoja_servicio-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Organización -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOrganizacion">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOrganizacion" aria-expanded="false" aria-controls="collapseOrganizacion">
                Organización
              </button>
            </h2>
            <div id="collapseOrganizacion" class="accordion-collapse collapse" aria-labelledby="headingOrganizacion"
              data-bs-parent="#accordionPermisos">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Ente -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-ente"
                            data-modulo="ENTE000920251001">
                          <label class="form-check-label" for="group-ente">Gestionar Ente</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="ENTE000920251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="ente-registrar">
                            <label class="form-check-label" for="ente-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="ente-ver">
                            <label class="form-check-label" for="ente-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="ente-modificar">
                            <label class="form-check-label" for="ente-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="ente-eliminar">
                            <label class="form-check-label" for="ente-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="ente-reactivar">
                            <label class="form-check-label" for="ente-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Dependencia -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-dependencia"
                            data-modulo="DEPEN01020251001">
                          <label class="form-check-label" for="group-dependencia">Gestionar Dependencia</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="DEPEN01020251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="dependencia-registrar">
                            <label class="form-check-label" for="dependencia-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="dependencia-ver">
                            <label class="form-check-label" for="dependencia-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="dependencia-modificar">
                            <label class="form-check-label" for="dependencia-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="dependencia-eliminar">
                            <label class="form-check-label" for="dependencia-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="dependencia-reactivar">
                            <label class="form-check-label" for="dependencia-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Unidad -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-unidad"
                            data-modulo="UNIDA01120251001">
                          <label class="form-check-label" for="group-unidad">Gestionar Unidad</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="UNIDA01120251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="unidad-registrar">
                            <label class="form-check-label" for="unidad-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="unidad-ver">
                            <label class="form-check-label" for="unidad-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="unidad-modificar">
                            <label class="form-check-label" for="unidad-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="unidad-eliminar">
                            <label class="form-check-label" for="unidad-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="unidad-reactivar">
                            <label class="form-check-label" for="unidad-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Cargo -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-cargo"
                            data-modulo="CARGO01220251001">
                          <label class="form-check-label" for="group-cargo">Gestionar Cargo</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="CARGO01220251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="cargo-registrar">
                            <label class="form-check-label" for="cargo-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="cargo-ver">
                            <label class="form-check-label" for="cargo-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="cargo-modificar">
                            <label class="form-check-label" for="cargo-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="cargo-eliminar">
                            <label class="form-check-label" for="cargo-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="cargo-reactivar">
                            <label class="form-check-label" for="cargo-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Tipo de Servicio -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-tipo_servicio"
                            data-modulo="TIPOS01320251001">
                          <label class="form-check-label" for="group-tipo_servicio">Gestionar Tipo de Servicio</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="TIPOS01320251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="tipo_servicio-registrar">
                            <label class="form-check-label" for="tipo_servicio-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="tipo_servicio-ver">
                            <label class="form-check-label" for="tipo_servicio-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="tipo_servicio-modificar">
                            <label class="form-check-label" for="tipo_servicio-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="tipo_servicio-eliminar">
                            <label class="form-check-label" for="tipo_servicio-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="cargo-reactivar">
                            <label class="form-check-label" for="cargo-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Equipos y Bienes -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingEquipos">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseEquipos" aria-expanded="false" aria-controls="collapseEquipos">
                Equipos y Bienes
              </button>
            </h2>
            <div id="collapseEquipos" class="accordion-collapse collapse" aria-labelledby="headingEquipos"
              data-bs-parent="#accordionPermisos">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Bien -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-bien"
                            data-modulo="BIEN001420251001">
                          <label class="form-check-label" for="group-bien">Gestionar Bien</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="BIEN001420251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="bien-registrar">
                            <label class="form-check-label" for="bien-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="bien-ver">
                            <label class="form-check-label" for="bien-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="bien-modificar">
                            <label class="form-check-label" for="bien-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="bien-eliminar">
                            <label class="form-check-label" for="bien-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="bien-reactivar">
                            <label class="form-check-label" for="bien-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Categoría -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-categoria"
                            data-modulo="CATEG01520251001">
                          <label class="form-check-label" for="group-categoria">Gestionar Categoría</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="CATEG01520251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="categoria-registrar">
                            <label class="form-check-label" for="categoria-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="categoria-ver">
                            <label class="form-check-label" for="categoria-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="categoria-modificar">
                            <label class="form-check-label" for="categoria-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="categoria-eliminar">
                            <label class="form-check-label" for="categoria-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="categoria-reactivar">
                            <label class="form-check-label" for="categoria-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Marca -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-marca"
                            data-modulo="MARCA01620251001">
                          <label class="form-check-label" for="group-bien">Gestionar Marca</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="MARCA01620251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="marca-registrar">
                            <label class="form-check-label" for="marca-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="marca-ver">
                            <label class="form-check-label" for="marca-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="marca-modificar">
                            <label class="form-check-label" for="marca-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="marca-eliminar">
                            <label class="form-check-label" for="marca-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="marca-reactivar">
                            <label class="form-check-label" for="marca-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Equipo -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-equipo"
                            data-modulo="EQUIP01720251001">
                          <label class="form-check-label" for="group-bien">Gestionar Equipo</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="EQUIP01720251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="equipo-registrar">
                            <label class="form-check-label" for="equipo-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="equipo-ver">
                            <label class="form-check-label" for="equipo-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="equipo-modificar">
                            <label class="form-check-label" for="equipo-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="equipo-eliminar">
                            <label class="form-check-label" for="equipo-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="historial" id="equipo-historial">
                            <label class="form-check-label" for="equipo-historial">Ver Historial</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="equipo-reactivar">
                            <label class="form-check-label" for="equipo-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Material -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-material"
                            data-modulo="MATER02420251001">
                          <label class="form-check-label" for="group-material">Gestionar Material</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="MATER02420251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="material-registrar">
                            <label class="form-check-label" for="material-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="material-ver">
                            <label class="form-check-label" for="material-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="material-modificar">
                            <label class="form-check-label" for="material-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="material-eliminar">
                            <label class="form-check-label" for="material-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="historial" id="material-historial">
                            <label class="form-check-label" for="material-historial">Ver Historial</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="material-reactivar">
                            <label class="form-check-label" for="material-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Infraestructura -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingInfraestructura">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseInfraestructura" aria-expanded="false" aria-controls="collapseInfraestructura">
                Infraestructura
              </button>
            </h2>
            <div id="collapseInfraestructura" class="accordion-collapse collapse"
              aria-labelledby="headingInfraestructura" data-bs-parent="#accordionPermisos">
              <div class="accordion-body">
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Switch -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-switch"
                            data-modulo="SWITC01820251001">
                          <label class="form-check-label" for="group-switch">Gestionar Switch</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="SWITC01820251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="switch-registrar">
                            <label class="form-check-label" for="switch-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="switch-ver">
                            <label class="form-check-label" for="switch-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="switch-modificar">
                            <label class="form-check-label" for="switch-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="switch-eliminar">
                            <label class="form-check-label" for="switch-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="switch-reactivar">
                            <label class="form-check-label" for="switch-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Patch Panel -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-patch_panel"
                            data-modulo="PATCH01920251001">
                          <label class="form-check-label" for="group-patch_panel">Gestionar Patch Panel</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="PATCH01920251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="patch_panel-registrar">
                            <label class="form-check-label" for="patch_panel-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="patch_panel-ver">
                            <label class="form-check-label" for="patch_panel-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="patch_panel-modificar">
                            <label class="form-check-label" for="patch_panel-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="patch_panel-eliminar">
                            <label class="form-check-label" for="patch_panel-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="patch_panel-reactivar">
                            <label class="form-check-label" for="patch_panel-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Interconexión -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-interconexion"
                            data-modulo="INTER02020251001">
                          <label class="form-check-label" for="group-interconexion">Gestionar Interconexión</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="INTER02020251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="interconexion-registrar">
                            <label class="form-check-label" for="interconexion-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="interconexion-ver">
                            <label class="form-check-label" for="interconexion-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="interconexion-modificar">
                            <label class="form-check-label" for="interconexion-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="interconexion-eliminar">
                            <label class="form-check-label" for="interconexion-eliminar">Eliminar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Punto de Conexión -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-punto_conexion"
                            data-modulo="PUNTO02120251001">
                          <label class="form-check-label" for="group-punto_conexion">Gestionar Punto de Conexión</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="PUNTO02120251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="punto_conexion-registrar">
                            <label class="form-check-label" for="punto_conexion-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="punto_conexion-ver">
                            <label class="form-check-label" for="punto_conexion-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="punto_conexion-modificar">
                            <label class="form-check-label" for="punto_conexion-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="punto_conexion-eliminar">
                            <label class="form-check-label" for="punto_conexion-eliminar">Eliminar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <!-- Grupo Piso -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-piso"
                            data-modulo="PISO002220251001">
                          <label class="form-check-label" for="group-piso">Gestionar Piso</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="PISO002220251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="piso-registrar">
                            <label class="form-check-label" for="piso-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="piso-ver">
                            <label class="form-check-label" for="piso-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="piso-modificar">
                            <label class="form-check-label" for="piso-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="piso-eliminar">
                            <label class="form-check-label" for="piso-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="piso-reactivar">
                            <label class="form-check-label" for="piso-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <!-- Grupo Oficina -->
                    <fieldset class="permission-group">
                      <legend class="group-header">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input group-checkbox" type="checkbox" id="group-oficina"
                            data-modulo="OFICIN02320251001">
                          <label class="form-check-label" for="group-oficina">Gestionar Oficina</label>
                        </div>
                      </legend>
                      <div class="row permission-options" data-modulo-string="OFICIN02320251001">
                        <!-- Opciones de permisos -->
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="registrar" id="oficina-registrar">
                            <label class="form-check-label" for="oficina-registrar">Registrar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="ver" id="oficina-ver">
                            <label class="form-check-label" for="oficina-ver">Ver</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="modificar" id="oficina-modificar">
                            <label class="form-check-label" for="oficina-modificar">Modificar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="eliminar" id="oficina-eliminar">
                            <label class="form-check-label" for="oficina-eliminar">Eliminar</label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-check form-switch">
                            <input class="form-check-input permission-checkbox" data-id-permiso="" type="checkbox"
                              role="switch" value="reactivar" id="oficina-reactivar">
                            <label class="form-check-label" for="oficina-reactivar">Reactivar</label>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- Fin Accordion -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="enviar" name="" class="btn btn-primary"></button>
      </div>
    </div>
  </div>
</div>

<script>
  // Script para manejar la selección de grupos completos
  document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar/deseleccionar todos los permisos de un grupo
    document.querySelectorAll('.group-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        const modulo = this.getAttribute('data-modulo');
        const isChecked = this.checked;

        document.querySelectorAll(`.permission-options[data-modulo-string="${modulo}"] .permission-checkbox`).forEach(perm => {
          perm.checked = isChecked;
        });
      });
    });

    // Actualizar el checkbox del grupo cuando cambian los permisos individuales
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', function () {
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