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
let displayedEmojis = 30; // Inicialmente mostramos 30 emojis

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
  const emojiSection = document.getElementById("emoji-section");

  // Limpiar la sección excepto el input de búsqueda
  const searchInput = document.getElementById("emoji-search");
  emojiSection.innerHTML = "";
  emojiSection.appendChild(searchInput); // Mantener el buscador en su lugar

  emojis.slice(0, limit).forEach((emoji) => {
    const span = document.createElement("span");
    span.classList.add("emoji");
    span.textContent = emoji.character;
    span.addEventListener("click", () => addEmojiToInput(emoji.character));
    emojiSection.appendChild(span);
  });
}

// Filtrar emojis según el texto en el campo de búsqueda
function filterEmojis() {
  const searchTerm = emojiSearch.value.toLowerCase();
  const filteredEmojis = allEmojis.filter((emoji) =>
    emoji.unicodeName.toLowerCase().includes(searchTerm)
  );
  renderEmojis(filteredEmojis);
}

// Agregar evento de búsqueda
emojiSearch.addEventListener("input", filterEmojis);

// Carga asíncrona de más emojis al hacer scroll
emojiSection.addEventListener("scroll", () => {
  if (
    emojiSection.scrollTop + emojiSection.clientHeight >=
    emojiSection.scrollHeight
  ) {
    // Cargar más emojis cuando se llegue al fondo del scroll
    displayedEmojis += 30; // Cargar 30 más
    renderEmojis(allEmojis); // Re-renderizar con los nuevos emojis
  }
});

/* Llenar sección emojis */
fetch(
  "https://emoji-api.com/emojis?access_key=bbe48b2609417c3b0dc67a95b31e62d0acb27c5b"
)
  .then((response) => response.json())
  .then((emojis) => {
    allEmojis = emojis; // Almacenar todos los emojis para el filtro
    renderEmojis(allEmojis); // Renderizar todos los emojis inicialmente
  })
  .catch((error) => console.error("Error al cargar los emojis:", error));

/* Fin llenar seccion emojis */

/* Fin emojis */

/* Enviar mensaje whatsapp */
document.addEventListener("DOMContentLoaded", function () {
  const sendButton = document.getElementById("send-button");
  const messageInput = document.getElementById("message-input");

  const fromPhoneNumberId = "109565362009074"; // Identificador de número de teléfono
  const accessToken =
    "EAAVZAG5oL9G4BOyrsyNgZBmlNXqlTB9ObbeyYhVyZBItJgJzyyVzt4Kuwz1P6OZAZAyB2wC9qFBLnc5qE9ZBrvDJ2yqPHlzekeN051WhK1qMF4QfXrtUScbZCeFrGJiaqHHZCPFg3CHyTXrAhzA9mKjlx6g09P4ZBjrppXBLfgBfGGMLgTxHTrb5vtpmjZBgEh9nZAwxgZDZD"; // Asegúrate de que este token sea válido
  const phoneNumber = "+593981702066"; // Número al que se va a enviar

  const url = `https://graph.facebook.com/v19.0/${fromPhoneNumberId}/messages`;

  // Capturar el evento click del botón de envío
  sendButton.addEventListener("click", function (event) {
    event.preventDefault();

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
  });
});
