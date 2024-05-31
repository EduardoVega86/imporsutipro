<?php require_once './Views/templates/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="content-page" style="padding: 20px;">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                    <div class="col-lg-12">
                        <div class="portlet">
                            <div class="portlet-heading" style="background-color: #171931;">
                                <h3 class="portlet-title">
                                    Historial de pedidos
                                </h3>
                                <div class="portlet-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#bg-primary"><i class="ion-minus-round"></i></a>
                                    <span class="divider"></span>
                                    <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="bg-primary" class="panel-collapse collapse show" style="padding: 10px;">
                                <div class="d-flex flex-column justify-content-between">
                                    <div class="d-flex flex-row " style="width: 100%;">
                                        <div class="d-flex flex-row align-items-end" style="width: 34%;">
                                            <div class="flex-fill" style="margin: 0; padding-left: 0;">
                                                <h6>Seleccione fecha de inicio:</h6>
                                                <div class="input-group date" id="datepickerInicio">
                                                    <input type="text" class="form-control" name="fechaInicio">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-fill" style="padding-left: 15px; ">
                                                <h6>Seleccione fecha de fin:</h6>
                                                <div class="input-group date" id="datepickerFin">
                                                    <input type="text" class="form-control" name="fechaFin">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style=" padding-top: 10px;">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info waves-effect waves-light" onclick='load(1);'>
                                                        Buscar <span class="fa fa-search"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-fill" style=" padding-left: 10px; width:35%">
                                            <label for="tienda_q" class="col-form-label">Tienda</label>
                                            <select onchange="buscar(this.value)" id="tienda_q" class="form-control">
                                                <option value="0">Selecciona una Tienda</option>
                                                <?php
                                                $query_categoria = mysqli_query($conexion, "SELECT DISTINCT tienda FROM facturas_cot");
                                                while ($rw = mysqli_fetch_array($query_categoria)) {
                                                    echo '<option value="' . htmlspecialchars($rw['tienda']) . '">' . htmlspecialchars($rw['tienda']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="flex-fill">
                                            <div class=" d-flex flex-row justify-content-start">
                                                <input class="input-change" type="checkbox" role="switch" id="envioGratis_checkout">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Facturas Impresas</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row">
                                        <div class="d-flex flex-column" style="width: 100%;">
                                            <div class="d-flex flex-row justify-content-start">
                                                <div style="width: 100%;">
                                                    <label for="inputPassword3" class="col-sm-2 col-form-label" style="padding-left: 0;">Buscar</label>
                                                    <div>
                                                        <input type="text" class="form-control" id="q" placeholder="Nombre del cliente o # factura" onkeyup='load(1);'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="width: 100%;">


                                            </div>
                                        </div>
                                        <div style="width: 100%;">
                                            <label style="padding-left: 20px;" for="inputPassword3" class="col-sm-2 col-form-label">Transportadora</label>
                                            <div style="padding-left: 20px;">
                                                <select onchange="buscar_transporte(this.value)" name="transporte" id="transporte" class="form-control">
                                                    <option value="0"> Seleccione Transportadora</option>
                                                    <option value="LAAR">Laar</option>
                                                    <option value="IMPORFAST">Speed</option>
                                                    <option value="SERVIENTREGA">Servientrega</option>
                                                    <option value="GINTRACOM">Gintracom</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="padding-top: 20px;">


                                    <button class="btn btn-outline-danger" onclick="pdf(event)">Generar Impresiones</button>
                                </div>

                                <hr />
                                <div class="portlet-body">
                                    <?php
                                    include "../modal/eliminar_factura.php";
                                    include "../modal/cambiar_estado_guia.php";

                                    ?>

                                    <form class="form-horizontal" role="form" id="datos_cotizacion">
                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                <span id="loader"></span>
                                                <span id="modal_cot"></span>

                                            </div>

                                        </div>
                                    </form>
                                    <div class="datos_ajax_delete"></div><!-- Datos ajax Final -->

                                    <div class='outer_div'></div><!-- Carga los datos ajax -->
                                    <div class="col-md-4 input-group ">
                                        <label for="numero_q">Numero de facturas a ver: </label>
                                        <select onchange="buscar_numero(this.value)" name="numero_q" class="form-control" id="numero_q">
                                            <option value="10"> 10 </option>
                                            <option value="20"> 20 </option>
                                            <option value="50"> 50 </option>
                                            <option value="100"> 100 </option>

                                        </select>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- end container -->
        </div>
        <!-- end content -->

        <?php require 'includes/pie.php'; ?>

    </div>
</body>
</html>
<?php require_once './Views/templates/footer.php'; ?>