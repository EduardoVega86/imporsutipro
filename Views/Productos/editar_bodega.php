<?php
require_once './Views/templates/header.php';
$bodega_id = isset($_GET['id']) ? $_GET['id'] : null;
?>
<?php require_once './Views/Productos/css/editar_bodega_style.php'; ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places"></script>


<div class="content cuerpo_mapa">
    <div class="container" style="margin: 10px;">

        <div class="contenido">
            <div class="col-md-3">
                <h3 class="portlet-title">
                    Editar Dirección
                    <!-- <button class="btn btn-danger" onclick="colocarMarcadorUbicacionActual()">Usar ubicación actual</button> -->

                </h3>
                <form id="formularioDatos_editar" method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo $bodega_id; ?>">

                    <div class="form-group row">
                        <div class="col-md-12">

                            <input id="nombre" name="nombre" class="form-control " type="text" placeholder="Nombre de la Bodega" required>
                            <br>
                            <!-- <input id="direccion" name="direccion" class="form-control " type="text" placeholder="Ingresa una dirección">
                            <br> -->


                            <div>
                                <span class="help-block">Provincia </span>
                                <select class="datos form-control" id="provincia" name="provincia" onchange="cargarCiudades()" required>
                                    <option value="">Provincia *</option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <span class="help-block">Ciudad </span>
                                <div id="div_ciudad">
                                    <select class="datos form-control" id="ciudad_entrega" name="ciudad_entrega" required disabled>
                                        <option value="">Ciudad *</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <input readonly id="direccion_completa" name="direccion_completa" class="form-control" type="text" placeholder="Ingresa una dirección">
                            <br>
                            <input id="calle_principal" name="calle_principal" class="form-control " type="text" placeholder="Ingrese calle principal">
                            <br>
                            <input id="calle_secundaria" name="calle_secundaria" class="form-control " type="text" placeholder="Ingrese calle secundaria">
                            <br>
                            <input id="numero_casa" name="numero_casa" class="form-control " type="text" placeholder="Numero de Casa">
                            <br>
                            <input id="nombre_contacto" name="nombre_contacto" class="form-control " type="text" placeholder="Ingrese Contacto">
                            <br>
                            <input id="telefono" name="telefono" class="form-control " type="text" placeholder="Telefono de contacto">
                            <br>
                            <input id="referencia" name="referencia" class="form-control " type="text" placeholder="Ingrese referencia">
                            <div class="input-group">

                                <?php
                                //echo '<h2>'. get_row('edificio', 'nombre', 'id_edificio', $id_edificio).'</h2>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">

                                <input readonly id="latitud" name="latitud" class="form-control" type="text" placeholder="Latitud">
                                <input readonly id="longitud" name="longitud" class="form-control" type="text" placeholder="Longitud">
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">


                            </div>
                        </div>



                    </div>
                    <input class="btn btn-primary" type="submit" value="Guardar">
                </form>
            </div>
            <!-- <div class="col-md-9">
                <div id="mapa" style="height: 100%;"></div>
                <div id="infoDireccion"></div>
            </div> -->
        </div>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places&callback=initMap"></script>
        <script>
            // Inicializar el mapa
            function initMap() {
                var map = new google.maps.Map(document.getElementById('mapa'), {
                    center: {
                        lat: 0,
                        lng: -78
                    },
                    zoom: 7
                });

                var geocoder = new google.maps.Geocoder();
                var infowindow = new google.maps.InfoWindow();

                // Autocompletado de direcciones
                var input = document.getElementById('direccion');
                //alert(input);
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);

                // Crear un marcador inicial
                var marker = new google.maps.Marker({
                    position: {
                        lat: 0,
                        lng: -78
                    },
                    map: map,
                    draggable: true // Hacer el marcador arrastrable
                });

                // Al seleccionar una dirección, centrar el mapa en esa ubicación y colocar el marcador
                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    if (!place.geometry) {
                        window.alert("No se encontraron detalles de la dirección: '" + place.name + "'");
                        return;
                    }

                    // Centrar el mapa en la ubicación seleccionada
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);

                    // Posicionar el marcador en la ubicación seleccionada
                    marker.setPosition(place.geometry.location);

                    // Actualizar campos del formulario con la información de la dirección seleccionada
                    infowindow.setContent('Dirección: ' + place.formatted_address);
                    infowindow.open(map, marker);
                    $("#latitud").val(place.geometry.location.lat());
                    $("#longitud").val(place.geometry.location.lng());
                    $("#direccion_completa").val(place.formatted_address);


                    // Obtener la dirección mediante geocodificación inversa
                    geocoder.geocode({
                        'location': place.geometry.location
                    }, function(results, status) {
                        if (status === 'OK') {
                            if (results[0]) {
                                infowindow.setContent('Dirección: ' + results[0].formatted_address);
                                infowindow.open(map, marker);

                            } else {
                                window.alert('No se encontraron resultados');
                            }
                        } else {
                            window.alert('Geocoder falló debido a: ' + status);
                        }
                    });

                });

                // Al mover el marcador, obtener la nueva dirección
                marker.addListener('dragend', function() {
                    var latlng = marker.getPosition();

                    geocoder.geocode({
                        'location': latlng
                    }, function(results, status) {
                        if (status === 'OK') {
                            if (results[0]) {
                                infowindow.setContent('Dirección: ' + results[0].formatted_address);
                                infowindow.open(map, marker);
                                var latitud = results[0].geometry.location.lat();
                                var longitud = results[0].geometry.location.lng();
                                alert(latlng)
                            } else {
                                window.alert('No se encontraron resultados');
                            }
                        } else {
                            window.alert('Geocoder falló debido a: ' + status);
                        }
                    });
                });
            }
        </script>



    </div>
    <!-- end container -->
