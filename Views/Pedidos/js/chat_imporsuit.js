/* llenar seccion numeros */
$(document).ready(function () {
  let lastMessageId = null; // Variable global para almacenar el ID del último mensaje mostrado

  // Llamada AJAX para obtener los datos de la API de contactos
  $.ajax({
    url: SERVERURL + "Pedidos/numeros_clientes",
    method: "GET",
    dataType: "json",
    success: function (data) {
      let innerHTML = "";

      // Recorremos cada contacto
      $.each(data, function (index, contacto) {
        let color_etiqueta = "";

        if (contacto.color_etiqueta) {
          color_etiqueta = `<i class="fa-solid fa-tag" style="color: ${contacto.color_etiqueta} !important;"></i>`;
        }

        innerHTML += `
            <li class="list-group-item contact-item d-flex align-items-center" data-id="${
              contacto.id_cliente
            }">
                <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                <div class="d-flex flex-column">
                    <h6 class="mb-0">${
                      contacto.nombre_cliente || "Desconocido"
                    } ${contacto.apellido_cliente || ""} ${color_etiqueta}</h6>
                    <h7>+${contacto.celular_cliente}</h7>
                    <small class="text-muted">${
                      contacto.texto_mensaje || "No hay mensajes"
                    }</small>
                </div>
            </li>`;
      });

      // Inyectamos el HTML generado en la lista de contactos
      $("#contact-list").html(innerHTML);

      // Añadimos el evento de click a cada contacto
      $("#contact-list").on("click", ".contact-item", function () {
        let id_cliente = $(this).data("id");
        // Llamamos a la función para ejecutar la API con el id_cliente
        ejecutarApiConIdCliente(id_cliente);

        // Iniciar el polling para actualizar los mensajes automáticamente
        startPollingMensajes(id_cliente);
      });
    },
    error: function (error) {
      console.error("Error al obtener los mensajes:", error);
    },
  });

  // Función que se ejecuta cuando se hace click en un contacto
  function ejecutarApiConIdCliente(id_cliente) {
    let formData = new FormData();
    formData.append("id_cliente", id_cliente);

    // Aquí puedes hacer una nueva llamada AJAX con el id_cliente
    $.ajax({
      url: SERVERURL + "Pedidos/numero_cliente",
      method: "POST",
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      dataType: "json",
      success: function (response) {
        $("#nombre_chat").text(
          response[0].nombre_cliente + " " + response[0].apellido_cliente
        );
        $("#telefono_chat").text(
          "+"+response[0].celular_cliente
        );

        $("#id_cliente_chat").val(response[0].id);
        $("#celular_chat").val(response[0].celular_cliente);
        $("#uid_cliente").val(response[0].uid_cliente);
        $("#id_etiqueta_select").val(response[0].id_etiqueta);

        /* seccion informacion */
        $("#telefono_info").text(formato_telefono(response[0].celular_cliente));
        $("#nombre_info").text(
          "Nombre: " +
            response[0].nombre_cliente +
            " " +
            response[0].apellido_clientes
        );
        $("#correo_info").text("Email: " + response[0].email_cliente);

        let formData = new FormData();
        formData.append("telefono", response[0].celular_cliente);
        $.ajax({
          url: SERVERURL + "pedidos/obtenerPedidosPorTelefono",
          type: "POST",
          data: formData,
          processData: false, // No procesar los datos
          contentType: false, // No establecer ningún tipo de contenido
          dataType: "json",
          success: function (response_historial) {
            let tableBody = "";

            // Recorremos cada pedido del historial
            $.each(response_historial, function (index, historial) {
              tableBody += `
                      <tr>
                          <td>${historial.numero_factura}</td>
                          <td>${historial.nombre}</td>
                          <td>
                              <button class="btn btn-primary btn-sm" onclick="verDetallesPedido(${historial.id_factura})">
                                  Ver detalles
                              </button>
                          </td>
                      </tr>
                  `;
            });

            // Inyectamos el HTML generado en el cuerpo de la tabla
            $("#historialPedidosBody").html(tableBody);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert("Error al obtener el historial: " + errorThrown);
          },
        });

        /* Fin seccion informacion */

        // Llamar a la función para cargar los mensajes iniciales del chat
        cargarMensajesChat(id_cliente);
      },
      error: function (error) {
        console.error("Error al ejecutar la API:", error);
      },
    });
  }

  function formato_telefono(telefono) {
    if (telefono.includes("+")) {
      return telefono;
    } else {
      telefono = "+" + telefono;
      return telefono;
    }
  }

  // Función para cargar los mensajes del chat
  function cargarMensajesChat(id_cliente) {
    let formData_chat = new FormData();
    formData_chat.append("id_cliente", id_cliente);

    $.ajax({
      url: SERVERURL + "Pedidos/mensajes_clientes",
      method: "POST",
      data: formData_chat,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      dataType: "json",
      success: function (response2) {
        console.log("Respuesta de la API:", response2);

        // Llamamos a la función para llenar los mensajes
        llenarMensajesChat(response2);

        const chatMessages = document.querySelector(".chat-messages");
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Guardamos el ID del último mensaje
        if (response2.length > 0) {
          lastMessageId = response2[response2.length - 1].id;
        }
      },
      error: function (error) {
        console.error("Error al ejecutar la API:", error);
      },
    });
  }

  // Función para llenar los mensajes del chat
  function llenarMensajesChat(mensajes) {
    let innerHTML = "";

    // Recorremos los mensajes y creamos el HTML correspondiente
    $.each(mensajes, function (index, mensaje) {
      let claseMensaje = mensaje.rol_mensaje == 1 ? "sent" : "received";

      if (mensaje.tipo_mensaje == "text") {
        if (mensaje.id_automatizador) {
          let rutaArchivo = JSON.parse(mensaje.ruta_archivo);
          let textoMensaje = mensaje.texto_mensaje;

          // Reemplazar placeholders en el mensaje
          for (let key in rutaArchivo) {
            if (rutaArchivo.hasOwnProperty(key)) {
              // Crear la expresión regular para encontrar el placeholder (por ejemplo, {{nombre}})
              let placeholder = new RegExp(`{{${key}}}`, "g"); // 'g' para reemplazar todas las ocurrencias
              // Reemplazar el placeholder con el valor correspondiente de rutaArchivo
              textoMensaje = textoMensaje.replace(
                placeholder,
                rutaArchivo[key]
              );
            }
          }

          innerHTML += `
            <div class="message ${claseMensaje}">
              <span style = "white-space: pre-wrap;">${textoMensaje}</span >
            </div>
            `;
        } else {
          innerHTML += `
            <div class="message ${claseMensaje}">
              <span style = "white-space: pre-wrap;">${mensaje.texto_mensaje}</span>
            </div>
            `;
        }
      } else if (mensaje.tipo_mensaje == "image") {
        innerHTML += `
            <div class="message d-flex flex-column ${claseMensaje}">
                <img src="${SERVERURL}${mensaje.ruta_archivo}" class="image-mensaje">
                <span style = "white-space: pre-wrap;">${mensaje.texto_mensaje}</span>
            </div>
            `;
      } else if (mensaje.tipo_mensaje == "audio") {
        innerHTML += `
            <div class="message d-flex flex-column ${claseMensaje}">
                <audio controls class="audio-player">
                    <source src="${SERVERURL}${mensaje.ruta_archivo}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <div class="audio-time" id="audio-time-${index}">00:00</div>
            </div>
            `;
      } else if (mensaje.tipo_mensaje == "document") {
        let info_archivo = JSON.parse(mensaje.ruta_archivo);

        let nombre_archivo = info_archivo.nombre || "Sin nombre";
        let tamano_archivo = formatFileSize(info_archivo.size);
        let ruta_archivo = info_archivo.ruta || "";

        innerHTML += `
        <div class="message d-flex flex-column ${claseMensaje}">
            <div class="document-container">
                <div class="document-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="document-info">
                    <div class="document-name">${nombre_archivo}</div>
                    <div class="document-details">
                        <span>${tamano_archivo}</span>
                    </div>
                </div>
                <a href="${SERVERURL}${ruta_archivo}" class="document-download" download>
                    <i class="fas fa-download"></i>
                </a>
            </div>
            <div class="document-text">${mensaje.texto_mensaje}</div>
        </div>
        `;
      } else if (mensaje.tipo_mensaje == "video") {
        innerHTML += `
          <div class="message d-flex flex-column ${claseMensaje}">
              <div class="video-placeholder">
                  <button class="btn btn-primary load-video-btn" data-id-mensaje="${mensaje.id}">
                      Descargar video
                  </button>
              </div>
              <div class="video-text">${mensaje.texto_mensaje}</div>
          </div>
        `;
      } else if (mensaje.tipo_mensaje == "button") {
        innerHTML += `
            <div class="message ${claseMensaje}">
              <span style = "white-space: pre-wrap;">${mensaje.texto_mensaje}</span>
            </div>
            `;
      }
    });

    function formatFileSize(sizeInBytes) {
      if (sizeInBytes >= 1048576) {
        return (sizeInBytes / 1048576).toFixed(1) + " MB";
      } else {
        return Math.round(sizeInBytes / 1024) + " KB";
      }
    }

    // Inyectamos los mensajes en el contenedor de mensajes
    $(".chat-messages").html(innerHTML);

    // Agregar eventos para los botones de cargar video
    $(".load-video-btn").on("click", function () {
      const button = $(this);
      const idMensaje = button.data("id-mensaje");

      let formData = new FormData();
      formData.append("id_mensaje", idMensaje);

      $.ajax({
        url: `${SERVERURL}/Pedidos/obtener_url_video_mensaje`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
          const videoHTML = `
            <video controls class="video-player video_style_mensaje">
                <source src="${SERVERURL}${response[0].ruta_archivo}" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
          `;
          button.parent().html(videoHTML);
        },
        error: function () {
          alert("Error al cargar el video.");
        },
      });
    });

    // Agregar eventos para actualizar el tiempo del audio
    $(".audio-player").each(function (index) {
      const audio = this;
      const audioTime = document.getElementById(`audio-time-${index}`);

      audio.addEventListener("timeupdate", function () {
        let minutes = Math.floor(audio.currentTime / 60);
        let seconds = Math.floor(audio.currentTime % 60);
        if (seconds < 10) seconds = "0" + seconds;
        audioTime.textContent = minutes + ":" + seconds;
      });
    });
  }

  // Función para iniciar el polling de mensajes
  let pollingInterval;

  function startPollingMensajes(id_cliente) {
    // Si ya hay un intervalo de polling, lo limpiamos para evitar duplicados
    if (pollingInterval) {
      clearInterval(pollingInterval);
    }

    // Definimos el intervalo de polling (cada 5 segundos)
    pollingInterval = setInterval(function () {
      cargarUltimosMensajes(id_cliente);
    }, 5000);
  }

  // Función para cargar solo los mensajes nuevos
  function cargarUltimosMensajes(id_cliente) {
    let formData_chat = new FormData();
    formData_chat.append("id_cliente", id_cliente);

    // Pasamos el ID del último mensaje cargado
    if (lastMessageId) {
      formData_chat.append("ultimo_mensaje_id", lastMessageId);
    }

    $.ajax({
      url: SERVERURL + "Pedidos/ultimo_mensaje_cliente",
      method: "POST",
      data: formData_chat,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response && response.length > 0) {
          // Verificamos que el último mensaje no sea el mismo que ya se ha mostrado
          console.log("lastMessageId: " + lastMessageId);
          console.log("response[0].id_mensaje: " + response[0].id);

          if (response[0].id_mensaje != lastMessageId) {
            llenarMensajesChatIncremental(response);
            lastMessageId = response[response.length - 1].id; // Actualizamos el ID del último mensaje
          }
        }
      },
      error: function (error) {
        console.error("Error al cargar los mensajes nuevos:", error);
      },
    });
  }

  // Función para agregar mensajes nuevos de forma incremental
  function llenarMensajesChatIncremental(mensajes) {
    let innerHTML = "";

    $.each(mensajes, function (index, mensaje) {
      let claseMensaje = mensaje.rol_mensaje == 1 ? "sent" : "received";

      if (mensaje.tipo_mensaje == "text") {
        if (mensaje.id_automatizador) {
          let rutaArchivo = JSON.parse(mensaje.ruta_archivo);
          let textoMensaje = mensaje.texto_mensaje;

          // Reemplazar placeholders en el mensaje
          for (let key in rutaArchivo) {
            if (rutaArchivo.hasOwnProperty(key)) {
              // Crear la expresión regular para encontrar el placeholder (por ejemplo, {{nombre}})
              let placeholder = new RegExp(`{{${key}}}`, "g"); // 'g' para reemplazar todas las ocurrencias
              // Reemplazar el placeholder con el valor correspondiente de rutaArchivo
              textoMensaje = textoMensaje.replace(
                placeholder,
                rutaArchivo[key]
              );
            }
          }

          innerHTML += `
            <div class="message ${claseMensaje}">
              <span style = "white-space: pre-wrap;">${textoMensaje}</span>
            </div>
            `;
        } else {
          innerHTML += `
            <div class="message ${claseMensaje}">
              <span style = "white-space: pre-wrap;">${mensaje.texto_mensaje}</span>
            </div>
            `;
        }
      } else if (mensaje.tipo_mensaje == "image") {
        innerHTML += `
            <div class="message d-flex flex-column ${claseMensaje}">
                <img src="${SERVERURL}${mensaje.ruta_archivo}" class="image-mensaje">
                <span style = "white-space: pre-wrap;">${mensaje.texto_mensaje}</span>
            </div>
            `;
      } else if (mensaje.tipo_mensaje == "audio") {
        innerHTML += `
            <div class="message d-flex flex-column ${claseMensaje}">
                <audio controls class="audio-player">
                    <source src="${SERVERURL}${mensaje.ruta_archivo}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <div class="audio-time" id="audio-time-${index}">00:00</div>
            </div>
            `;
      } else if (mensaje.tipo_mensaje == "document") {
        let info_archivo = JSON.parse(mensaje.ruta_archivo);

        let nombre_archivo = info_archivo.nombre || "Sin nombre";
        let tamano_archivo = formatFileSize(info_archivo.size);
        let ruta_archivo = info_archivo.ruta || "";

        innerHTML += `
        <div class="message d-flex flex-column ${claseMensaje}">
            <div class="document-container">
                <div class="document-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="document-info">
                    <div class="document-name">${nombre_archivo}</div>
                    <div class="document-details">
                        <span>${tamano_archivo}</span>
                    </div>
                </div>
                <a href="${SERVERURL}${ruta_archivo}" class="document-download" download>
                    <i class="fas fa-download"></i>
                </a>
            </div>
            <div class="document-text">${mensaje.texto_mensaje}</div>
        </div>
        `;
      } else if (mensaje.tipo_mensaje == "video") {
        innerHTML += `
          <div class="message d-flex flex-column ${claseMensaje}">
              <div class="video-placeholder">
                  <button class="btn btn-primary load-video-btn" data-id-mensaje="${mensaje.id}">
                      Descargar video
                  </button>
              </div>
              <div class="video-text">${mensaje.texto_mensaje}</div>
          </div>
        `;
      } else if (mensaje.tipo_mensaje == "button") {
        innerHTML += `
            <div class="message ${claseMensaje}">
              <span style = "white-space: pre-wrap;">${mensaje.texto_mensaje}</span>
            </div>
            `;
      }
    });

    // Añadir los mensajes nuevos al final de la lista
    $(".chat-messages").append(innerHTML);

    const chatMessages = document.querySelector(".chat-messages");
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }
});

