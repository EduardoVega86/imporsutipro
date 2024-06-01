<?php require_once './Views/templates/header.php'; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places"></script>

<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow-y: auto;
    }
</style>
<div class="content" style="margin: 10px;">
    <div class="container">
        <h3 class="portlet-title">
            Agregar Dirección
            <button class="btn btn-danger" onclick="colocarMarcadorUbicacionActual()">Usar ubicación actual</button>

        </h3>
        <div class="row">
            <div class="col-md-3">
                <form id="formularioDatos" method="post" action="../ajax/guardar_bodega.php">

                    <div class="form-group row">
                        <div class="col-md-12">

                            <input id="nombre" name="nombre" class="form-control " type="text" placeholder="Nombre de la Bodega" required>
                            <br>
                            <input id="direccion" name="direccion" class="form-control " type="text" placeholder="Ingresa una dirección">
                            <br>


                            <div>
                                <span class="help-block">Provincia </span>
                                <select class="datos form-control " onchange="cargar_provincia_pedido()" id="provinica" name="provinica" required>
                                    <option value="">Provincia *</option>

                                </select>
                            </div>
                            <br>
                            <div>
                                <span class="help-block">Ciudad </span>
                                <div id="div_ciudad" onclick="verify()">
                                    <select class="datos form-control" id="ciudad_entrega" name="ciudad_entrega" onchange="seleccionarProvincia()" required disabled>
                                        <option value="">Ciudad *</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <br>
                            <input readonly id="direccion_completa" name="direccion_completa" class="form-control" type="text" placeholder="Ingresa una dirección">

                            <br>
                            <input id="nombre_contacto" name="nombre_contacto" class="form-control " type="text" placeholder="Ingrese Contacto">
                            <br>
                            <input id="telefono" name="telefono" class="form-control " type="text" placeholder="Telefono de contacto">
                            <br>
                            <input id="numero_casa" name="numero_casa" class="form-control " type="text" placeholder="Numero de Casa">
                            <br>
                            <input id="referencia" name="referencia" class="form-control " type="text" placeholder="Ingrese referencia">
                            <div class="input-group">

                                <?php
                                //echo '<h2>'. get_row('edificio', 'nombre', 'id_edificio', $id_edificio).'</h2>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">

                                <input readonly id="latitud" name="latitud" class="form-control" type="text" placeholder="Latitud">
                                <input readonly id="longitud" name="longitud" class="form-control" type="text" placeholder="Longitud">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">


                            </div>
                        </div>



                    </div>
                    <input class="btn btn-primary" type="submit" value="Guardar">
                </form>
            </div>
            <div class="col-md-9">
                <div id="mapa" style="height: 100%;"></div>
                <div id="infoDireccion"></div>
            </div>
        </div>
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
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places&callback=initMap"></script>


    </div>
    <!-- end container -->
</div>
<!-- end content -->



</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj-OWe4vKRnRiHQEx2ANZqxIGBT8z6Fo0&libraries=places&callback=initMap"></script>

<script>
    // Define variables globales para el mapa y el marcador
    var map;
    var marker;
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow();

    function initMap() {
        geocoder = new google.maps.Geocoder();
        infowindow = new google.maps.InfoWindow();

        // Inicializa el mapa
        map = new google.maps.Map(document.getElementById('mapa'), {
            center: {
                lat: -0.1806532,
                lng: -78.4678382
            }, // Coordenadas de ejemplo, puedes poner las que quieras
            zoom: 15
        });

        // Crea un marcador arrastrable en el mapa
        marker = new google.maps.Marker({
            map: map,
            position: {
                lat: -0.1806532,
                lng: -78.4678382
            }, // Coordenadas de ejemplo
            draggable: true,
            title: "Arrástrame para seleccionar una ubicación"
        });

        // Autocompletado de direcciones
        var input = document.getElementById('direccion');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        // Evento de autocompletado: actualiza el mapa y el marcador con la nueva ubicación
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("No se encontraron detalles de la dirección: '" + place.name + "'");
                return;
            }
            actualizarMapaYMarcador(place.geometry.location, place.formatted_address);
        });

        // Evento de arrastre del marcador: actualiza los campos del formulario con la nueva ubicación
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
        // Resto de la lógica para actualizar otros campos del formulario
    }

    function actualizarDireccionDesdeLatLng(latlng) {
        geocoder.geocode({
            'location': latlng
        }, function(results, status) {
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
                console.log(pos);
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

    // Asegúrate de reemplazar la clave de la API en la URL del script de Google Maps al final del archivo
</script>
<?php require_once './Views/templates/footer.php'; ?>