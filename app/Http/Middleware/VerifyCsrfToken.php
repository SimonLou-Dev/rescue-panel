<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'http://127.0.0.1:8000/tunnel',
        'http://localhost:8000/tunnel',
        'http://dev-bcfd.simon-lou.com/tunnel',
        'https://dev-bcfd.simon-lou.com/tunnel',
        'http://bcfd.simon-lou.com/tunnel',
        'https://bcfd.simon-lou.com/tunnel'
    ];
}