/* fin llenar seccion numeros */

const chatInfo = document.querySelector(".chat-info");
const chatContent = document.querySelector(".chat-content");
const btnThreeDots = document.getElementById("btn-three-dots");
const btnCloseInfo = document.getElementById("btn-close-info"); // Referencia al botón de cerrar (X)

const infoSection = document.querySelector(".info-section");
const toolsSection = document.querySelector(".tools-section");
const btnInfo = document.getElementById("btn-info");
const btnTools = document.getElementById("btn-tools");

// Función para alternar la visibilidad de la sección de información del contacto (botón de tres puntos)
btnThreeDots.addEventListener("click", () => {
  if (chatInfo.classList.contains("hidden")) {
    chatInfo.classList.remove("hidden");
    chatContent.classList.remove("full-width");
  } else {
    chatInfo.classList.add("hidden");
    chatContent.classList.add("full-width");
  }
});

// Función para cerrar la sección de información con la "X"
btnCloseInfo.addEventListener("click", () => {
  chatInfo.classList.add("hidden");
  chatContent.classList.add("full-width");
});

// Alternar la visibilidad de la sección de información (botón flotante de información)
btnInfo.addEventListener("click", () => {
  if (infoSection.style.display === "none" || !infoSection.style.display) {
    infoSection.style.display = "block";
    toolsSection.style.display = "none"; // Oculta la sección de herramientas si está visible
  } else {
    infoSection.style.display = "none";
  }
});

