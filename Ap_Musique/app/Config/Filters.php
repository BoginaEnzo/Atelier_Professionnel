<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'auth' => \App\Filters\AuthFilter::class,
        // autres filtres
    ];

    public $globals = [
        'before' => [
            // liste des filtres globaux
        ],
        'after' => [
            // liste des filtres après la requête
        ],
    ];

    public $methods = [];

    public $filters = [
        'auth' => ['before' => ['/', 'evenements', 'evenements/*']],
        'admin' => ['before' => ['admin/*']],
    ];



}
