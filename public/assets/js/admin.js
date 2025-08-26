console.log('Admin UI listo');
function toggleAdminPassword() {
  const passwordField = document.getElementById('admin-password');
  const icon = document.querySelector('.toggle-password');
  if (!passwordField || !icon) return;
  const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordField.setAttribute('type', type);
  icon.classList.toggle('bi-eye');
  icon.classList.toggle('bi-eye-slash');
}

// Auto-ocultar mensajes de error
window.addEventListener('DOMContentLoaded', () => {
  const errorMsg = document.getElementById('error-msg');
  if (errorMsg) {
    setTimeout(() => { errorMsg.style.opacity = '0'; setTimeout(() => errorMsg.remove(), 1000); }, 4000);
  }
});


// Toggle sidebar (móvil)
document.querySelector('[data-action="toggle-sidebar"]')?.addEventListener('click', () => {
  document.querySelector('.sidebar')?.classList.toggle('active');
});

// Chart.js: donut de roles
(function () {
  const el = document.getElementById('graficoRoles');
  if (!el || typeof Chart === 'undefined') return;

  const admins = Number(el.dataset.admins || 0);
  const empleados = Number(el.dataset.empleados || 0);
  const cajeros = Number(el.dataset.cajeros || 0);

  const ctx = el.getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Administradores', 'Empleados', 'Cajeros'],
      datasets: [{
        data: [admins, empleados, cajeros]
      }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
  });
})();
