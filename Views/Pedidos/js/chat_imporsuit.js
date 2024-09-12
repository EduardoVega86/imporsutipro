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
const fromPhoneNumberId = "109565362009074"; // Identificador de número de teléfono
const accessToken =
  "EAAVZAG5oL9G4BOyrsyNgZBmlNXqlTB9ObbeyYhVyZBItJgJzyyVzt4Kuwz1P6OZAZAyB2wC9qFBLnc5qE9ZBrvDJ2yqPHlzekeN051WhK1qMF4QfXrtUScbZCeFrGJiaqHHZCPFg3CHyTXrAhzA9mKjlx6g09P4ZBjrppXBLfgBfGGMLgTxHTrb5vtpmjZBgEh9nZAwxgZDZD"; // Asegúrate de que este token sea válido
const phoneNumber = "+593981702066"; // Número al que se va a enviar

const url = `https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`;

document.addEventListener("DOMContentLoaded", function () {
  const sendButton = document.getElementById("send-button");
  const messageInput = document.getElementById("message-input");

  // Función para enviar el mensaje
  function sendMessage() {
    // Obtener el mensaje ingresado por el usuario
    const message = messageInput.value;

    if (message.trim() === "") {
      alert("Por favor, escribe un mensaje.");
      return;
    }

    // Datos para enviar el mensaje usando una plantilla
    /* const data = {
      messaging_product: "whatsapp",
      to: phoneNumber,
      type: "template",
      template: {
        name: "hello_world", // Plantilla que estás usando
        language: { code: "en_US" }, // Lenguaje de la plantilla
      },
    }; */

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

    // Usando fetch para enviar el mensaje
    fetch(url, {
      method: "POST",
      headers: headers,
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((responseData) => {
        console.log("API Response: ", responseData); // Mostrar la respuesta completa
        if (responseData.error) {
          console.error("Error: ", responseData.error);
          alert(`Error: ${responseData.error.message}`);
        } else {
          console.log("Response: ", responseData);
          alert("¡Mensaje enviado con éxito!");

          // Limpiar el campo de entrada después de enviar el mensaje
          messageInput.value = "";
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud: ", error);
        alert("Ocurrió un error al enviar el mensaje.");
      });
  }

  // Deshabilitar la funcionalidad del botón de envío
  sendButton.addEventListener("click", function (event) {
    event.preventDefault(); // El botón ya no hace nada
  });

  // Ejecutar la función de enviar mensaje al presionar "Enter"
  messageInput.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      event.preventDefault(); // Evitar el salto de línea
      sendMessage(); // Llamar a la función de envío
    }
  });

  /* Fin enviar mensaje Whatsapp */
});

/* Enviar mensaje de audio Whatsapp */
let mediaRecorder;
let audioChunks = [];
let isRecording = false;
let timerInterval;
let timeElapsed = 0;

// Elementos del DOM
const recordButton = document.getElementById("record-button");
const audioControls = document.getElementById("audio-recording-controls");
const pauseButton = document.getElementById("pause-recording");
const stopButton = document.getElementById("stop-recording");
const sendAudioButton = document.getElementById("send-audio");
const audioTimer = document.getElementById("audio-timer");

// Función para actualizar el temporizador
function updateTimer() {
  timeElapsed++;
  let minutes = Math.floor(timeElapsed / 60);
  let seconds = timeElapsed % 60;
  audioTimer.textContent = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
}

// Al hacer clic en el botón de grabar
recordButton.addEventListener("click", () => {
  if (!isRecording) {
    startRecording();
  } else {
    stopRecording();
  }
});

// Función para iniciar la grabación
function startRecording() {
  navigator.mediaDevices.getUserMedia({ audio: true }).then((stream) => {
    mediaRecorder = new MediaRecorder(stream);
    mediaRecorder.start();
    audioChunks = [];

    mediaRecorder.addEventListener("dataavailable", (event) => {
      audioChunks.push(event.data);
    });

    mediaRecorder.addEventListener("stop", () => {
      const audioBlob = new Blob(audioChunks, {
        type: "audio/ogg; codecs=opus",
      });
      const audioUrl = URL.createObjectURL(audioBlob);
      const audio = new Audio(audioUrl);
      audio.play(); // Reproducir el audio grabado para pruebas
    });

    // Mostrar controles de grabación
    audioControls.classList.remove("d-none");
    isRecording = true;
    recordButton
      .querySelector("i")
      .classList.replace("fa-microphone", "fa-stop");

    // Iniciar temporizador
    timeElapsed = 0;
    timerInterval = setInterval(updateTimer, 1000);
  });
}

// Función para detener la grabación
function stopRecording() {
  mediaRecorder.stop();
  clearInterval(timerInterval);
  isRecording = false;
  audioControls.classList.add("d-none");
  recordButton.querySelector("i").classList.replace("fa-stop", "fa-microphone");
}

// Función para enviar el audio grabado a WhatsApp
sendAudioButton.addEventListener("click", () => {
  const audioBlob = new Blob(audioChunks, { type: "audio/ogg; codecs=opus" });
  const formData = new FormData();
  formData.append("file", audioBlob, "audio.ogg");
  formData.append("messaging_product", "whatsapp");
  formData.append("to", phoneNumber);
  formData.append("type", "audio");

  fetch(`https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${accessToken}`,
    },
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Audio enviado:", data);
      alert("¡Audio enviado con éxito!");
    })
    .catch((error) => {
      console.error("Error al enviar el audio:", error);
    });
});
/* Fin enviar mensaje de audio Whatsapp */
