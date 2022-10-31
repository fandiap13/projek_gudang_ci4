<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'filterAdmin' => \App\Filters\FilterAdmin::class,
        'filterKasir' => \App\Filters\FilterKasir::class,
        'filterGudang' => \App\Filters\FilterGudang::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            'csrf',
            // 'invalidchars',

            // jika belum login hanya dapat mengakses
            'filterAdmin' => [
                'except' => [
                    'login/*', 'login', '/',
                ]
            ],
            'filterKasir' => [
                'except' => [
                    'login/*', 'login', '/',
                ]
            ],
            'filterGudang' => [
                'except' => [
                    'login/*', 'login', '/',
                ]
            ],
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
            // jika sudah login dapat mengakses
            'filterAdmin' => [
                'except' => [
                    'main/*',
                    'login/keluar',
                    'satuan/*',
                    'kategori/*',
                    'barang/*',
                    'barangmasuk/*',
                    'barangkeluar/*',
                    'pelanggan/*',
                    'laporan/*',
                    'utility/*'
                ]
            ],
            'filterKasir' => [
                'except' => [
                    'main/*',
                    'login/keluar',
                    'barangkeluar/*',
                    'pelanggan/*'
                ]
            ],
            'filterGudang' => [
                'except' => [
                    'main/*',
                    'login/keluar',
                    'barangmasuk/*',
                    'barangkeluar/*',
                    'pelanggan/*'
                ]
            ],
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [];
}
