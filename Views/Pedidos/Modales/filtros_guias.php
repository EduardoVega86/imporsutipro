 <div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalFiltrosLabel">
                     <i class="fas fa-sliders-h"></i> Filtros de búsqueda
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
             </div>
             <div class="modal-body">
                 <div class="primer_seccionFiltro">

                     <div class="d-flex flex-row align-items-end filtro_fecha">
                         <div class="flex-fill">
                             <h6>Seleccione el rango de fechas:</h6>
                             <div class="input-group">
                                 <input type="text" class="form-control" id="daterange">
                                 <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                             </div>
                         </div>
                     </div>
                     <div class="flex-fill filtro_impresar">
                         <div class=" d-flex flex-column justify-content-start">
                             <label for="inputPassword3" class="col-sm-2 col-form-label">Impresiones</label>
                             <div>
                                 <select name="impresion" class="form-control" id="impresion">
                                     <option value=""> Todas</option>
                                     <option value="1"> Impresas </option>
                                     <option value="0"> No impresas </option>
                                 </select>
                             </div>
                         </div>
                     </div>
                     <div class="flex-fill filtro_impresar">
                         <div class=" d-flex flex-column justify-content-start">
                             <label for="despachado" class="col-sm-2 col-form-label">Despachados</label>
                             <div>
                                 <select name="despachos" class="form-control" id="despachos">
                                     <option value=""> Todas</option>
                                     <option value="2"> Despachados </option>
                                     <option value="1"> No Despachados </option>
                                     <option value="3"> Devueltos </option>
                                     <option value="4"> No Devueltos</option>
                                 </select>
                             </div>
                         </div>
                     </div>
                     <div class="flex-fill filtro_tienda" style="width: 100%; padding-top: 8px; ">
                         <div style="width: 100%;">
                             <label for="tienda_q" class="col-form-label">Proveedor / Dropshipper</label>
                             <select id="tienda_q" class="form-control">
                                 <option value="">Selecciona un Proveedor o Dropshipper</option>
                                 <option value="1">Dropshipper</option>
                                 <option value="0">Local</option>
                             </select>
                         </div>
                     </div>
                 </div>
                 <hr>
                 <div class="segunda_seccionFiltro">
                     <div style="width: 100%;">
                         <label for="inputPassword3" class="col-sm-2 col-form-label">Estado</label>
                         <div>
                             <select name="estado_q" class="form-control" id="estado_q">
                                 <option value="">Seleccione Estado</option>
                                 <option value="generada">Generada/ Por Recolectar</option>
                                 <option value="en_transito">En transito / Procesamiento / En ruta</option>
                                 <option value="zona_entrega">Zona de entrega</option>
                                 <option value="entregada">Entregadas</option>
                                 <option value="novedad">Novedad</option>
                                 <option value="devolucion">Devolución</option>
                             </select>
                         </div>
                     </div>

                     <div style="width: 100%;">
                         <label for="inputPassword3" class="col-sm-2 col-form-label">Transportadora</label>
                         <div>
                             <select name="transporte" id="transporte" class="form-control">
                                 <option value=""> Seleccione Transportadora</option>
                                 <option value="LAAR">Laar</option>
                                 <option value="SPEED">Speed</option>
                                 <option value="SERVIENTREGA">Servientrega</option>
                                 <option value="GINTRACOM">Gintracom</option>
                             </select>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" id="btnAplicarFiltros" class="btn btn-primary">
                     <i class="fas fa-filter"></i> Aplicar Filtros
                 </button>
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
             </div>
         </div>
     </div>
 </div>