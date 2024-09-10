const infoPanel = document.getElementById("info-panel");
const chatContent = document.querySelector(".chat-content");
const toggleInfoBtn = document.querySelector(".fa-ellipsis-v"); // El botón de los 3 puntos
const closeInfoBtn = document.getElementById("close-info");

// Alternar visibilidad del panel
toggleInfoBtn.addEventListener("click", () => {
  if (infoPanel.classList.contains("open")) {
    infoPanel.classList.remove("open");
    chatContent.classList.remove("reduced");
  } else {
    infoPanel.classList.add("open");
    chatContent.classList.add("reduced");
  }
});

// Cerrar el panel al hacer clic en la "X"
closeInfoBtn.addEventListener("click", () => {
  infoPanel.classList.remove("open");
  chatContent.classList.remove("reduced");
});

// Ocultar el panel de la derecha al cargar la página
window.addEventListener("load", () => {
  infoPanel.classList.remove("open");
  chatContent.classList.remove("reduced");
});
