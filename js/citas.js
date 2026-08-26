// js/citas.js - VERSIÓN NETLIFY
document.getElementById('formCita').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const mensajeDiv = document.getElementById('mensaje');
    mensajeDiv.innerHTML = '<div class="alert-success">⏳ Enviando solicitud...</div>';
    
    const datos = {
        nombre: document.getElementById('nombre').value,
        email: document.getElementById('email').value,
        telefono: document.getElementById('telefono').value,
        tipo_acta: document.getElementById('tipo_acta').value,
        fecha: document.getElementById('fecha').value,
        hora: document.getElementById('hora').value,
        notas: document.getElementById('notas').value || ''
    };
    
    // Usar la función de Netlify
    fetch('/.netlify/functions/guardar-cita', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            mensajeDiv.innerHTML = `
                <div class="alert-success">
                    ✅ ¡Cita solicitada con éxito! Te contactaremos pronto para confirmar.
                </div>
            `;
            document.getElementById('formCita').reset();
            // Scroll al mensaje
            mensajeDiv.scrollIntoView({ behavior: 'smooth' });
        } else {
            mensajeDiv.innerHTML = `
                <div class="alert-error">
                    ❌ Error: ${data.mensaje || 'Ocurrió un error'}
                </div>
            `;
        }
    })
    .catch(error => {
        mensajeDiv.innerHTML = `
            <div class="alert-error">
                ❌ Error de conexión. Verifica tu internet e intenta de nuevo.
            </div>
        `;
        console.error('Error:', error);
    });
});