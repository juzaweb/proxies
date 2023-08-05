<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

return [
    'tests' => [
        /**
         * Test site url
         * Default: https://translate.google.com
         */
        'site_url' => env('PROXY_TEST_SITE_URL', 'https://translate.google.com'),

        /**
         * Test timeout in seconds
         * Default: 10 seconds
         */
        'timeout' => env('PROXY_TEST_TIMEOUT', 10),
    ],
];
