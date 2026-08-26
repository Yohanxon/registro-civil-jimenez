// netlify/functions/admin-requisitos.js
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
        const { accion, id, titulo, descripcion, icono } = JSON.parse(event.body);

        let resultado;

        switch(accion) {
            case 'agregar':
                resultado = await supabase
                    .from('requisitos')
                    .insert([{ titulo, descripcion, icono: icono || '📄' }]);
                break;

            case 'eliminar':
                resultado = await supabase
                    .from('requisitos')
                    .delete()
                    .eq('id', id);
                break;

            case 'editar':
                resultado = await supabase
                    .from('requisitos')
                    .update({ titulo, descripcion, icono })
                    .eq('id', id);
                break;

            default:
                return {
                    statusCode: 400,
                    headers,
                    body: JSON.stringify({ exito: false, mensaje: 'Acción no válida' })
                };
        }

        if (resultado.error) {
            return {
                statusCode: 500,
                headers,
                body: JSON.stringify({ exito: false, mensaje: resultado.error.message })
            };
        }

        return {
            statusCode: 200,
            headers,
            body: JSON.stringify({ exito: true, mensaje: 'Operación exitosa' })
        };

    } catch (error) {
        return {
            statusCode: 500,
            headers,
            body: JSON.stringify({ exito: false, mensaje: error.message })
        };
    }
};