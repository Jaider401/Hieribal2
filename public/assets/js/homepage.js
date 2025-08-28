    document.addEventListener("wheel", (event) => {
    // Cantidad de desplazamiento en píxeles
    const scrollAmount = 100; 

    if (event.deltaY > 0) {
      // Scroll hacia abajo
    window.scrollBy({ top: scrollAmount, left: 0, behavior: "smooth" });
    } else {
      // Scroll hacia arriba
    window.scrollBy({ top: -scrollAmount, left: 0, behavior: "smooth" });
    }

    // Previene el scroll brusco por defecto
    event.preventDefault();
}, { passive: false });

