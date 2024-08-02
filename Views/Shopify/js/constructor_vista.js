document.addEventListener("DOMContentLoaded", function () {
  const apiURL = SERVERURL + "/shopify/obtenerConfiguracion";

  async function fetchData() {
    try {
      const response = await fetch(apiURL);
      const data = await response.json();
      displayData(data);
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }

  function displayData(data) {
    const container = document.querySelector(".datos_shopify");
    container.innerHTML = "";

    data.forEach((config) => {
      const configDiv = document.createElement("div");
      configDiv.classList.add("config-item");

      const configHtml = generateHtmlFromJson(config);
      configDiv.innerHTML = configHtml;
      container.appendChild(configDiv);
    });
  }

  function generateHtmlFromJson(obj, level = 0) {
    let html = "<ul>";

    for (let key in obj) {
      if (obj.hasOwnProperty(key)) {
        const value = obj[key];
        const formattedKey = `<strong>${key.replace(
          "/",
          ":</strong> <span>"
        )}</span>`;

        if (typeof value === "object" && value !== null) {
          html += `<li>${formattedKey}:<ul class="nested">${generateHtmlFromJson(
            value,
            level + 1
          )}</ul></li>`;
        } else if (typeof value === "string" && value.includes("/")) {
          html += `<li>${formattedKey}:</li>`;
          html += formatComplexString(value, level);
        } else {
          html += `<li>${formattedKey}: <span>${value}</span></li>`;
        }
      }
    }

    html += "</ul>";
    return html;
  }

  function formatComplexString(value, level) {
    const parts = value.split("/");
    let html = '<ul class="nested">';

    parts.forEach((part) => {
      html += `<li style="padding-left: 5px;">${part}</li>`;
    });

    html += "</ul>";
    return html;
  }

  fetchData();
});