// Alternar la visibilidad de la sección de herramientas (botón flotante de herramientas)
btnTools.addEventListener("click", () => {
  if (toolsSection.style.display === "none" || !toolsSection.style.display) {
    toolsSection.style.display = "block";
    infoSection.style.display = "none"; // Oculta la sección de información si está visible
  } else {
    toolsSection.style.display = "none";
  }
});

/* emojis */
// Elementos del DOM
const emojiButton = document.getElementById("emoji-button");
const emojiSection = document.getElementById("emoji-section");
const messageInput = document.getElementById("message-input");
const emojiSearch = document.getElementById("emoji-search"); // Elemento de búsqueda
let allEmojis = []; // Variable para almacenar todos los emojis
let displayedEmojis = 100; // Inicialmente mostramos 100 emojis
let totalLoadedEmojis = 0; // Total de emojis cargados hasta ahora
let isLoading = false; // Para evitar múltiples cargas al mismo tiempo

// Mostrar/Ocultar la sección de emojis
emojiButton.addEventListener("click", () => {
  if (emojiSection.classList.contains("d-none")) {
    emojiSection.classList.remove("d-none");
  } else {
    emojiSection.classList.add("d-none");
  }
});

// Insertar el emoji seleccionado en el input de mensaje
function addEmojiToInput(emoji) {
  messageInput.value += emoji;

  // Simulamos el evento "input" para que se comporte como si se hubiera escrito en el campo
  messageInput.dispatchEvent(new Event("input"));
}

// Función para renderizar emojis en la sección
function renderEmojis(emojis, limit = displayedEmojis) {
  const emojiContainer = document.getElementById("emoji-list"); // Seleccionamos el contenedor de emojis

  // Limpiar el contenedor de emojis antes de renderizar nuevos (evitar duplicación)
  emojiContainer.innerHTML = "";

  // Renderizamos solo el número de emojis limitado o filtrado
  emojis.slice(0, limit).forEach((emoji) => {
    const span = document.createElement("span");
    span.classList.add("emoji");
    span.textContent = emoji.character;
    span.addEventListener("click", () => addEmojiToInput(emoji.character));
    emojiContainer.appendChild(span);
  });
}

// Filtrar emojis según el texto en el campo de búsqueda
function filterEmojis() {
  const searchTerm = emojiSearch.value.toLowerCase();
  const filteredEmojis = allEmojis.filter(
    (emoji) =>
      emoji.unicodeName && emoji.unicodeName.toLowerCase().includes(searchTerm)
  );
  renderEmojis(filteredEmojis, filteredEmojis.length); // Mostramos todos los filtrados
}

// Agregar evento de búsqueda
emojiSearch.addEventListener("input", filterEmojis);

// Carga asíncrona de más emojis al hacer scroll
emojiSection.addEventListener("scroll", () => {
  if (
    emojiSection.scrollTop + emojiSection.clientHeight >=
    emojiSection.scrollHeight
  ) {
    loadMoreEmojis(); // Cargar más emojis cuando se llegue al fondo del scroll
  }
});

