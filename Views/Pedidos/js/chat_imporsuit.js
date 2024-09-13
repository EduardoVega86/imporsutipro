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
  // No cerramos la sección de emojis al seleccionar uno
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
  const fromPhoneNumberId = "109565362009074"; // Identificador de número de teléfono de WhatsApp
  const accessToken =
    "EAAVZAG5oL9G4BO3vZAhKcOTpfZAQJgNDzTNDArOp8VitYT8GUFqcYKIsZAO0pBkf0edoZC1DgfXICkIEP7xZCkPkj8nS1gfDqI4jNeEVDmseyba3l2os8EoYgf1Mdnl2MwaYhmrdfZBgUnItwT8nZBVvjinB7j8IAfZBx2LZA1WNZCqqsZBZC2cqDdObeiLqEsih9U3XOQwZDZD"; // Asegúrate de que este token sea válido
  const phoneNumber = "+593981702066"; // Número al que se va a enviar el mensaje o audio
  const url = `https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`;

  // ---- Variables para la grabación de audio ----
  let mediaRecorder;
  let audioBlob = null; // Inicializamos como null
  let isRecording = false;
  let timerInterval;
  let timeElapsed = 0;
  let stream; // Variable para almacenar el flujo del micrófono

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
        stream = micStream; // Guardamos el flujo del micrófono para detenerlo más tarde
        mediaRecorder = new MediaRecorder(stream);

        mediaRecorder.start();
        isRecording = true;
        audioBlob = null; // Reiniciar el valor del audioBlob al iniciar grabación
        console.log("Grabación iniciada");

        // Forzar la visualización de los controles de grabación
        audioControls.style.display = "block";
        audioControls.classList.remove("d-none");

        recordButton
          .querySelector("i")
          .classList.replace("fa-microphone", "fa-stop");

        // Iniciar temporizador
        timeElapsed = 0;
        timerInterval = setInterval(updateTimer, 1000);

        mediaRecorder.addEventListener("dataavailable", (event) => {
          console.log("Datos de audio recibidos:", event.data);
          audioBlob = event.data; // Guardamos el Blob cuando esté disponible
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
      stream.getTracks().forEach((track) => track.stop()); // Detenemos el flujo del micrófono
      clearInterval(timerInterval);
      isRecording = false;
      audioControls.classList.add("d-none");
      recordButton
        .querySelector("i")
        .classList.replace("fa-stop", "fa-microphone");
      console.log("Grabación detenida");
    }
  }

  // ---- Función para convertir y enviar el archivo de audio ----
  async function convertAndSendAudio(audioBlob) {
    const ffmpeg = FFmpeg.createFFmpeg({ log: true });
    if (!ffmpeg.isLoaded()) {
      await ffmpeg.load();
    }

    // Cargar el archivo webm a FFmpeg
    ffmpeg.FS('writeFile', 'input.webm', await fetchFile(audioBlob));

    // Ejecutar la conversión a OGG
    await ffmpeg.run('-i', 'input.webm', 'output.ogg');

    // Obtener el archivo convertido
    const data = ffmpeg.FS('readFile', 'output.ogg');
    const blob = new Blob([data.buffer], { type: 'audio/ogg' });

    // Crear un objeto FormData para subir el archivo convertido al servidor
    const formData = new FormData();
    formData.append('audio', blob, 'audio.ogg');

    // Subir el archivo al servidor
    const response = await fetch(SERVERURL + "Pedidos/guardar_audio_Whatsapp", {
      method: 'POST',
      body: formData
    });

    const result = await response.json();
    if (result.status === 200) {
      console.log("Audio convertido y subido:", result.data);

      // Ahora enviar el archivo subido a WhatsApp
      const audioUrl = SERVERURL + result.data;
      enviarAudioAWsp(audioUrl);
    } else {
      console.error("Error al subir el audio:", result.message);
      alert("Error al subir el audio convertido.");
    }
  }

  // ---- Función para enviar el audio convertido a WhatsApp ----
  function enviarAudioAWsp(audioUrl) {
    const data = {
      messaging_product: "whatsapp",
      recipient_type: "individual",
      to: phoneNumber,
      type: "audio",
      audio: {
        link: audioUrl, // URL del archivo subido al servidor
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
          console.error("Error: ", responseData.error);
          alert(`Error: ${responseData.error.message}`);
        } else {
          alert("¡Audio enviado con éxito a WhatsApp!");
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
      });
  }

  // ---- Botón de enviar audio a WhatsApp ----
  sendAudioButton.addEventListener("click", async () => {
    stopRecording();

    setTimeout(() => {
      if (audioBlob && audioBlob.size > 0) {
        console.log("Tamaño del audioBlob:", audioBlob.size);
        console.log("El archivo de audio tiene datos, procediendo a convertir...");
        convertAndSendAudio(audioBlob);
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

  // ---- Evento para cambiar los botones cuando se escribe en el input ----
  messageInput.addEventListener("input", toggleButtons);

  // ---- Botón para enviar texto ----
  sendButton.addEventListener("click", (event) => {
    event.preventDefault();
    const message = messageInput.value.trim();
    if (message) {
      sendMessageToWhatsApp(message);
    } else {
      alert("Por favor, escribe un mensaje.");
    }
  });

  // ---- Enviar mensaje con la tecla Enter ----
  messageInput.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      event.preventDefault(); // Evitar el salto de línea
      const message = messageInput.value.trim();
      if (message) {
        sendMessageToWhatsApp(message);
      } else {
        alert("Por favor, escribe un mensaje.");
      }
    }
  });

  // ---- Botón para iniciar/detener la grabación ----
  recordButton.addEventListener("click", () => {
    if (!isRecording) {
      startRecording();
    } else {
      stopRecording();
    }
  });

  // Iniciar con el botón de grabar visible
  toggleButtons();
});

/* Fin enviar mensaje de audio Whatsapp */
