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

    .cuerpo_mapa {
        margin: 10px;
        width: 100%;
    }

    .contenido {
        display: flex;
        flex-direction: row;
    }

    .col-md-3, .col-md-9 {
        padding: 0 15px;
    }

    @media (max-width: 768px) {
        .cuerpo_mapa {
            margin: 10px;
            width: 100%;
        }

        .contenido {
            flex-direction: column;
        }

        .col-md-3, .col-md-9 {
            width: 100%;
            padding: 0;
        }

        #mapa {
            height: 400px;
        }
    }
</style>

<div class="content cuerpo_mapa">
    <div class="container" style="margin: 10px;">
        <div class="contenido">
            <div class="col-md-3">
                <h3 class="portlet-title">
                    Agregar Dirección
                    <button class="btn btn-danger" onclick="colocarMarcadorUbicacionActual()">Usar ubicación actual</button>
                </h3>
                <form id="formularioDatos" method="post">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input id="nombre" name="nombre" class="form-control " type="text" placeholder="Nombre de la Bodega" required>
                            <br>
                            <input id="direccion" name="direccion" class="form-control " type="text" placeholder="Ingresa una dirección">
                            <br>
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
    </div>
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

        const formData = new FormData(this);

        const url = '<?php echo SERVERURL; ?>Productos/agregarBodega'; // Asegúrate de definir SERVERURL en tu backend PHP

        fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                // Mostrar alerta de éxito
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