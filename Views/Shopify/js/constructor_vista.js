document.addEventListener("DOMContentLoaded", function () {
  // URL de la API
  const apiURL = SERVERURL + "/shopify/obtenerConfiguracion";

  // Función para obtener datos de la API
  async function fetchData() {
    try {
      const response = await fetch(apiURL);
      const data = await response.json();
      displayData(data);
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }

  // Función para mostrar los datos en el DOM
  function displayData(data) {
    const container = document.querySelector(".datos_shopify");
    container.innerHTML = ""; // Limpiamos el contenido previo

    data.forEach((config) => {
      const configDiv = document.createElement("div");
      configDiv.classList.add("config-item");

      // Construir la estructura de datos
      const configHtml = generateHtmlFromJson(config);
      configDiv.innerHTML = configHtml;
      container.appendChild(configDiv);
    });
  }

  // Función recursiva para generar HTML a partir de un objeto JSON
  function generateHtmlFromJson(obj, level = 0) {
    let html = '<ul style="list-style-type:none; padding-left: 0;">';

    for (let key in obj) {
      if (obj.hasOwnProperty(key)) {
        const value = obj[key];
        const formattedKey = `<strong>${key.replace("/", ":</strong> ")}`;

        if (typeof value === "object" && value !== null) {
          // Si el valor es un objeto, llamamos recursivamente
          html += `<li>${formattedKey}: ${generateHtmlFromJson(
            value,
            level + 1
          )}</li>`;
        } else if (typeof value === "string" && value.includes("/")) {
          // Si el valor es una cadena con '/', formateamos en varios niveles
          html += `<li>${formattedKey}:</li>`;
          html += formatComplexString(value, level);
        } else {
          // Si es un valor simple, lo mostramos directamente
          html += `<li>${formattedKey}: ${value}</li>`;
        }
      }
    }

    html += "</ul>";
    return html;
  }

  // Función para formatear cadenas complejas (con '/')
  function formatComplexString(value, level) {
    const parts = value.split("/");
    let html = "";

    parts.forEach((part) => {
      html += `<li style="padding-left: ${
        level * 20
      }px;"><strong>${part}</strong></li>`;
    });

    return html;
  }

  // Llamar a la función para obtener datos
  fetchData();
});
