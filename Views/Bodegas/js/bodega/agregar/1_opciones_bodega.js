
document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll("#menu .nav-link");
    const sections = document.querySelectorAll(".content-section");

    menuItems.forEach(item => {
        item.addEventListener("click", function (e) {
            e.preventDefault();

            // Quitar la clase activa de todos los enlaces
            menuItems.forEach(link => link.classList.remove("active"));
            this.classList.add("active");

            // Ocultar todas las secciones
            sections.forEach(section => section.classList.add("hidden-all"));

            // Mostrar la sección correspondiente
            const sectionId = this.getAttribute("data-section");
            document.getElementById(sectionId).classList.remove("hidden-all");
        });
    });
});