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

/* Enviar mensaje whatsapp */
document.addEventListener("DOMContentLoaded", function () {
  const sendButton = document.getElementById("send-button");
  const messageInput = document.getElementById("message-input");

  const fromPhoneNumberId = "109565362009074"; // Identificador de número de teléfono
  const accessToken =
    "EAAVZAG5oL9G4BO5Be7wI2OuGoEkfjSIwTZAf5ihLOmVxcrTAxtkQfJJqWb6ax14MZCrgZChWZA2ZAqG7lsM6iTZCZAvbrMTu5Di7dMlL1KFSob1oN814V0RQv2RGq5OGhlNZCgUnRRwLYPmdyPx5ZBVBGdm3h5S3Jp812Gud6sETPW1KTLLn03X6ZBTwlf5qeEyh16ZC";
  const phoneNumber = "+593992717075"; // Número al que se va a enviar

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

    // Datos para enviar el mensaje
    const data = {
      messaging_product: "whatsapp",
      recipient_type: "individual",
      to: phoneNumber,
      type: "text",
      text: {
        preview_url: true,
        body: message, // Usar el mensaje que el usuario escribió
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
