<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Cross-Origin Resource Sharing (CORS) Configuration
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
 */
class Cors extends BaseConfig
{
    /**
     * The default CORS configuration.
     *
     * @var array{
     * allowedOrigins: list<string>,
     * allowedOriginsPatterns: list<string>,
     * supportsCredentials: bool,
     * allowedHeaders: list<string>,
     * exposedHeaders: list<string>,
     * allowedMethods: list<string>,
     * maxAge: int,
     * }
     */
    public array $default = [
        /**
         * Origins for the `Access-Control-Allow-Origin` header.
         *
         * Mengizinkan origin dari Live Server frontend dan localhost
         */
        'allowedOrigins' => ['http://127.0.0.1:5500', 'http://localhost:5500', 'http://localhost:8080'],

        /**
         * Origin regex patterns for the `Access-Control-Allow-Origin` header.
         */
        'allowedOriginsPatterns' => [],

        /**
         * Weather to send the `Access-Control-Allow-Credentials` header.
         */
        'supportsCredentials' => false,

        /**
         * Set headers to allow.
         *
         * Wajib mengizinkan 'Authorization' dan 'Content-Type' agar Bearer Token
         * dan payload JSON dari Axios dapat terbaca oleh Server-Side Security.
         */
        'allowedHeaders' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'Origin'],

        /**
         * Set headers to expose.
         */
        'exposedHeaders' => [],

        /**
         * Set methods to allow.
         *
         * Sesuai instruksi soal UAS untuk operasi standar CRUD data master.
         */
        'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

        /**
         * Set how many seconds the results of a preflight request can be cached.
         */
        'maxAge' => 7200,
    ];
}