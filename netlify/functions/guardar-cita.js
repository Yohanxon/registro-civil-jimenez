// netlify/functions/guardar-cita.js
const { createClient } = require('@supabase/supabase-js');

// ====== TUS CREDENCIALES DE SUPABASE ======
const SUPABASE_URL = 'https://eglgvdjbpwdbodtkmjqf.supabase.co';
const SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVnbGd2ZGpicHdkYm9kdGttanFmIiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODc3NzA5NTQsImV4cCI6MjEwMzM0Njk1NH0.wWDe8_fFfBsZK81QyyVYAuqYbMKPScIjlSI21CpqLiI';
// ==========================================

exports.handler = async (event, context) => {
    // Configurar CORS
    const headers = {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Headers': 'Content-Type',
        'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
        'Content-Type': 'application/json'
    };

    // Responder a OPTIONS (pre-flight)
    if (event.httpMethod === 'OPTIONS') {
        return { statusCode: 200, headers };
    }

    try {
        // Conectar a Supabase con tus credenciales
        const supabase = createClient(SUPABASE_URL, SUPABASE_KEY);

        // Obtener datos del body
        const datos = JSON.parse(event.body);

        // Validar datos
        if (!datos.nombre || !datos.email || !datos.telefono || !datos.tipo_acta || !datos.fecha || !datos.hora) {
            return {
                statusCode: 400,
                headers,
                body: JSON.stringify({ 
                    exito: false, 
                    mensaje: 'Todos los campos son obligatorios' 
                })
            };
        }

        // Guardar en Supabase
        const { data, error } = await supabase
            .from('citas')
            .insert([{
                nombre_completo: datos.nombre,
                email: datos.email,
                telefono: datos.telefono,
                tipo_acta: datos.tipo_acta,
                fecha: datos.fecha,
                hora: datos.hora,
                notas: datos.notas || '',
                estado: 'pendiente'
            }]);

        if (error) {
            console.error('Error Supabase:', error);
            return {
                statusCode: 500,
                headers,
                body: JSON.stringify({ 
                    exito: false, 
                    mensaje: 'Error al guardar: ' + error.message 
                })
            };
        }

        return {
            statusCode: 200,
            headers,
            body: JSON.stringify({ 
                exito: true, 
                mensaje: 'Cita guardada correctamente',
                data: data
            })
        };

    } catch (error) {
        console.error('Error general:', error);
        return {
            statusCode: 500,
            headers,
            body: JSON.stringify({ 
                exito: false, 
                mensaje: 'Error del servidor: ' + error.message 
            })
        };
    }
};