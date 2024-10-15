// Función principal que se ejecuta al cargar el DOM
document.addEventListener("DOMContentLoaded", () => {
  iniciarAplicacion();
});

async function iniciarAplicacion() {
  try {
    await verificarSuscripciones();
    await cargarTiendas();
    configurarEventos();
    iniciarSlider();
  } catch (error) {
    console.error("Error al iniciar la aplicación:", error);
  }
}

// Verificar suscripciones del usuario
async function verificarSuscripciones() {
  const email = "<?php echo $_SESSION['user']; ?>";
  const response = await fetch(
    "https://herramientas.imporfactory.app/suscripciones",
    {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email }),
    }
  );
  const data = await response.json();

  const diasElemento = document.querySelector(".dias");

  if (data.status === "error") {
    diasElemento.textContent = "¡No tienes suscripción activa!";
    return;
  }

  if (data.dias === 0) {
    diasElemento.textContent = "¡Tu suscripción vence hoy!";
  } else {
    diasElemento.textContent = `Tu suscripción vence en ${data.suscripciones[0].dias} días`;
  }

  // Actualizar herramientas disponibles
  actualizarHerramientas(data.suscripciones);
}

function actualizarHerramientas(suscripciones) {
  suscripciones.forEach((suscripcion) => {
    if (suscripcion.dias > 0) {
      const herramientaId = obtenerIdHerramienta(suscripcion.sistema);
      if (herramientaId) {
        document.getElementById(herramientaId).classList.remove("grayscale");
      }
    }
  });
}

function obtenerIdHerramienta(sistema) {
  const mapaHerramientas = {
    Infoaduana: "infoaduana",
    Cotizador: "cotizador",
    "METODOLOGIA ECOMMERCE 3EN1": "cursos",
    "METODOLOGIA IMPORTADOR 3 EN 1": "cursos",
    Productos: "proveedores",
  };
  return mapaHerramientas[sistema] || null;
}

// Cargar tiendas del usuario
async function cargarTiendas() {
  const id_usuario = "<?php echo $_SESSION['id']; ?>";
  const formData = new FormData();
  formData.append("id_usuario", id_usuario);

  const response = await fetch(
    "<?php echo SERVERURL; ?>/suscripciones/tiendas",
    {
      method: "POST",
      body: formData,
    }
  );
  const tiendas = await response.json();

  const contenedorTiendas = document.getElementById("tiendas_contenedor");
  tiendas.forEach((tienda) => {
    const tiendaElemento = crearElementoTienda(tienda);
    contenedorTiendas.appendChild(tiendaElemento);
  });
}

function crearElementoTienda(tienda) {
  const div = document.createElement("div");
  div.id = `tienda${tienda.id_plataforma}`;
  div.className =
    "grid grid-cols-2 gap-5 items-center hover:scale-110 hover:text-blue-700 cursor-pointer";

  const iconoDiv = document.createElement("div");
  iconoDiv.className =
    "border-2 hover:border-blue-700 p-5 border-black text-center";
  const icono = document.createElement("i");
  icono.className = "fas text-2xl fa-store";
  iconoDiv.appendChild(icono);

  const nombreSpan = document.createElement("span");
  nombreSpan.className = "text-center";
  nombreSpan.textContent = tienda.nombre_tienda;

  div.appendChild(iconoDiv);
  div.appendChild(nombreSpan);

  div.addEventListener("click", () => {
    window.location.href = `https://tiendas.imporsuitpro.com/tienda/${tienda.id_plataforma}`;
  });

  return div;
}

// Configurar eventos de la página
function configurarEventos() {
  // Manejo del menú de perfil
  const profilePic = document.getElementById("profilePic");
  const menuDropdown = document.getElementById("menuDropdown");
  const logoutBtn = document.getElementById("logoutBtn");

  profilePic.addEventListener("click", (e) => {
    e.stopPropagation();
    menuDropdown.classList.toggle("hidden");
  });

  logoutBtn.addEventListener("click", () => {
    window.location.href = "<?php echo SERVERURL; ?>acceso/logout";
  });

  document.addEventListener("click", () => {
    menuDropdown.classList.add("hidden");
  });

  // Eventos para las herramientas
  configurarEventosHerramientas();
}

// Configurar eventos de las herramientas
function configurarEventosHerramientas() {
  const herramientas = [
    {
      id: "infoaduana",
      url: "https://infoaduana.imporfactory.app/newlogin?token=<?php echo $_SESSION['token']; ?>",
    },
    {
      id: "cotizador",
      url: "https://cotizador.imporfactory.app/newlogin?token=<?php echo $_SESSION['token']; ?>",
    },
    {
      id: "cursos",
      url: "https://cursos.imporfactory.app/newlogin?token=<?php echo $_SESSION['token']; ?>",
    },
    { id: "proveedores", url: "https://proveedores.imporsuitpro.com/" },
    { id: "tienda", accion: mostrarTiendas },
  ];

  herramientas.forEach((herramienta) => {
    const elemento = document.getElementById(herramienta.id);
    elemento.addEventListener("click", () => {
      if (elemento.classList.contains("grayscale")) {
        mostrarErrorSuscripcion();
      } else if (herramienta.url) {
        window.location.href = herramienta.url;
      } else if (herramienta.accion) {
        herramienta.accion();
      }
    });
  });
}

function mostrarTiendas() {
  const tiendasModal = document.getElementById("tiendas");
  tiendasModal.classList.remove("hidden");
}

function mostrarErrorSuscripcion() {
  Swal.fire({
    icon: "error",
    title:
      "No tienes una suscripción activa para este sistema, contacta a tu asesor de ventas.",
    position: "top-end",
    timer: 2000,
    toast: true,
    showConfirmButton: false,
  });
}

// Iniciar slider de imágenes
function iniciarSlider() {
  const imagenes = [
    "https://tiendas.imporsuitpro.com/imgs/herramientas/infoaduana_b.jpg",
    "https://tiendas.imporsuitpro.com/imgs/herramientas/cotizador_b.jpg",
    "https://tiendas.imporsuitpro.com/imgs/herramientas/imporlab_b.jpg",
    "https://tiendas.imporsuitpro.com/imgs/herramientas/facturacion_b.jpg",
  ];
  let sliderIndex = 0;
  const sliderContainer = document.getElementById("imagenes_slider");

  // Cargar imágenes en el slider
  imagenes.forEach((src) => {
    const img = document.createElement("img");
    img.src = src;
    img.classList.add("h-[calc(100%_/_3)]", "w-[calc(100%_/_4)]");
    sliderContainer.appendChild(img);
  });

  const posiciones = ["-ml-[0%]", "-ml-[100%]", "-ml-[200%]", "-ml-[300%]"];
  const actualizarSlider = () => {
    sliderContainer.className = `flex w-[400%] duration-300 ${posiciones[sliderIndex]}`;
  };

  // Botones de navegación
  document.getElementById("anterior").addEventListener("click", () => {
    sliderIndex = sliderIndex === 0 ? imagenes.length - 1 : sliderIndex - 1;
    actualizarSlider();
  });

  document.getElementById("siguiente").addEventListener("click", () => {
    sliderIndex = (sliderIndex + 1) % imagenes.length;
    actualizarSlider();
  });

  // Cambio automático de imágenes
  setInterval(() => {
    sliderIndex = (sliderIndex + 1) % imagenes.length;
    actualizarSlider();
  }, 3000);
}

// Configuración de SweetAlert2
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});
