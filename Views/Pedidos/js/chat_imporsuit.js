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
  const audioControls = document.getElementById("audio-recording-controls");
  const pauseButton = document.getElementById("pause-recording");
  const stopButton = document.getElementById("stop-recording");
  const sendAudioButton = document.getElementById("send-audio");
  const audioTimer = document.getElementById("audio-timer");

  // ---- Variables para la grabación de audio ----
  let mediaRecorder;
  let audioChunks = [];
  let isRecording = false;
  let isPaused = false;
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
        stream = micStream; // Guardamos el flujo del micrófono para detenerlo más tarde

        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];

        mediaRecorder.start();
        isRecording = true;
        console.log("Grabación iniciada"); // Log para depurar

        // Escuchar los datos disponibles y agregar a audioChunks
        mediaRecorder.addEventListener("dataavailable", (event) => {
          console.log("Datos de audio recibidos:", event.data); // Verificar si se reciben datos
          audioChunks.push(event.data);
        });

        // Mostrar controles de grabación
        audioControls.classList.remove("d-none");
        recordButton
          .querySelector("i")
          .classList.replace("fa-microphone", "fa-stop");

        // Iniciar temporizador
        timeElapsed = 0;
        timerInterval = setInterval(updateTimer, 1000);
      })
      .catch((error) => {
        console.error("Error al acceder al micrófono:", error); // Manejo de errores al acceder al micrófono
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
      isPaused = false;
      audioControls.classList.add("d-none");
      recordButton
        .querySelector("i")
        .classList.replace("fa-stop", "fa-microphone");
      console.log("Grabación detenida"); // Log para depurar
    }
  }

  // ---- Función para subir el archivo de audio ----
  function uploadAudio(audioBlob) {
    const formData = new FormData();
    formData.append("audio", audioBlob, "audio.webm"); // Cambiamos el formato a 'audio.webm'

    return fetch(SERVERURL + "Pedidos/guardar_audio_Whatsapp", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === 200) {
          console.log("Audio subido:", data.data); // Aquí tienes la ruta al archivo en el servidor
          return data.data; // Devuelve la ruta del archivo
        } else {
          console.error("Error al subir el audio:", data.message);
          throw new Error(data.message);
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
      });
  }

  // ---- Botón de enviar audio ----
  sendAudioButton.addEventListener("click", async () => {
    // Detener grabación antes de enviar el audio
    stopRecording();

    // Crear un archivo Blob en formato webm (en lugar de ogg)
    const audioBlob = new Blob(audioChunks, {
      type: "audio/webm; codecs=opus",
    });

    // Verifica si el audioBlob contiene datos
    console.log("Tamaño del audioBlob:", audioBlob.size); // Log para depurar
    if (audioBlob.size > 0) {
      console.log("El archivo de audio tiene datos, procediendo a subir...");
    } else {
      console.error("El archivo de audio está vacío.");
      alert("No se ha grabado ningún audio.");
      return;
    }

    // Sube el archivo de audio al servidor y obtén la URL
    const audioUrl = await uploadAudio(audioBlob);

    if (!audioUrl) {
      console.error("No se pudo obtener la URL del archivo de audio.");
      return;
    }

    // Ahora puedes enviar esa URL a través de WhatsApp
    const data = {
      messaging_product: "whatsapp",
      recipient_type: "individual",
      to: phoneNumber,
      type: "audio",
      audio: {
        link: SERVERURL + audioUrl, // Agrega la URL completa del archivo
      },
    };

    fetch(`https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`, {
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
  });

  // ---- Botón para iniciar/detener la grabación ----
  recordButton.addEventListener("click", () => {
    if (!isRecording) {
      startRecording();
    } else {
      stopRecording(); // Detener si está en proceso de grabación
    }
  });
});

/* Fin enviar mensaje de audio Whatsapp */
