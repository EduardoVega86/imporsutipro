/* llenar seccion numeros */
$(document).ready(function () {
  // Llamada AJAX para obtener los datos de la API de contactos
  $.ajax({
    url: SERVERURL + "Pedidos/numeros_clientes",
    method: "GET",
    dataType: "json",
    success: function (data) {
      let innerHTML = "";

      // Recorremos cada contacto
      $.each(data, function (index, contacto) {
        innerHTML += `
            <li class="list-group-item contact-item d-flex align-items-center" data-id="${
              contacto.id_cliente
            }">
                <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                <div>
                    <h6 class="mb-0">${
                      contacto.nombre_cliente || "Desconocido"
                    } ${contacto.apellido_cliente || ""}</h6>
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

        $("#id_cliente_chat").val(response[0].id);
        $("#celular_chat").val(response[0].celular_cliente);
        $("#uid_cliente").val(response[0].uid_cliente);

        // Llamar a la función para cargar los mensajes iniciales del chat
        cargarMensajesChat(id_cliente);
      },
      error: function (error) {
        console.error("Error al ejecutar la API:", error);
      },
    });
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
      // Verificamos el rol_mensaje para determinar si es "sent" o "received"
      let claseMensaje = mensaje.rol_mensaje == 1 ? "sent" : "received";

      innerHTML += `
        <div class="message ${claseMensaje}">
          ${mensaje.texto_mensaje}
        </div>
      `;
    });

    // Inyectamos los mensajes en el contenedor de mensajes
    $(".chat-messages").html(innerHTML);
  }

  // Función para iniciar el polling de mensajes
  let pollingInterval;

  function startPollingMensajes(id_cliente) {
    // Si ya hay un intervalo de polling, lo limpiamos para evitar duplicados
    if (pollingInterval) {
      clearInterval(pollingInterval);
    }

    // Definimos el intervalo de polling (por ejemplo, cada 5 segundos)
    pollingInterval = setInterval(function () {
      cargarMensajesChat(id_cliente); // Volvemos a cargar los mensajes en intervalos regulares
    }, 5000); // Cada 5 segundos
  }

  // Función para detener el polling (opcional)
  function stopPollingMensajes() {
    if (pollingInterval) {
      clearInterval(pollingInterval); // Detenemos el polling si es necesario
    }
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
    "EAAVZAG5oL9G4BO3vZAhKcOTpfZAQJgNDzTNDArOp8VitYT8GUFqcYKIsZAO0pBkf0edoZC1DgfXICkIEP7xZCkPkj8nS1gfDqI4jNeEVDmseyba3l2os8EoYgf1Mdnl2MwaYhmrdfZBgUnItwT8nZBVvjinB7j8IAfZBx2LZA1WNZCqqsZBZC2cqDdObeiLqEsih9U3XOQwZDZD";
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

  // ---- Botón de enviar audio a WhatsApp ----
  sendAudioButton.addEventListener("click", async () => {
    stopRecording();

    setTimeout(async () => {
      if (audioBlob && audioBlob.size > 0) {
        console.log("Tamaño del audioBlob:", audioBlob.size);
        console.log("El archivo de audio tiene datos, procediendo a subir...");

        const audioUrl = await uploadAudio(audioBlob);

        if (audioUrl) {
          var phoneNumber = "+" + $("#celular_chat").val();
          const data = {
            messaging_product: "whatsapp",
            recipient_type: "individual",
            to: phoneNumber,
            type: "audio",
            audio: {
              link: SERVERURL + audioUrl,
            },
          };

          fetch(url, {
            method: "POST",
            headers: {
              Authorization: `Bearer ${accessToken}`,
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          })
            .then((response) => response.json())
            .then((responseData) => {
              if (responseData.error) {
                console.error("Error: ", responseData.error);
                alert(`Error: ${responseData.error.message}`);
              } else {
                alert("¡Audio enviado con éxito!");
              }
            })
            .catch((error) => {
              console.error("Error al enviar el audio:", error);
            });
        } else {
          console.error("No se pudo obtener la URL del archivo de audio.");
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
/* Fin enviar mensaje de audio Whatsapp */
