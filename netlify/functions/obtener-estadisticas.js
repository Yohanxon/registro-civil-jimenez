// netlify/functions/obtener-estadisticas.js
const { createClient } = require('@supabase/supabase-js');

// ====== TUS CREDENCIALES DE SUPABASE ======
const SUPABASE_URL = 'https://eglgvdjbpwdbodtkmjqf.supabase.co';
const SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVnbGd2ZGpicHdkYm9kdGttanFmIiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODc3NzA5NTQsImV4cCI6MjEwMzM0Njk1NH0.wWDe8_fFfBsZK81QyyVYAuqYbMKPScIjlSI21CpqLiI';
// ==========================================

exports.handler = async (event, context) => {
    const headers = {
        'Access-Control-Allow-Origin': '*',
        'Content-Type': 'application/json'
    };

    if (event.httpMethod === 'OPTIONS') {
        return { statusCode: 200, headers };
    }

    try {
        const supabase = createClient(SUPABASE_URL, SUPABASE_KEY);

        // Obtener total de citas
        const { count: total, error: errorTotal } = await supabase
            .from('citas')
            .select('*', { count: 'exact', head: true });

        // Obtener pendientes
        const { count: pendientes, error: errorPend } = await supabase
            .from('citas')
            .select('*', { count: 'exact', head: true })
            .eq('estado', 'pendiente');

        // Obtener confirmadas
        const { count: confirmadas, error: errorConf } = await supabase
            .from('citas')
            .select('*', { count: 'exact', head: true })
            .eq('estado', 'confirmada');

        // Obtener canceladas
        const { count: canceladas, error: errorCan } = await supabase
            .from('citas')
            .select('*', { count: 'exact', head: true })
            .eq('estado', 'cancelada');

        if (errorTotal || errorPend || errorConf || errorCan) {
            return {
                statusCode: 500,
                headers,
                body: JSON.stringify({
                    total: 0,
                    pendientes: 0,
                    confirmadas: 0,
                    canceladas: 0
                })
            };
        }

        return {
            statusCode: 200,
            headers,
            body: JSON.stringify({
                total: total || 0,
                pendientes: pendientes || 0,
                confirmadas: confirmadas || 0,
                canceladas: canceladas || 0
            })
        };

    } catch (error) {
        return {
            statusCode: 500,
            headers,
            body: JSON.stringify({
                total: 0,
                pendientes: 0,
                confirmadas: 0,
                canceladas: 0
            })
        };
    }
};