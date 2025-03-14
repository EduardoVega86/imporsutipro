<?php require_once './Views/templates/header.php'; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places"></script>
<?php require_once './Views/Productos/css/agregar_bodegas_style.php'; ?>


<div class="content cuerpo_mapa">
    <div class="container" style="margin: 10px;">

        <div class="contenido contenido_mapa">
            <div class="col-md-3">
                <h3 class="portlet-title">
                    Agregar Dirección
                    <!-- <button class="btn btn-danger" onclick="colocarMarcadorUbicacionActual()">Usar ubicación actual</button> -->

                </h3>
                <form id="formularioDatos" method="post">

                    <div class="form-group row">
                        <div class="col-md-12">

                            <label for="latitud">Nombre de la Bodega:</label>
                            <input id="nombre" name="nombre" class="form-control " type="text" placeholder="Nombre de la Bodega" required>
                            <br>
                            <!-- <input id="direccion" name="direccion" class="form-control " type="text" placeholder="Ingresa una dirección">
                            <br> -->


                            <div>
                                <span class="help-block">Provincia: </span>
                                <select class="datos form-control" id="provincia" name="provincia" onchange="cargarCiudades()" required>
                                    <option value="">Provincia *</option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <span class="help-block">Ciudad: </span>
                                <div id="div_ciudad">
                                    <select class="datos form-control" id="ciudad_entrega" name="ciudad_entrega" required disabled>
                                        <option value="">Ciudad *</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <label for="direccion_completa">Dirección:</label>
                            <input id="e" name="direccion_completa" class="form-control" type="text" placeholder="Direccion referencia" required>
                            <br>
                            <label for="numero_casa">Numero de Casa:</label>
                            <input id="numero_casa" name="numero_casa" class="form-control " type="text" placeholder="Numero de Casa">
                            <br>
                            <label for="nombre_contacto">Ingrese Contacto:</label>
                            <input id="nombre_contacto" name="nombre_contacto" class="form-control " type="text" placeholder="Ingrese Contacto">
                            <br>
                            <label for="telefono">Telefono de contacto:</label>
                            <input id="telefono" name="telefono" class="form-control " type="text" placeholder="Telefono de contacto">
                            <br>
                            <label for="referencia">Referencia:</label>
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

                                <input readonly id="latitud" name="latitud" class="form-control" type="hidden" placeholder="Latitud" value="0"> <!-- temporalmente value 0 -->
                                <input readonly id="longitud" name="longitud" class="form-control" type="hidden" placeholder="Longitud" value="0"> <!-- temporalmente value 0 -->
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
                    document.getElementById('latitud').value = place.geometry.location.lat();
                    document.getElementById('longitud').value = place.geometry.location.lng();
                    document.getElementById('direccion_completa').value = place.formatted_address;

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
                                document.getElementById('latitud').value = latitud;
                                document.getElementById('longitud').value = longitud;
                                document.getElementById('direccion_completa').value = results[0].formatted_address;
                            } else {
                                window.alert('No se encontraron resultados');
                            }
                        } else {
                            window.alert('Geocoder falló debido a: ' + status);
                        }
                    });
                });
            }

            // Asegúrate de reemplazar 'YOUR_API_KEY' con tu clave API correcta.
        </script>



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

        cargarProvincias(); // Llamar a cargarProvincias cuando la página esté lista
    });

    // Función para cargar provincias
    function cargarProvincias() {
        $.ajax({
            url: '' + SERVERURL + 'Ubicaciones/obtenerProvincias', // Reemplaza con la ruta correcta a tu controlador
            method: 'GET',
            success: function(response) {
                let provincias = JSON.parse(response);
                let provinciaSelect = $('#provincia');
                provinciaSelect.empty();
                provinciaSelect.append('<option value="">Provincia *</option>'); // Añadir opción por defecto

                provincias.forEach(function(provincia) {
                    provinciaSelect.append(`<option value="${provincia.codigo_provincia}">${provincia.provincia}</option>`);
                });
            },
            error: function(error) {
                console.log('Error al cargar provincias:', error);
            }
        });
    }

    // Función para cargar ciudades según la provincia seleccionada
    function cargarCiudades() {
        let provinciaId = $('#provincia').val();
        if (provinciaId) {
            $.ajax({
                url: SERVERURL + 'Ubicaciones/obtenerCiudades/' + provinciaId, // Reemplaza con la ruta correcta a tu controlador
                method: 'GET',
                success: function(response) {
                    let ciudades = JSON.parse(response);
                    console.log('Ciudades recibidas:', ciudades); // Verificar los datos en la consola del navegador
                    let ciudadSelect = $('#ciudad_entrega');
                    ciudadSelect.empty();
                    ciudadSelect.append('<option value="">Ciudad *</option>'); // Añadir opción por defecto

                    ciudades.forEach(function(ciudad) {
                        ciudadSelect.append(`<option value="${ciudad.id_cotizacion}">${ciudad.ciudad}</option>`);
                    });

                    ciudadSelect.prop('disabled', false); // Habilitar el select de ciudades
                },
                error: function(error) {
                    console.log('Error al cargar ciudades:', error);
                }
            });
        } else {
            $('#ciudad_entrega').empty().append('<option value="">Ciudad *</option>').prop('disabled', true);
        }
    }

    document.getElementById("formularioDatos").addEventListener("submit", function(event) {
        event.preventDefault();

        const data = JSON.stringify(Object.fromEntries(new FormData(event.target)));

        const url = '<?php echo SERVERURL; ?>Productos/agregarBodega'; // Asegúrate de definir SERVERURL en tu backend PHP

        fetch(url, {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                // Mostrar alerta de éxito
                if (data.status === 500) {
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
                console.error('Error:', error);
                // Mostrar alerta de error
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