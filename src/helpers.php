<?php

function is_proxy_format(string $proxy): bool
{
    if (preg_match('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\:(\d{1,5})$/', $proxy)) {
        return true;
    }

    return false;
}

function parse_proxy_string_to_array(string $proxy): array
{
    $split = explode(':', $proxy);

    return [
        'ip' => $split[0],
        'port' => $split[1],
        'protocol' => 'http',
        'created_at' => now()->format('Y-m-d H:i:s'),
        'updated_at' => now()->format('Y-m-d H:i:s'),
    ];
}
