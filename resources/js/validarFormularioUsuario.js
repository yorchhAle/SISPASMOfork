/*
 * Script de validación en el lado del cliente para el formulario CRUD de Alumnos.
    Interceptar el envío del formulario (`submit`) para realizar validaciones de campos obligatorios, 
    formato de correo/teléfono y rango de edad. Maneja la lógica de opcionalidad de las contraseñas en formularios 
    de actualización (`isUpdateForm`).
*/
document.addEventListener('DOMContentLoaded', function () {
    /* Selecciona el formulario por su clase. */
    const form = document.querySelector('.gm-form');

    /* Si no hay formulario en la página, detenemos el script. */
    if (!form) {
        return;
    }

    /* Detecta si es un formulario de actualización buscando el campo _method='PUT'. */
    const isUpdateForm = form.querySelector('input[name="_method"][value="PUT"]');

    /* Escuchamos el evento 'submit' del formulario. */
    form.addEventListener('submit', function (event) {
        /* Prevenimos el envío automático para poder validar primero. */
        event.preventDefault();

        /* Limpiamos errores anteriores. */
        const errorContainer = document.querySelector('.gm-errors');
        if (errorContainer) {
            errorContainer.innerHTML = '';
        }

        let errors = [];

        /* Validación, campos que no deben estar vacíos. */
        const requiredFields = [
            'nombre', 'apellidoP', 'apellidoM', 'fecha_nac', 'usuario', 'pass',
            'pass_confirmation', 'genero', 'correo', 'telefono', 'direccion',
            'matriculaA', 'id_diplomado', 'estatus'
        ];

        requiredFields.forEach(fieldName => {
            if (isUpdateForm && (fieldName === 'pass' || fieldName === 'pass_confirmation')) {
                return; 
            }
            
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field && field.value.trim() === '') {
                const friendlyName = field.placeholder || fieldName;
                errors.push(`El campo "${friendlyName}" es obligatorio.`);
            }
        });

        /* Si es un formulario de actualización y se escribe una nueva contraseña, la confirmación se vuelve 
            obligatoria. */
        const passInput = form.querySelector('[name="pass"]');
        const passConfirmationInput = form.querySelector('[name="pass_confirmation"]');

        if (isUpdateForm && passInput && passInput.value.trim() !== '') {
            if (passConfirmationInput && passConfirmationInput.value.trim() === '') {
                errors.push('Debes confirmar la nueva contraseña.');
            }
        }

        /* Validación de correo electrónico. */
        const emailInput = form.querySelector('[name="correo"]');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailInput && emailInput.value.trim() !== '' && !emailRegex.test(emailInput.value)) {
            errors.push('El formato del correo electrónico no es válido.');
        }

        /* Validación del teléfono (10 dígitos numéricos). */
        const phoneInput = form.querySelector('[name="telefono"]');
        const phoneRegex = /^\d{10}$/;
        if (phoneInput && phoneInput.value.trim() !== '' && !phoneRegex.test(phoneInput.value)) {
            errors.push('El teléfono debe contener exactamente 10 dígitos numéricos.');
        }

        /* Validación de Fecha de Nacimiento (entre 15 y 80 años). */
        const dobInput = form.querySelector('[name="fecha_nac"]');
        if (dobInput && dobInput.value) {
            const birthDate = new Date(dobInput.value);
            const today = new Date();
            
            /* Ajusta la fecha de nacimiento para evitar problemas de zona horaria */
            const utcBirthDate = new Date(birthDate.getUTCFullYear(), birthDate.getUTCMonth(), birthDate.getUTCDate());

            let age = today.getFullYear() - utcBirthDate.getFullYear();
            const monthDifference = today.getMonth() - utcBirthDate.getMonth();
            const dayDifference = today.getDate() - utcBirthDate.getDate();

            if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                age--;
            }

            if (age < 15) {
                errors.push('La edad mínima para registrarse es de 15 años.');
            }
            if (age > 80) {
                errors.push('La edad máxima para registrarse es de 80 años.');
            }
        }
        

        /* Si hay errores, los mostramos */
        if (errors.length > 0) {
            const uniqueErrors = [...new Set(errors)]; 
            
            if (errorContainer) {
                uniqueErrors.forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorContainer.appendChild(li);
                });
            } else {
                /* Si no existe el contenedor, mostramos una alerta */
                alert(uniqueErrors.join('\n'));
            }
            window.scrollTo(0, 0);
        } else {
            form.submit();
        }
    });
});