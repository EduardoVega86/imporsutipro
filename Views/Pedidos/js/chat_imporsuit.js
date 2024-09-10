const infoPanel = document.getElementById("info-panel");
const chatContent = document.getElementById("chat-content");
const toggleInfoBtn = document.querySelector(".fa-ellipsis-v"); // El botón de los 3 puntos
const closeInfoBtn = document.getElementById("close-info");

// Alternar visibilidad del panel
toggleInfoBtn.addEventListener("click", () => {
  if (infoPanel.classList.contains("hidden")) {
    infoPanel.classList.remove("hidden");
    infoPanel.classList.add("visible");
    chatContent.classList.remove("expanded");
  } else {
    infoPanel.classList.add("hidden");
    chatContent.classList.add("expanded");
  }
});

// Cerrar el panel al hacer clic en la "X"
closeInfoBtn.addEventListener("click", () => {
  infoPanel.classList.add("hidden");
  chatContent.classList.add("expanded");
});

// Ocultar el panel de la derecha al cargar la página
window.addEventListener("load", () => {
  infoPanel.classList.remove("visible");
  infoPanel.classList.add("hidden");
  chatContent.classList.add("expanded");
});
