// netlify/functions/obtener-citas.js
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

        const { data, error } = await supabase
            .from('citas')
            .select('*')
            .order('fecha', { ascending: false })
            .order('hora', { ascending: false });

        if (error) {
            return {
                statusCode: 500,
                headers,
                body: JSON.stringify([])
            };
        }

        return {
            statusCode: 200,
            headers,
            body: JSON.stringify(data || [])
        };

    } catch (error) {
        return {
            statusCode: 500,
            headers,
            body: JSON.stringify([])
        };
    }
};