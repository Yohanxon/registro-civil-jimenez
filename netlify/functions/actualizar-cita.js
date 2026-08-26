// netlify/functions/actualizar-cita.js
const { createClient } = require('@supabase/supabase-js');

// ====== TUS CREDENCIALES DE SUPABASE ======
const SUPABASE_URL = 'https://eglgvdjbpwdbodtkmjqf.supabase.co';
const SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVnbGd2ZGpicHdkYm9kdGttanFmIiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODc3NzA5NTQsImV4cCI6MjEwMzM0Njk1NH0.wWDe8_fFfBsZK81QyyVYAuqYbMKPScIjlSI21CpqLiI';
// ==========================================

exports.handler = async (event, context) => {
    const headers = {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Headers': 'Content-Type',
        'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
        'Content-Type': 'application/json'
    };

    if (event.httpMethod === 'OPTIONS') {
        return { statusCode: 200, headers };
    }

    try {
        const supabase = createClient(SUPABASE_URL, SUPABASE_KEY);
        const datos = JSON.parse(event.body);
        const { id, estado } = datos;

        if (!id || !estado) {
            return {
                statusCode: 400,
                headers,
                body: JSON.stringify({ 
                    exito: false, 
                    mensaje: 'ID y estado son requeridos' 
                })
            };
        }

        const { data, error } = await supabase
            .from('citas')
            .update({ estado: estado })
            .eq('id', id);

        if (error) {
            return {
                statusCode: 500,
                headers,
                body: JSON.stringify({ 
                    exito: false, 
                    mensaje: error.message 
                })
            };
        }

        return {
            statusCode: 200,
            headers,
            body: JSON.stringify({ 
                exito: true, 
                mensaje: 'Cita actualizada correctamente' 
            })
        };

    } catch (error) {
        return {
            statusCode: 500,
            headers,
            body: JSON.stringify({ 
                exito: false, 
                mensaje: error.message 
            })
        };
    }
};