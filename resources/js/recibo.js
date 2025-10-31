/*
 * Script para manejar la apertura y cierre de modales o elementos ocultos.
    Define dos listeners de eventos globales basados en atributos de datos:
    [data-open]: Al hacer clic, toma el valor del atributo (que debe ser un selector CSS) y establece el estilo 
    'display' del elemento objetivo a 'flex' (mostrar).
*/
document.querySelectorAll('[data-open]').forEach(btn => {
    btn.addEventListener('click', () => {
        const sel = btn.getAttribute('data-open');
        const el = document.querySelector(sel);
        if (el) el.style.display = 'flex';
    });
});

/* [data-close]: Al hacer clic, toma el valor del atributo y establece el estilo 'display' del elemento objetivo a 
    'none' (ocultar). */
document.querySelectorAll('[data-close]').forEach(btn => {
    btn.addEventListener('click', () => {
        const sel = btn.getAttribute('data-close');
        const el = document.querySelector(sel);
        if (el) el.style.display = 'none';
    });
});