// Función para cargar más emojis de manera asíncrona
function loadMoreEmojis() {
  if (isLoading) return; // Evitar múltiples cargas simultáneas
  isLoading = true;

  const batchSize = 100; // Lote de 100 emojis
  const url = `https://emoji-api.com/emojis?access_key=bbe48b2609417c3b0dc67a95b31e62d0acb27c5b&offset=${totalLoadedEmojis}&limit=${batchSize}`;

  fetch(url)
    .then((response) => response.json())
    .then((newEmojis) => {
      // Verificamos que no estamos añadiendo duplicados
      const uniqueNewEmojis = newEmojis.filter(
        (emoji) => !allEmojis.some((e) => e.slug === emoji.slug)
      );

      // Añadir solo los emojis nuevos a la lista total
      allEmojis = [...allEmojis, ...uniqueNewEmojis];
      totalLoadedEmojis += uniqueNewEmojis.length;
      displayedEmojis += uniqueNewEmojis.length; // Actualizamos el límite de emojis mostrados
      renderEmojis(allEmojis); // Renderizamos los emojis con los nuevos incluidos
      isLoading = false;
    })
    .catch((error) => {
      console.error("Error al cargar más emojis:", error);
      isLoading = false;
    });
}

/* Llenar sección emojis - Cargar primeros 100 emojis */
loadMoreEmojis(); // Iniciar la carga de los primeros emojis

// Función para detectar clic fuera del cuadro de emojis
document.addEventListener("click", function (event) {
  const isClickInside =
    emojiSection.contains(event.target) || emojiButton.contains(event.target);

  // Si el clic no fue dentro de la sección de emojis ni en el botón de emojis, cierra la sección
  if (!isClickInside) {
    emojiSection.classList.add("d-none");
  }
});

/* Fin emojis */