</div>
<!-- end content -->



</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj-OWe4vKRnRiHQEx2ANZqxIGBT8z6Fo0&libraries=places&callback=initMap"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj-OWe4vKRnRiHQEx2ANZqxIGBT8z6Fo0&libraries=places&callback=initMap"></script>

<script>
    var map;
    var marker;
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow();

    function initMap() {
        geocoder = new google.maps.Geocoder();
        infowindow = new google.maps.InfoWindow();

        map = new google.maps.Map(document.getElementById('mapa'), {
            center: { lat: -0.1806532, lng: -78.4678382 },
            zoom: 15
        });

        marker = new google.maps.Marker({
            map: map,
            position: { lat: -0.1806532, lng: -78.4678382 },
            draggable: true,
            title: "Arrástrame para seleccionar una ubicación"
        });

        var input = document.getElementById('direccion');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("No se encontraron detalles de la dirección: '" + place.name + "'");
                return;
            }
            actualizarMapaYMarcador(place.geometry.location, place.formatted_address);
        });

        marker.addListener('dragend', function() {
            actualizarDireccionDesdeLatLng(marker.getPosition());
        });
    }

    function actualizarMapaYMarcador(location, address) {
        map.setCenter(location);
        marker.setPosition(location);
        infowindow.setContent(address);
        infowindow.open(map, marker);
        $("#latitud").val(location.lat());
        $("#longitud").val(location.lng());
        $("#direccion_completa").val(address);
    }

    function actualizarDireccionDesdeLatLng(latlng) {
        geocoder.geocode({ 'location': latlng }, function(results, status) {
            if (status === 'OK' && results[0]) {
                actualizarMapaYMarcador(latlng, results[0].formatted_address);
            } else {
                window.alert('Geocoder falló debido a: ' + status);
            }
        });
    }

    function colocarMarcadorUbicacionActual() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                actualizarMapaYMarcador(pos, 'Tu ubicación actual');
            }, function() {
                handleLocationError(true, map.getCenter());
            });
        } else {
            handleLocationError(false, map.getCenter());
        }
    }

    function handleLocationError(browserHasGeolocation, pos) {
        infowindow.setPosition(pos);
        infowindow.setContent(browserHasGeolocation ?
            'Error: El servicio de Geolocalización falló.' :
            'Error: Tu navegador no soporta geolocalización.');
        infowindow.open(map);
    }

    var bodegaId = <?php echo json_encode($bodega_id); ?>;

    $(document).ready(function() {
        // Inicializar Select2 en los selectores
        $('#provincia').select2({
            placeholder: 'Provincia *',
            allowClear: true
        });

        $('#ciudad_entrega').select2({
            placeholder: 'Ciudad *',
            allowClear: true
        });
        
        cargarProvincias();
        cargarDatosBodega();
    });

    function cargarProvincias() {
        $.ajax({
            url: '<?php echo SERVERURL; ?>Ubicaciones/obtenerProvincias',
            method: 'GET',
            success: function(response) {
                let provincias = JSON.parse(response);
                let provinciaSelect = $('#provincia');
                provinciaSelect.empty();
                provinciaSelect.append('<option value="">Provincia *</option>');

                provincias.forEach(function(provincia) {
                    provinciaSelect.append(`<option value="${provincia.codigo_provincia}">${provincia.provincia}</option>`);
                });
            },
            error: function(error) {
                console.log('Error al cargar provincias:', error);
            }
        });
    }

    function cargarCiudades() {
        let provinciaId = $('#provincia').val();
        if (provinciaId) {
            $.ajax({
                url: '<?php echo SERVERURL; ?>Ubicaciones/obtenerCiudades/' + provinciaId,
                method: 'GET',
                success: function(response) {
                    let ciudades = JSON.parse(response);
                    console.log('Ciudades recibidas:', ciudades); // Verificar los datos en la consola del navegador
                    let ciudadSelect = $('#ciudad_entrega');
                    ciudadSelect.empty();
                    ciudadSelect.append('<option value="">Ciudad *</option>');

                    ciudades.forEach(function(ciudad) {
                        ciudadSelect.append(`<option value="${ciudad.id_cotizacion}">${ciudad.ciudad}</option>`);
                    });

                    ciudadSelect.prop('disabled', false);
                },
                error: function(error) {
                    console.log('Error al cargar ciudades:', error);
                }
            });
        } else {
            $('#ciudad_entrega').empty().append('<option value="">Ciudad *</option>').prop('disabled', true);
        }
    }

    function cargarDatosBodega() {
        const url = '<?php echo SERVERURL; ?>Productos/obtenerBodega/' + bodegaId;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const bodega = data[0];
                    document.getElementById('nombre').value = bodega.nombre;
                    document.getElementById('direccion').value = bodega.direccion;
                    document.getElementById('provincia').value = bodega.provincia;
                    cargarCiudades(bodega.provincia, bodega.localidad);
                    document.getElementById('direccion_completa').value = bodega.direccion;
                    document.getElementById('nombre_contacto').value = bodega.responsable;
                    document.getElementById('telefono').value = bodega.contacto;
                    document.getElementById('numero_casa').value = bodega.num_casa;
                    document.getElementById('referencia').value = bodega.referencia;
                    document.getElementById('latitud').value = bodega.latitud;
                    document.getElementById('longitud').value = bodega.longitud;
                } else {
                    console.error('Error al cargar los datos de la bodega:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    document.getElementById("formularioDatos_editar").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        const url = '<?php echo SERVERURL; ?>Productos/editarBodega';

        fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status == 500) {
                    Swal.fire({
                        icon: 'error',
                        title: data.title,
                        text: data.message
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: data.title,
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = '<?php echo SERVERURL ?>Productos/bodegas';
                    });
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema con el agregar bodega.',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
    });
</script>

<?php require_once './Views/templates/footer.php'; ?>