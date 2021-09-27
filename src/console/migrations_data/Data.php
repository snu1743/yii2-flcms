<?php

namespace console\migrations_data;


class Data
{
    /**
     * @param string $path
     * @return string
     */
    public static function get(string $path): string
    {
        return file_get_contents(__DIR__ . "/$path");
    }
}