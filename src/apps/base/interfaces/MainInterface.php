<?php


namespace fl\cms\apps\base\interfaces;

/**
 * Interface MainInterface
 * @package fl\cms\apps\base\interfaces
 */
interface MainInterface
{
    public static function init(string $page, array $pageData): string;

    public function process(): void;

    public function getResult(): string;
}