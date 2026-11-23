<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credenciais do administrador
    |--------------------------------------------------------------------------
    |
    | Vêm apenas do .env. Não coloque usuário ou senha reais no código.
    | A sessão do admin usa a chave abaixo e o tempo de vida de SESSION_LIFETIME.
    |
    */

    'username' => env('ADMIN_USER'),

    'password' => env('ADMIN_PASSWORD'),

    'session_key' => 'rota_ifto_admin',

];
