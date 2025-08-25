// public/assets/js/validarRegistro.js
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  if (!form) return;

  const cedula    = form.querySelector("[name='cedula']");
  const nombres   = form.querySelector("[name='nombres']");
  const apellidos = form.querySelector("[name='apellidos']");
  const telefono  = form.querySelector("[name='telefono']");
  const correo    = form.querySelector("[name='correo']");
  const password  = form.querySelector("[name='password']");

  // Expresiones regulares
  const regexNombre = /^[a-zA-ZÀ-ÿ\s]+$/;            // Solo letras y espacios
  const regexNumero = /^[0-9]+$/;                    // Solo números
  const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  // Email básico

  // --- utilidades ---
  const createOrGetErrorBox = () => {
    let box = form.querySelector(".error-msg");
    if (!box) {
      box = document.createElement("div");
      box.className = "error-msg";
      form.prepend(box);
    }
    return box;
  };
  const showErrors = (msgs) => {
    const box = createOrGetErrorBox();
    box.innerHTML = msgs.map(m => `<div>• ${m}</div>`).join("");
    box.style.display = "block";
  };
  const clearErrors = () => {
    const box = form.querySelector(".error-msg");
    if (box) { box.innerHTML = ""; box.style.display = "none"; }
  };

  // --- Restricciones en vivo ---
  if (cedula) {
    cedula.setAttribute("inputmode", "numeric");
    cedula.setAttribute("autocomplete", "off");
    cedula.addEventListener("input", () => {
      // solo números y máximo 10
      cedula.value = cedula.value.replace(/\D/g, "");
      if (cedula.value.length > 10) cedula.value = cedula.value.slice(0, 10);
    });
  }

  if (telefono) {
    telefono.setAttribute("inputmode", "tel");
    telefono.addEventListener("input", () => {
      telefono.value = telefono.value.replace(/\D/g, "");
    });
  }

  // --- helpers de servidor (AJAX) ---
  async function checkField(type, value) {
    try {
      const url = `?r=check_field&type=${encodeURIComponent(type)}&value=${encodeURIComponent(value)}`;
      const res = await fetch(url, { headers: { "Accept": "application/json" } });
      if (!res.ok) return { exists: false };
      return await res.json(); // { exists: boolean }
    } catch {
      // si falla la red, no bloqueamos — validará el backend
      return { exists: false };
    }
  }

  // (Opcional) validación rápida al salir del input
  if (cedula) {
    cedula.addEventListener("blur", async () => {
      if (cedula.value.length === 10) {
        const { exists } = await checkField("cedula", cedula.value);
        if (exists) showErrors(["⚠️ La cédula ya está registrada."]);
      }
    });
  }
  if (correo) {
    correo.addEventListener("blur", async () => {
      if (regexCorreo.test(correo.value.trim())) {
        const { exists } = await checkField("correo", correo.value.trim());
        if (exists) showErrors(["⚠️ El correo ya está registrado."]);
      }
    });
  }

  // --- Validación al enviar ---
  form.addEventListener("submit", async (e) => {
    clearErrors();
    const mensajes = [];

    // Cedula
    const c = (cedula?.value || "").trim();
    if (!regexNumero.test(c)) {
      mensajes.push("La cédula debe contener solo números.");
    }
    if (c.length !== 10) {
      mensajes.push("La cédula debe tener exactamente 10 dígitos.");
    }

    // Nombres
    const n = (nombres?.value || "").trim();
    if (!regexNombre.test(n)) {
      mensajes.push("El nombre solo debe contener letras y espacios.");
    }

    // Apellidos (opcional)
    const a = (apellidos?.value || "").trim();
    if (a !== "" && !regexNombre.test(a)) {
      mensajes.push("El apellido solo debe contener letras y espacios.");
    }

    // Teléfono (opcional)
    const t = (telefono?.value || "").trim();
    if (t !== "" && !regexNumero.test(t)) {
      mensajes.push("El teléfono debe contener solo números.");
    }

    // Correo
    const mail = (correo?.value || "").trim();
    if (!regexCorreo.test(mail)) {
      mensajes.push("El correo no es válido.");
    }

    // Password
    if (!password || password.value.length < 8) {
      mensajes.push("La contraseña debe tener mínimo 8 caracteres.");
    }

    // Consultas al servidor (si no hay errores de formato)
    if (mensajes.length === 0) {
      const [cedulaRes, correoRes] = await Promise.all([
        checkField("cedula", c),
        checkField("correo", mail),
      ]);
      if (cedulaRes.exists) mensajes.push("⚠️ La cédula ya está registrada.");
      if (correoRes.exists) mensajes.push("⚠️ El correo ya está registrado.");
    }

    if (mensajes.length > 0) {
      e.preventDefault();
      showErrors(mensajes);
      // opcional: focus al primer campo con problema
      if (!regexNumero.test(c) || c.length !== 10) { cedula?.focus(); return; }
      if (!regexNombre.test(n)) { nombres?.focus(); return; }
      if (a !== "" && !regexNombre.test(a)) { apellidos?.focus(); return; }
      if (t !== "" && !regexNumero.test(t)) { telefono?.focus(); return; }
      if (!regexCorreo.test(mail)) { correo?.focus(); return; }
      if (!password || password.value.length < 8) { password?.focus(); return; }
    }
  });
});
