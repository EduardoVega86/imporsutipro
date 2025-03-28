const form = document.getElementById("frmBodega");
const guardarBtn = form.querySelector("button[type='submit']");
const requiredLabels = form.querySelectorAll("label .form-label, label");
let requiredInputs = [];

// Detectar inputs relacionados con campos requeridos (*)
requiredLabels.forEach(label => {
    const hasAsterisk = label.innerHTML.includes('*');
    if (hasAsterisk) {
        const input = label.closest('.mb-3')?.querySelector("input, select, textarea");
        if (input) {
            requiredInputs.push(input);
        }
    }
});

// Capturar clic aún si el botón está deshabilitado
guardarBtn.parentElement.addEventListener("click", function (e) {
    if (guardarBtn.disabled) {
        e.preventDefault();

        const faltantes = [];

        requiredInputs.forEach(input => {
            if (input.disabled) return;
            const isSelectInvalid = input.tagName === 'SELECT' && input.value === "0";
            const isEmpty = input.value.trim() === "";
            if (isSelectInvalid || isEmpty) {
                const label = input.closest('.mb-3')?.querySelector("label.form-label")?.textContent || 'Campo requerido';
                faltantes.push(label.trim());
            }
        });

        if (faltantes.length > 0) {
            Swal.fire({
                icon: "warning",
                title: "Campos incompletos",
                html: `Por favor completa los siguientes campos:<br><ul style="text-align: left;">${faltantes.map(f => `<li>${f}</li>`).join('')}</ul>`,
            });
        }
    }
});

// Validación automática para activar el botón si todo está lleno
const validarCampos = () => {
    let allValid = true;

    requiredInputs.forEach(input => {
        if (input.disabled) return;
        if (input.tagName === 'SELECT' && input.value === "0") {
            allValid = false;
        } else if (input.value.trim() === "") {
            allValid = false;
        }
    });

    guardarBtn.disabled = !allValid;
};

requiredInputs.forEach(input => {
    input.addEventListener("input", validarCampos);
    input.addEventListener("change", validarCampos);
});

//validar 3 segunda desde el inicio

const validarCampos2 = () => {
    let allValid = true;

    requiredInputs.forEach(input => {
        if (input.disabled) return;
        if (input.tagName === 'SELECT' && input.value === "0") {
            allValid = false;
        } else if (input.value.trim() === "") {
            allValid = false;
        }
    });

    guardarBtn.disabled = !allValid;
}

// Agregar evento de cambio a los selects
const selects = form.querySelectorAll("select");
selects.forEach(select => {
    select.addEventListener("change", validarCampos2);
});
// Agregar evento de entrada a los inputs
const inputs = form.querySelectorAll("input");
inputs.forEach(input => {
    input.addEventListener("input", validarCampos2);
});
// Agregar evento de entrada a los textareas
const textareas = form.querySelectorAll("textarea");
textareas.forEach(textarea => {
    textarea.addEventListener("input", validarCampos2);
});
// Agregar evento de entrada a los inputs de tipo checkbox
const checkboxes = form.querySelectorAll("input[type='checkbox']");
checkboxes.forEach(checkbox => {
    checkbox.addEventListener("change", validarCampos2);
});
// Agregar evento de entrada a los inputs de tipo radio
const radios = form.querySelectorAll("input[type='radio']");
radios.forEach(radio => {
    radio.addEventListener("change", validarCampos2);
});
