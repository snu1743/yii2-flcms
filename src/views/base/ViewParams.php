<?php


namespace fl\cms\views\base;


class ViewParams
{
    public static function set(array $data): array
    {
        $params['data'] = $data;
        $params['body'] = (isset($data['cms_page_content']['body']) && is_string($data['cms_page_content']['body'])) ? $data['cms_page_content']['body'] : '';
        return $params;
    }
}