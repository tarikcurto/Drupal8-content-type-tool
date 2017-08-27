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

use Symfony\Component\Yaml\Yaml;

class Field {

    /**
     * Field node config YML structure.
     *
     * @var array
     */
    protected $field;

    /**
     * Set node field BODY to current content type.
     *
     * @param array $options [
     *  node_type_id: string required,
     *  node_type_config_key: string  required
     * ]
     * @return string Field identifier.
     */
    public function setBody($options) {

        $this->resetField('body');

        $this->field['id'] = 'node.' . $options['node_type_id'] . '.body';
        $this->field['bundle'] = $options['node_type_id'];
        $this->field['dependencies']['config'][] = $options['node_type_config_key'];

        return $this->getFieldId();
    }

    /**
     * Rebuild $nodeField attribute using
     * default nodeField YML file.
     *
     * @param string $fieldStorageType string_textfield, float, body
     * @return void
     */
    protected function resetField($fieldStorageType = 'string_textfield') {

        switch ($fieldStorageType) {

            case 'body':
                $this->field = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field/body.yml');
                break;

            case 'number_float':
                // TODO: add really support for numbers!.
                $this->field = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field/number_float.yml');
                break;

            case 'string_textfield':
            default:
                $this->field = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field/string_textfield.yml');

        }
    }

    /**
     * Current field id.
     *
     * @return string
     */
    public function getFieldId() {

        return $this->field['id'];
    }

    /**
     * Get current field config.
     *
     * @return array
     */
    public function getField() {

        return $this->field;
    }

    /**
     * Set node field to current content type.
     *
     * @param string $fieldStorageType string_textfield, number_float
     * @param array $options [
     *  node_type_id: string required,
     *  node_type_config_key: string  required,
     *  storage_config_key: string required,
     *  field_name: string required,
     *  label: string
     * ]
     * @return string Field identifier.
     */
    public function setField($options, $fieldStorageType = 'string_textfield') {


        $this->resetField($fieldStorageType);

        $this->field['field_name'] = $options['field_name'];
        $this->field['id'] = 'node.' . $options['node_type_id'] . '.' . $this->field['field_name'];
        $this->field['bundle'] = $options['node_type_id'];
        $this->field['dependencies']['config'][] = $options['node_type_config_key'];
        $this->field['dependencies']['config'][] = $options['storage_config_key'];

        if (isset($options['label']))
            $this->field['label'] = $options['label'];

        return $this->getFieldId();
    }

    /**
     * Get current nodeField config key.
     * Node field YML file name.
     *
     * @return string Field identifier.
     */
    public function getFieldConfigKey() {

        return 'field.field.' . $this->getFieldId();
    }

}