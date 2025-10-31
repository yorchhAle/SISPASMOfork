/*
 * Script de validación en el lado del cliente para el formulario de Ficha Médica.
    Interceptar el envío del formulario, realizar validaciones específicas (campos) de contacto obligatorios y 
    campos de teléfono solo numéricos) y mostrar mensajes de error antes de permitir el envío al servidor.
*/
console.log('✅ fichaValidacion.js cargado correctamente');

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.gm-form');
    if (!form) return;

    /* Lógica de validación antes de enviar el formulario. */
    form.addEventListener('submit', function(event) {
        event.preventDefault(); 
        /* Asumimos que el formulario es válido al inicio */
        let isValid = true; 

        /* Limpiar errores anteriores de JS. */
        document.querySelectorAll('.gm-error-message-js').forEach(el => el.remove());

        /* Validación, campos obligatorios. */
        const contactFields = [
            { name: 'contacto[nombre]', label: 'Nombre del contacto' },
            { name: 'contacto[apellidos]', label: 'Apellidos del contacto' },
            { name: 'contacto[domicilio]', label: 'Domicilio del contacto' },
            { name: 'contacto[telefono]', label: 'Teléfono del contacto' },
            { name: 'contacto[parentesco]', label: 'Parentesco del contacto' },
            { name: 'contacto[institucion]', label: 'Institución del contacto' },
        ];

        contactFields.forEach(item => {
            const field = form.querySelector(`[name="${item.name}"]`);
            if (!field.value.trim()) {
                isValid = false;
                showError(field, `El campo "${item.label}" es obligatorio.`);
            }
        });

        /* Validación, teléfonos númericos. */
        const phoneFields = [
            { name: 'enfermedades[telefono_medico]', label: 'Teléfono del médico' },
            { name: 'contacto[telefono]', label: 'Teléfono del contacto' },
        ];
        
        const numericRegex = /^[0-9]+$/;

        phoneFields.forEach(item => {
            const field = form.querySelector(`[name="${item.name}"]`);
            if (field.value.trim() && !numericRegex.test(field.value.trim())) {
                isValid = false;
                showError(field, `El campo "${item.label}" solo debe contener números.`);
            }
        });

        /* Manejo de errores. */
        if (isValid) {
            /* Si todo es válido, enviar el formulario. */
            form.submit(); 
        } else {
            /* Si hay errores, hacer scroll hacia el primer campo con error. */
            const firstErrorField = form.querySelector('.has-error');
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    /*
     * Función para mostrar un mensaje de error debajo de un campo.
    */
    function showError(field, message) {
        /* Añadir clase de error al contenedor del campo para resaltarlo (opcional). */
        const fieldContainer = field.closest('div');
        if (fieldContainer) {
            fieldContainer.classList.add('has-error');
        }

        /* Crear y mostrar el mensaje de error. */
        const errorElement = document.createElement('span');

        /* Añadimos una clase extra para JS para poder eliminarlo después */
        errorElement.className = 'gm-error-message gm-error-message-js'; 
        errorElement.textContent = message;
        field.insertAdjacentElement('afterend', errorElement);
    }
});