/* Enviar mensaje whatsapp */
document.addEventListener("DOMContentLoaded", function () {
  const recordButton = document.getElementById("record-button");
  const sendButton = document.getElementById("send-button");
  const audioControls = document.getElementById("audio-recording-controls");
  const sendAudioButton = document.getElementById("send-audio");
  const audioTimer = document.getElementById("audio-timer");
  const messageInput = document.getElementById("message-input");

  // WhatsApp API credentials
  const fromPhoneNumberId = "109565362009074";
  const accessToken =
    "EAAVZAG5oL9G4BOZCAZBOKr5MXUIoVZBi7txtqZCncfz3DWg8W7p8bVS0usQyl9i73IAAIZCzLB9vlhymWtRstgDGZCZBqCre6XTayQ0HWxIUjjNsUZC0x2k7TI3LOMXbLEC26CDyzn3NDeQHWBnjgzDYrcJbuqpoOjBpVnxaf1kYPsD8xdGcPpaUnAVfVJ26D980U";
  /* const phoneNumber = "+593981702066"; */
  const url = `https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`;

  // ---- Variables para la grabación de audio ----
  let mediaRecorder;
  let audioBlob = null;
  let isRecording = false;
  let timerInterval;
  let timeElapsed = 0;
  let stream;

  // ---- Función para actualizar el temporizador ----
  function updateTimer() {
    timeElapsed++;
    let minutes = Math.floor(timeElapsed / 60);
    let seconds = timeElapsed % 60;
    audioTimer.textContent = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
  }

  // ---- Función para iniciar la grabación ----
  function startRecording() {
    navigator.mediaDevices
      .getUserMedia({ audio: true })
      .then((micStream) => {
        console.log("Acceso al micrófono concedido");
        stream = micStream;

        // Verificar si el navegador soporta grabación en OGG
        const mimeType = MediaRecorder.isTypeSupported("audio/ogg")
          ? "audio/ogg"
          : "audio/webm";
        console.log("Formato seleccionado:", mimeType);

        mediaRecorder = new MediaRecorder(stream, { mimeType: mimeType });

        mediaRecorder.start();
        isRecording = true;
        audioBlob = null;
        console.log("Grabación iniciada en formato", mimeType);

        audioControls.style.display = "block";
        audioControls.classList.remove("d-none");

        recordButton
          .querySelector("i")
          .classList.replace("fa-microphone", "fa-stop");

        timeElapsed = 0;
        timerInterval = setInterval(updateTimer, 1000);

        mediaRecorder.addEventListener("dataavailable", (event) => {
          console.log("Datos de audio recibidos:", event.data);
          audioBlob = event.data;
        });
      })
      .catch((error) => {
        console.error("Error al acceder al micrófono:", error);
        alert("No se puede acceder al micrófono. Verifica los permisos.");
      });
  }

  // ---- Función para detener la grabación y el flujo del micrófono ----
  function stopRecording() {
    if (mediaRecorder && isRecording) {
      mediaRecorder.stop();
      stream.getTracks().forEach((track) => track.stop());
      clearInterval(timerInterval);
      isRecording = false;
      audioControls.classList.add("d-none");
      recordButton
        .querySelector("i")
        .classList.replace("fa-stop", "fa-microphone");
      console.log("Grabación detenida");
    }
  }

  // ---- Función para subir el archivo de audio ----
  function uploadAudio(audioBlob) {
    const formData = new FormData();
    formData.append("audio", audioBlob, "audio.ogg");

    return fetch(SERVERURL + "Pedidos/guardar_audio_Whatsapp", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === 200) {
          console.log("Audio subido:", data.data);
          return data.data;
        } else {
          console.error("Error al subir el audio:", data.message);
          throw new Error(data.message);
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
      });
  }

  async function sendAudioToWhatsApp(fileUrl) {
    console.log("Enviando archivo de audio a WhatsApp:", fileUrl); // Log para verificar la URL

    const fromPhoneNumberId = "109565362009074";
    const accessToken =
      "EAAVZAG5oL9G4BO3vZAhKcOTpfZAQJgNDzTNDArOp8VitYT8GUFqcYKIsZAO0pBkf0edoZC1DgfXICkIEP7xZCkPkj8nS1gfDqI4jNeEVDmseyba3l2os8EoYgf1Mdnl2MwaYhmrdfZBgUnItwT8nZBVvjinB7j8IAfZBx2LZA1WNZCqqsZBZC2cqDdObeiLqEsih9U3XOQwZDZD";
    const url = `https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`;

    const payload = {
      messaging_product: "whatsapp",
      to: "+593981702066",
      type: "audio",
      audio: {
        link: fileUrl,
      },
    };

    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          Authorization: `Bearer ${accessToken}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();
      if (result.error) {
        console.error("Error al enviar el audio a WhatsApp:", result.error);
      } else {
        console.log("Audio enviado a WhatsApp con éxito:", result); // Log para verificar la respuesta completa
      }
    } catch (error) {
      console.error("Error en la solicitud de WhatsApp:", error);
    }
  }

  // ---- Botón de enviar audio a WhatsApp ----
  sendAudioButton.addEventListener("click", async () => {
    stopRecording();

    setTimeout(async () => {
      if (audioBlob && audioBlob.size > 0) {
        console.log("Tamaño del audioBlob:", audioBlob.size);

        // 1. Primero subes el archivo a tu servidor
        let audioUrl = await uploadAudio(audioBlob);
        audioUrl = SERVERURL + audioUrl;

        if (audioUrl) {
          console.log(
            "El archivo de audio ha sido subido a tu servidor:",
            audioUrl
          );

          // 2. Luego subimos el archivo a WhatsApp usando la URL obtenida
          await sendAudioToWhatsApp(audioUrl);
        } else {
          console.error(
            "No se pudo obtener la URL del archivo de audio subido a tu servidor."
          );
        }
      } else {
        console.error("El archivo de audio está vacío o no se ha creado.");
        alert("No se ha grabado ningún audio.");
      }
    }, 500);
  });

  // ---- Función para alternar los botones ----
  function toggleButtons() {
    if (messageInput.value.trim() === "") {
      recordButton.style.display = "inline-block";
      sendButton.style.display = "none";
    } else {
      recordButton.style.display = "none";
      sendButton.style.display = "inline-block";
    }
  }

  // ---- Función para enviar mensajes de texto a WhatsApp ----
  function sendMessageToWhatsApp(message) {
    if (message.trim() === "") {
      alert("Por favor, escribe un mensaje.");
      return;
    }

    var phoneNumber = "+" + $("#celular_chat").val();

    /* template */
    /* const data = {
      messaging_product: "whatsapp",
      to: phoneNumber,
      type: "template",
      template: {
        name: "hello_world", // Plantilla que estás usando
        language: { code: "en_US" }, // Lenguaje de la plantilla
      },
    }; */

    /* fin template */

    const data = {
      messaging_product: "whatsapp",
      recipient_type: "individual",
      to: phoneNumber,
      type: "text",
      text: {
        preview_url: true,
        body: message, // Mensaje personalizado
      },
    };

    const headers = {
      Authorization: `Bearer ${accessToken}`,
      "Content-Type": "application/json",
    };

    fetch(url, {
      method: "POST",
      headers: headers,
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((responseData) => {
        if (responseData.error) {
          console.error("Error al enviar el mensaje:", responseData.error);
          alert(`Error: ${responseData.error.message}`);
        } else {
          /* alert("¡Mensaje enviado con éxito!"); */

          var id_cliente_chat = $("#id_cliente_chat").val();
          var uid_cliente = $("#uid_cliente").val();

          let formData = new FormData();
          formData.append("texto_mensaje", message);
          formData.append("tipo_mensaje", "text");
          formData.append("mid_mensaje", uid_cliente);
          formData.append("id_recibe", id_cliente_chat);

          $.ajax({
            url: SERVERURL + "pedidos/agregar_mensaje_enviado",
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            dataType: "json",
            success: function (response) {
              startPollingMensajes(id_cliente_chat);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
            },
          });

          messageInput.value = ""; // Limpiar el campo de entrada
          toggleButtons(); // Verificar si hay que mostrar el botón de audio
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
        alert("Ocurrió un error al enviar el mensaje.");
      });
  }

  // ---- Evento para cambiar los botones cuando se escribe en el input ----
  messageInput.addEventListener("input", toggleButtons);

  sendButton.addEventListener("click", (event) => {
    event.preventDefault();
    const message = messageInput.value.trim();
    if (message) {
      sendMessageToWhatsApp(message);
    } else {
      alert("Por favor, escribe un mensaje.");
    }
  });

  messageInput.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      event.preventDefault();
      const message = messageInput.value.trim();
      if (message) {
        sendMessageToWhatsApp(message);
      } else {
        alert("Por favor, escribe un mensaje.");
      }
    }
  });

  recordButton.addEventListener("click", () => {
    if (!isRecording) {
      startRecording();
    } else {
      stopRecording();
    }
  });

  toggleButtons();
});

function abrir_modal_etiquetas() {
  cargarEtiquetas_asignar();
  $("#asignar_etiquetaModal").modal("show");
}
/* Fin enviar mensaje de audio Whatsapp */

/* seccion de creacion de guais y historial pedidos */
let contiene = "";

// Initialize selectedValue
let selectedValue = "";

const calcularTarifas = async (ciudad, provincia, monto_factura, recaudo) => {
  const form = new FormData();
  form.append("ciudad", ciudad);
  form.append("provincia", provincia);
  form.append("monto_factura", monto_factura);
  form.append("recaudo", recaudo);
  try {
    const response = await fetch(
      "https://new.imporsuitpro.com/calculadora/obtenerTarifas",
      {
        method: "POST",
        body: form,
      }
    );
    const data = await response.json();
    console.log(data);
    $("#precio_transporte_servi").text(data.servientrega);
    $("#precio_transporte_laar").text(data.laar);
    $("#precio_transporte_speed").text(data.speed);
    $("#precio_transporte_gintracom").text(data.gintracom);
  } catch (error) {
    console.error("Error al calcular tarifas:", error);
  }
};

const calcularServi = async (
  ciudad_destino,
  provincia_destino,
  ciudad_origen,
  monto_factura
) => {
  const form = new FormData();
  form.append("ciudadD", ciudad_destino);
  form.append("provinciaD", provincia_destino);
  form.append("ciudadO", ciudad_origen);
  form.append("monto_factura", monto_factura);
  try {
    const response = await fetch(
      "https://new.imporsuitpro.com/calculadora/calcularServi",
      {
        method: "POST",
        body: form,
      }
    );
    const data = await response.json();
    console.log(data);
    document.getElementById("flete").value = data.flete;
    document.getElementById("seguro").value = data.seguro;
    document.getElementById("comision").value = data.comision;
    document.getElementById("otros").value = data.otros;
    document.getElementById("impuestos").value = data.impuestos;
  } catch (error) {
    console.error("Error al calcular tarifas:", error);
  }
};

// Toast configuration
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});

const actualizarContiene = () => {
  contiene = ""; // Resetear contiene
  const productos = document.querySelectorAll("#productosBody tr");
  productos.forEach((producto) => {
    const nombreProducto = producto.querySelector("td:nth-child(1)").innerText;
    const cantidadProducto = producto.querySelector(
      "td:nth-child(2) input"
    ).value;
    contiene += `${cantidadProducto} x ${nombreProducto} `;
  });
  fetch("https://new.imporsuitpro.com/pedidos/actualizarContiene", {
    method: "POST",
    body: new URLSearchParams({
      id_pedido: document.getElementById("id_pedido").value,
      contiene: contiene,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === 200) {
        console.log("Contiene actualizado correctamente");
      } else {
        console.error("Error al actualizar contiene");
      }
    });
};

document
  .getElementById("formTransportadora")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    if (selectedValue) {
      if (selectedValue == "transportadora1") {
        alert("Has seleccionado: " + selectedValue + " Servientrega");
      } else if (selectedValue == "transportadora2") {
        alert("Has seleccionado: " + selectedValue + " Laar");
      } else if (selectedValue == "transportadora3") {
        alert("Has seleccionado: " + selectedValue + " Speed");
      } else if (selectedValue == "transportadora4") {
        alert("Has seleccionado: " + selectedValue + " Gintracom");
      } else {
        alert("Por favor selecciona una transportadora.");
      }
    }
  });

const mostrarCargando = () => {
  document.getElementById("loadingIndicator").style.display = "flex";
};

const ocultarCargando = () => {
  document.getElementById("loadingIndicator").style.display = "none";
};
async function llenarProvincia() {
  try {
    const response = await fetch(
      "https://new.imporsuitpro.com/Ubicaciones/obtenerProvincias"
    );
    const data = await response.json();

    const selectProvincia = document.getElementById("frm_provincia");
    selectProvincia.innerHTML = ""; // Limpiar opciones previas
    data.forEach((provincia) => {
      const option = document.createElement("option");
      option.value = provincia.codigo_provincia;
      option.innerText = provincia.provincia;
      selectProvincia.appendChild(option);
    });

    if (selectProvincia.dataset.selectedValue) {
      selectProvincia.value = selectProvincia.dataset.selectedValue;
      await llenarCiudades(selectProvincia.value);
    }
  } catch (error) {
    console.error("Error al cargar provincias:", error);
  }
}

async function llenarCiudades(codigo_provincia) {
  try {
    const response = await fetch(
      "https://new.imporsuitpro.com/Ubicaciones/obtenerCiudades/" +
        codigo_provincia
    );
    const data = await response.json();

    const selectCiudad = document.getElementById("frm_ciudad");
    selectCiudad.innerHTML = ""; // Limpiar opciones previas

    // Generar las opciones del select de ciudad
    data.forEach((ciudad) => {
      const option = document.createElement("option");
      option.value = ciudad.id_cotizacion;
      option.innerText = ciudad.ciudad;
      selectCiudad.appendChild(option);
    });
  } catch (error) {
    console.error("Error al cargar ciudades:", error);
  }
}

async function llenarProductos(arregloProductos) {
  const productosBody2 = document.getElementById("productosBody");
  productosBody2.innerHTML = "";
  const ultimoProducto = arregloProductos[arregloProductos.length - 1];

  let total = 0;
  contiene = ""; // Reset 'contiene'
  arregloProductos.forEach((producto) => {
    contiene += `${producto.cantidad} x ${producto.nombre_producto} `;
    total += producto.precio_venta * producto.cantidad;
    const tr = document.createElement("tr");
    tr.classList.add("align-middle");
    tr.innerHTML = `
                    <td class="text-center">${producto.nombre_producto}</td>
                    <td class="text-center"> <input type="text" id="cantidad${
                      producto.id_detalle
                    }" value="${
      producto.cantidad
    }" class="form-control" onchange="cambio(${producto.id_detalle}, ${
      producto.id_factura
    })" required></td>
                    <td class="text-center"> <input type="text" id="precio${
                      producto.id_detalle
                    }" value="${
      producto.precio_venta
    }" class="form-control" onchange="cambio(${producto.id_detalle}, ${
      producto.id_factura
    })" required></td>
                    <td class="text-center" id="total_${
                      producto.id_detalle
                    }"> ${producto.precio_venta * producto.cantidad}</td>
                    <td class="text-center">
                        <button class="btn btn-danger" onclick="eliminarProducto(${
                          producto.id_detalle
                        })"><i class='bx bxs-trash'></i></button>
                    </td>
                `;
    productosBody2.appendChild(tr);
  });

  document.getElementById("totalPagar").innerText = total;

  await productosAdicionales(ultimoProducto.id_producto, ultimoProducto.sku);
}

async function productosAdicionales(id_producto, sku) {
  const frm = new FormData();
  frm.append("sku", sku);
  try {
    const response = await fetch(
      "https://new.imporsuitpro.com/pedidos/buscarProductosBodega/" +
        id_producto,
      {
        method: "POST",
        body: frm,
      }
    );
    const data = await response.json();
    const modalBody = document.querySelector("#productosModal .modal-body");
    modalBody.innerHTML = "";

    // Build your modal content here
    // ... (existing code)
  } catch (error) {
    console.error("Error fetching additional products:", error);
  }
}

const agregarProducto = (id_inventario) => {
  const cantidadElement = document.getElementById("cantidad" + id_inventario);
  const precioElement = document.getElementById("precio" + id_inventario);
  const cantidad = cantidadElement.value;
  const precio = precioElement.value;
  const id_pedido = document.getElementById("id_pedido").value;
  const sku = document.getElementById("productoSku" + id_inventario).value;
  const id_producto = document.getElementById(
    "productoId" + id_inventario
  ).value;

  const frm = new FormData();
  frm.append("id_pedido", id_pedido);
  frm.append("id_producto", id_producto);
  frm.append("cantidad", cantidad);
  frm.append("precio", precio);
  frm.append("sku", sku);
  frm.append("id_inventario", id_inventario);

  fetch("https://new.imporsuitpro.com/pedidos/agregarProductoAPedido", {
    method: "POST",
    body: frm,
  })
    .then((response) => response.json())
    .then(async (data) => {
      if (data.status === 200) {
        Toast.fire({
          icon: "success",
          title: "Producto agregado correctamente",
        });
        await verDetallesPedido(id_pedido);
        setTimeout(() => {
          actualizarContiene();
        }, 1000);
      } else {
        Toast.fire({
          icon: "error",
          title: "Ha ocurrido un error al agregar el producto",
        });
      }
    });
};

const eliminarProducto = (id_detalle) => {
  const frm = new FormData();
  frm.append("id_detalle", id_detalle);
  fetch("https://new.imporsuitpro.com/pedidos/eliminarProductoDePedido", {
    method: "POST",
    body: frm,
  })
    .then((response) => response.json())
    .then(async (data) => {
      if (data.status === 200) {
        Toast.fire({
          icon: "success",
          title: "Producto eliminado correctamente",
        });
        await verDetallesPedido(document.getElementById("id_pedido").value);
      } else {
        Toast.fire({
          icon: "error",
          title: "Ha ocurrido un error al eliminar el producto",
        });
      }
    });
};

const cambio = (id_detalle, id_pedido) => {
  const cantidadElement = document.getElementById("cantidad" + id_detalle);
  const precioElement = document.getElementById("precio" + id_detalle);

  if (cantidadElement && precioElement) {
    const cantidad = parseFloat(cantidadElement.value);
    const precio = parseFloat(precioElement.value);
    const total = cantidad * precio;

    // Actualiza el total del producto editado
    document.getElementById("total_" + id_detalle).innerText = total.toFixed(2);

    // Obtener todos los totales en la tabla
    let totalGeneral = 0;
    const totalElements = document.querySelectorAll('[id^="total_"]'); // Selecciona todos los elementos con id que empiecen con 'total'

    totalElements.forEach((element) => {
      totalGeneral += parseFloat(element.innerText) || 0; // Sumar cada total al total general
    });

    // Actualizar el total de la factura
    document.getElementById("totalPagar").innerText = totalGeneral.toFixed(2);

    // Crear el FormData con los nuevos valores
    const frm = new FormData();
    frm.append("id_detalle", id_detalle);
    frm.append("id_pedido", id_pedido);
    frm.append("cantidad", cantidad);
    frm.append("precio", precio);
    frm.append("total", totalGeneral);

    fetch("https://new.imporsuitpro.com/pedidos/actualizarDetallePedido", {
      method: "POST",
      body: frm,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === 200) {
          Toast.fire({
            icon: "success",
            title: "Producto actualizado correctamente",
          });
          actualizarContiene();
          // Actualizar tarifas de transporte en función del nuevo total
          calcularTarifas(
            document.getElementById("frm_ciudad").value,
            document.getElementById("frm_provincia").value,
            totalGeneral.toFixed(2), // Usar el total general en la calculadora de tarifas
            document.getElementById("frm_recaudacion").value
          );
          document.getElementById("monto_factura").value =
            totalGeneral.toFixed(2);
        } else {
          Toast.fire({
            icon: "error",
            title: "Ha ocurrido un error al actualizar el producto",
          });
        }
      });
  } else {
    console.error(
      `No se encontró el elemento cantidad o precio con id: ${id_detalle}`
    );
  }
};

document.getElementById("closeMenu").addEventListener("click", function () {
  document.getElementById("detailsMenu").style.right = "-500px !important";
  $("#detailsMenu").hide();

  document.getElementById("infoMenu").classList.remove("hidden");

  $("#id_pedido").remove();
  transportadoras.forEach((i) => i.classList.remove("selected"));
  transportadoras.forEach((i) => (i.style.filter = "grayscale(100)"));
});

async function verDetallesPedido(idPedido) {
  mostrarCargando();
  const form = new FormData();
  form.append("id_factura", idPedido);
  try {
    const response = await fetch(
      "https://new.imporsuitpro.com/pedidos/obtenerDetallesPedido",
      {
        method: "POST",
        body: form,
      }
    );
    const data = await response.json();

    document.getElementById("infoMenu").classList.add("hidden");
    $("#detailsMenu").show();
    document.getElementById("detailsMenu").style.right = "0";

    // Fill form fields
    document.getElementById("frm_nombre_cliente").value =
      data.factura[0].nombre;
    document.getElementById("frm_telefono").value = data.factura[0].telefono;

    const selectProvincia = document.getElementById("frm_provincia");
    selectProvincia.dataset.selectedValue = data.factura[0].provincia;

    // Espera a que provincias y ciudades se llenen antes de seleccionar los valores
    await llenarProvincia();

    // Espera que las ciudades se carguen antes de asignar el valor
    await llenarCiudades(data.factura[0].provincia);

    // Seleccionar la ciudad después de llenar las ciudades
    $("#frm_ciudad").val(data.factura[0].ciudad_cot);

    // Seleccionar recaudación
    $("#frm_recaudacion").val(data.factura[0].cod);

    // Asigna otros valores del formulario
    document.getElementById("frm_calle_principal").value =
      data.factura[0].c_principal;
    document.getElementById("frm_calle_secundaria").value =
      data.factura[0].c_secundaria;
    document.getElementById("frm_referencia").value =
      data.factura[0].referencia;
    document.getElementById("frm_observacion").value =
      data.factura[0].observacion;

    // Asigna valores a los campos ocultos
    document.getElementById("nombreO").value = data.factura[0].nombreO;
    document.getElementById("ciudadO").value = data.factura[0].ciudadO;
    document.getElementById("provinciaO").value = data.factura[0].provinciaO;
    document.getElementById("direccionO").value = data.factura[0].direccionO;
    document.getElementById("celularO").value = data.factura[0].telefonoO;
    document.getElementById("referenciaO").value = data.factura[0].referenciaO;

    document.getElementById("numero_factura").value =
      data.factura[0].numero_factura;
    document.getElementById("monto_factura").value =
      data.factura[0].monto_factura;

    // obtener el precio de las transportadoras

    calcularTarifas(
      data.factura[0].ciudad_cot,
      data.factura[0].provincia,
      data.factura[0].monto_factura,
      data.factura[0].cod
    );

    // Create or update hidden input for id_pedido
    let input = document.getElementById("id_pedido");
    if (!input) {
      input = document.createElement("input");
      input.type = "hidden";
      input.id = "id_pedido";
      document.getElementById("detailsMenu").appendChild(input);
    }
    input.value = idPedido;

    document.getElementById("productosBody").innerHTML = "";

    await llenarProductos(data.productos); // Wait for products to load

    // calculate servientrega
    calcularServi(
      data.factura[0].ciudad_cot,
      data.factura[0].provincia,
      data.factura[0].ciudadO,
      data.factura[0].monto_factura
    );
  } catch (error) {
    console.error("Error in verDetallesPedido:", error);
  } finally {
    ocultarCargando();
  }
}

function cancelarPedido() {
  Swal.fire({
    title: "¿Estás seguro de cancelar el pedido?",
    text: "Una vez cancelado no se podrá recuperar",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, cancelar",
    cancelButtonText: "No, mantener",
  }).then((result) => {
    if (result.isConfirmed) {
      const form = new FormData();
      form.append("id_pedido", document.getElementById("id_pedido").value);
      fetch("https://new.imporsuitpro.com/pedidos/cancelarPedido", {
        method: "POST",
        body: form,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === 200) {
            Swal.fire({
              title: "Pedido cancelado",
              text: "El pedido ha sido cancelado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            });
            // Close the menu
            document.getElementById("detailsMenu").style.right = "-500px";
            $("#detailsMenu").hide();
            document.getElementById("infoMenu").classList.remove("hidden");

            // Reload the order list
            listarPedidos();
          } else {
            Swal.fire({
              title: "Error",
              text: "Ha ocurrido un error al cancelar el pedido",
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          }
        });
    }
  });
}

function generarGuia() {
  //obtener transportadora seleccionada
  const transportadora = document.getElementById(
    "selectedTransportadora"
  ).value;
  if (!transportadora) {
    Toast.fire({
      icon: "error",
      title: "Por favor selecciona una transportadora",
      position: "bottom-end",
    });
    return;
  }

  //obtener valores del formulario
  const formulario = new FormData();

  formulario.append("id_pedido", document.getElementById("id_pedido").value);
  formulario.append("nombreO", document.getElementById("nombreO").value);
  formulario.append("ciudadO", document.getElementById("ciudadO").value);
  formulario.append("provinciaO", document.getElementById("provinciaO").value);
  formulario.append("direccionO", document.getElementById("direccionO").value);
  formulario.append("celularO", document.getElementById("celularO").value);
  formulario.append(
    "referenciaO",
    document.getElementById("referenciaO").value
  );
  formulario.append(
    "nombre",
    document.getElementById("frm_nombre_cliente").value
  );
  formulario.append("telefono", document.getElementById("frm_telefono").value);
  formulario.append(
    "provincia",
    document.getElementById("frm_provincia").value
  );
  formulario.append("ciudad", document.getElementById("frm_ciudad").value);
  formulario.append(
    "calle_principal",
    document.getElementById("frm_calle_principal").value
  );
  formulario.append(
    "calle_secundaria",
    document.getElementById("frm_calle_secundaria").value
  );
  formulario.append(
    "referencia",
    document.getElementById("frm_referencia").value
  );
  formulario.append(
    "observacion",
    document.getElementById("frm_observacion").value
  );
  formulario.append(
    "recaudo",
    document.getElementById("frm_recaudacion").value
  );
  formulario.append(
    "numero_factura",
    document.getElementById("numero_factura").value
  );
  formulario.append(
    "total_venta",
    document.getElementById("monto_factura").value
  );
  formulario.append("transportadora", transportadora);
  formulario.append(
    "costo_flete",
    document.getElementById("precio_envio").value
  );

  if (transportadora == "transportadora1") {
    formulario.append("id_transporte", 2);
    generarServientrega(formulario);
  } else if (transportadora == "transportadora2") {
    generarLaar(formulario);
  } else if (transportadora == "transportadora3") {
    generarSpeed(formulario);
  } else if (transportadora == "transportadora4") {
    generarGintracom(formulario);
  }
}

const generarServientrega = (formulario) => {
  var flete = $('#flete').val();
  var seguro = $('#seguro').val();
  var comision = $('#comision').val();
  var otros = $('#otros').val();
  var impuestos = $('#impuestos').val();

  formulario.append("contiene", contiene);
  formulario.append("flete", flete);
  formulario.append("seguro", seguro);
  formulario.append("comision", comision);
  formulario.append("otros", otros);
  formulario.append("impuestos", impuestos);

  Swal.fire({
    title: "Generando guía",
    text: "Por favor espere un momento",
    icon: "info",
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch("https://new.imporsuitpro.com/Guias/generarServientrega", {
    method: "POST",
    body: formulario,
  })
    .then((response) => response.json())
    .then((data) => {
      if (parseInt(data.status) === 200) {
        Swal.fire({
          title: "Guía generada",
          text: "La guía ha sido generada correctamente",
          icon: "success",
          confirmButtonText: "Aceptar",
        });

        document.getElementById("closeMenu").click();
      } else {
        Swal.fire({
          title: "Error",
          text: "Ha ocurrido un error al generar la guía",
          icon: "error",
          confirmButtonText: "Aceptar",
        });
      }
    });
};
const generarSpeed = (formulario) => {
  formulario.append("contiene", contiene);
  Swal.fire({
    title: "Generando guía",
    text: "Por favor espere un momento",
    icon: "info",
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch("https://new.imporsuitpro.com/Guias/generarSpeed", {
    method: "POST",
    body: formulario,
  })
    .then((response) => response.json())
    .then((data) => {
      if (parseInt(data.status) === 200) {
        Swal.fire({
          title: "Guía generada",
          text: "La guía ha sido generada correctamente",
          icon: "success",
          confirmButtonText: "Aceptar",
        });

        document.getElementById("closeMenu").click();
      } else {
        Swal.fire({
          title: "Error",
          text: "Ha ocurrido un error al generar la guía",
          icon: "error",
          confirmButtonText: "Aceptar",
        });
      }
    });
};
const generarGintracom = (formulario) => {
  formulario.append("contiene", contiene);
  Swal.fire({
    title: "Generando guía",
    text: "Por favor espere un momento",
    icon: "info",
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch("https://new.imporsuitpro.com/Guias/generarGintracom", {
    method: "POST",
    body: formulario,
  })
    .then((response) => response.json())
    .then((data) => {
      if (parseInt(data.status) === 200) {
        Swal.fire({
          title: "Guía generada",
          text: "La guía ha sido generada correctamente",
          icon: "success",
          confirmButtonText: "Aceptar",
        });

        document.getElementById("closeMenu").click();
      } else {
        Swal.fire({
          title: "Error",
          text: "Ha ocurrido un error al generar la guía",
          icon: "error",
          confirmButtonText: "Aceptar",
        });
      }
    });
};

const generarLaar = (formulario) => {
  formulario.append("contiene", contiene);
  Swal.fire({
    title: "Generando guía",
    text: "Por favor espere un momento",
    icon: "info",
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });
  fetch("https://new.imporsuitpro.com/Guias/generarLaar", {
    method: "POST",
    body: formulario,
  })
    .then((response) => response.json())
    .then((data) => {
      if (parseInt(data.status) === 200) {
        Swal.fire({
          title: "Guía generada",
          text: "La guía ha sido generada correctamente",
          icon: "success",
          confirmButtonText: "Aceptar",
        });

        document.getElementById("closeMenu").click();
      } else {
        Swal.fire({
          title: "Error",
          text: "Ha ocurrido un error al generar la guía",
          icon: "error",
          confirmButtonText: "Aceptar",
        });
      }
    });
};

const transportadoras = document.querySelectorAll(".transportadora");

transportadoras.forEach((img) => {
  img.addEventListener("click", function () {
    selectedValue = this.getAttribute("data-value");
    const precio = document.querySelector("." + selectedValue).innerText;
    if (precio == "$0") {
      Toast.fire({
        icon: "error",
        title:
          "Esta transportadora no tiene cobertura en la ciudad seleccionada",
        position: "bottom-end",
      });
      return;
    }

    if (this.classList.contains("selected")) {
      this.classList.remove("selected");
      this.style.filter = "grayscale(100)";
      selectedValue = "";
      document.getElementById("selectedTransportadora").value = "";
      return;
    }
    // obtener el precio de la transportadora seleccionada
    console.log(precio);
    transportadoras.forEach((i) => i.classList.remove("selected"));
    this.classList.add("selected");
    this.style.filter = "grayscale(0)";

    // set precio_envio
    document.getElementById("precio_envio").value = precio;

    // Set others to grayscale
    transportadoras.forEach((i) => {
      if (i !== this) {
        i.style.filter = "grayscale(100)";
      }
    });
    document.getElementById("selectedTransportadora").value = selectedValue;
  });
});

document
  .getElementById("formTransportadora")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    if (selectedValue) {
      alert("Has seleccionado: " + selectedValue);
      // Aquí puedes agregar el envío de la selección al servidor
    } else {
      alert("Por favor selecciona una transportadora.");
    }
  });

document.addEventListener("DOMContentLoaded", () => {
  listarPedidos();
  // No need to call llenarProvincia() here
});
/* fin seccion de creacion de guais y historial pedidos */
