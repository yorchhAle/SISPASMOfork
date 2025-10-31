/*
 * Asigna un listener de evento 'input' al campo de calificación.
    Limitar el valor de la calificación de manera reactiva para que siempre se mantenga en el rango de 0 a 100. 
    Si el valor es menor a 0, lo establece en 0, y si es mayor a 100, lo establece en 100.
 */
const inputCalif = document.getElementById('calificacion');
        inputCalif?.addEventListener('input', () => {
            const v = parseFloat(inputCalif.value);
            if (isNaN(v)) return;
            if (v < 0) inputCalif.value = 0;
            if (v > 100) inputCalif.value = 100;
        });