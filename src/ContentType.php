<?php
/**
 * DRUPAL 8 Content type tool.
 * Copyright (C) 2017. Tarik Curto <centro.tarik@live.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 */

namespace Drupal\content_type_tool;

/**
 *
 * @package Drupal\custom_content_type
 */
class  ContentType {

    /**
     * Current resources folder module
     * system path.
     *
     * @return string
     */
    public static function resourcesPath() {

        return self::modulePath() . '/resources';
    }

    /**
     * Current folder module
     * system path.
     *
     * @return string
     */
    public static function modulePath() {

        return drupal_get_path('module', 'content_type_tool');
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public static function nameByString(string $string): string {

        $name = str_replace(['_', '.'], [' ', ' '], $string);
        $name = strtolower($name);
        $name = ucfirst($name);

        return $name;
    }
}