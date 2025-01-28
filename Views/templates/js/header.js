document.addEventListener("DOMContentLoaded", () => {
    const dropdownBtn = document.getElementById("marketplace-btn");
    const submenu = document.getElementById("submenu01");

    if (dropdownBtn && submenu) {
        // Alternar el submenú al hacer clic en el botón
        dropdownBtn.addEventListener("click", (e) => {
            e.preventDefault(); // Evita la recarga de la página
            submenu.classList.toggle("show"); // Alterna la visibilidad del submenú
        });
    }
});
