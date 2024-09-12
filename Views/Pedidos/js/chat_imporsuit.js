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
const emojiSection = document.getElementById("emoji-section");
const emojiSearch = document.getElementById("emoji-search");
const messageInput = document.getElementById("message-input");

// Categorías disponibles en la API
const categories = {
  "Smileys & Emotion": "Caritas y emociones",
  "Animals & Nature": "Animales y naturaleza",
  "Food & Drink": "Comida y bebida",
  "Travel & Places": "Viajes y lugares",
  Activities: "Actividades",
  Objects: "Objetos",
  Symbols: "Símbolos",
  Flags: "Banderas",
};

// Cargar los emojis desde la API
async function loadEmojis() {
  try {
    const response = await fetch(
      "https://emoji-api.com/emojis?access_key=TU_CLAVE_API"
    );
    const emojis = await response.json();
    displayEmojisByCategory(emojis);
  } catch (error) {
    console.error("Error al cargar los emojis:", error);
  }
}

// Mostrar emojis divididos por categorías
function displayEmojisByCategory(emojis) {
  emojiSection.innerHTML = ""; // Limpiar el contenedor de emojis

  // Crear un contenedor para cada categoría
  Object.keys(categories).forEach((categoryKey) => {
    const categoryEmojis = emojis.filter(
      (emoji) => emoji.group === categoryKey
    );

    if (categoryEmojis.length > 0) {
      // Crear la sección para la categoría
      const categoryContainer = document.createElement("div");
      categoryContainer.classList.add("emoji-category");

      // Título de la categoría
      const title = document.createElement("h5");
      title.textContent = categories[categoryKey];
      categoryContainer.appendChild(title);

      // Crear la cuadrícula de emojis
      const emojiGrid = document.createElement("div");
      emojiGrid.classList.add("emoji-grid");

      // Añadir los emojis a la cuadrícula
      categoryEmojis.forEach((emoji) => {
        const span = document.createElement("span");
        span.classList.add("emoji");
        span.textContent = emoji.character;

        // Agregar el emoji al input cuando se hace clic
        span.addEventListener("click", () => {
          messageInput.value += emoji.character;
        });

        emojiGrid.appendChild(span);
      });

      // Añadir la cuadrícula a la categoría
      categoryContainer.appendChild(emojiGrid);
      emojiSection.appendChild(categoryContainer); // Añadir la categoría al contenedor general
    }
  });
}

// Filtrar emojis en función de la búsqueda
emojiSearch.addEventListener("input", () => {
  const searchTerm = emojiSearch.value.toLowerCase();
  const emojis = document.querySelectorAll(".emoji");

  emojis.forEach((emoji) => {
    if (emoji.textContent.includes(searchTerm)) {
      emoji.style.display = "block";
    } else {
      emoji.style.display = "none";
    }
  });
});

// Cargar los emojis cuando la página esté lista
loadEmojis();

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
