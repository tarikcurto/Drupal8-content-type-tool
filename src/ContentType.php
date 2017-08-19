<?php

namespace Drupal\content_type_tool;

/**
 *
 * @package Drupal\custom_content_type
 */
class  ContentType
{

    /**
     * Current folder module
     * system path.
     *
     * @return string
     */
    public static function modulePath(){

        return  drupal_get_path('module', 'content_type_tool');
    }

    /**
     * Current resources folder module
     * system path.
     *
     * @return string
     */
    public static function resourcesPath(){

        return self::modulePath() . '/resources';
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public static function nameByString(string $string) : string{

        $name = str_replace(['_', '.'], [' ', ' '], $string);
        $name = strtolower($name);
        $name = ucfirst($name);

        return $name;
    }
}