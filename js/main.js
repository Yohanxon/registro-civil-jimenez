// js/main.js - VERSIÓN NETLIFY
document.addEventListener('DOMContentLoaded', function() {
    cargarRequisitos();
    cargarConfiguracion();
});

function cargarRequisitos() {
    fetch('/.netlify/functions/obtener-requisitos')
        .then(response => response.json())
        .then(data => {
            const contenedor = document.getElementById('requisitos-lista');
            if (contenedor) {
                contenedor.innerHTML = data.map(req => `
                    <div class="requisito-item">
                        <span class="icono">${req.icono || '📄'}</span>
                        <h4>${escapeHTML(req.titulo)}</h4>
                        <p>${escapeHTML(req.descripcion)}</p>
                    </div>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error al cargar requisitos:', error);
            // Datos de respaldo
            const contenedor = document.getElementById('requisitos-lista');
            if (contenedor) {
                contenedor.innerHTML = `
                    <div class="requisito-item">
                        <span class="icono">🪪</span>
                        <h4>Cédula de Identidad</h4>
                        <p>Cédula de identidad laminada vigente</p>
                    </div>
                    <div class="requisito-item">
                        <span class="icono">📕</span>
                        <h4>Pasaporte</h4>
                        <p>Pasaporte vigente (si aplica)</p>
                    </div>
                    <div class="requisito-item">
                        <span class="icono">📜</span>
                        <h4>Acta de Nacimiento</h4>
                        <p>Acta de nacimiento original</p>
                    </div>
                    <div class="requisito-item">
                        <span class="icono">📋</span>
                        <h4>Documento Probatorio</h4>
                        <p>Documento que acredite el hecho solicitado</p>
                    </div>
                `;
            }
        });
}

function cargarConfiguracion() {
    // Datos estáticos (se cargan desde el HTML)
    // No necesitamos fetch porque ya están en el footer
}

// Función para escapar HTML (seguridad)
function escapeHTML(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}