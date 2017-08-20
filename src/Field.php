<?php

namespace Drupal\content_type_tool;

use Symfony\Component\Yaml\Yaml;

class Field
{

    /**
     * Field node config YML structure.
     *
     * @var array
     */
    protected $field;

    /**
     * Set node field to current content type.
     *
     * @param string $fieldStorageType textline_plain, number_float
     * @param array $options [
     *  node_type_id: string required,
     *  node_type_config_key: string  required,
     *  storage_config_key: string required,
     *  field_name: string required,
     *  label: string
     * ]
     * @return string Field identifier.
     */
    public function setField($options, $fieldStorageType = 'textline_plain'){


        $this->resetField($fieldStorageType);

        $this->field['field_name']  = $options['field_name'];
        $this->field['id'] = 'node.' . $options['node_type_id'] . '.' . $this->field['field_name'];
        $this->field['bundle'] = $options['node_type_id'];
        $this->field['dependencies']['config'][] = $options['node_type_config_key'];
        $this->field['dependencies']['config'][] = $options['storage_config_key'];

        if(isset($options['label']))
            $this->field['label'] = $options['label'];

        return $this->getFieldId();
    }

    /**
     * Get current field config.
     *
     * @return array
     */
    public function getField(){

        return $this->field;
    }

    /**
     * Get current nodeField config key.
     * Node field YML file name.
     *
     * @return string Field identifier.
     */
    public function getFieldConfigKey(){

        return 'field.field.' . $this->getFieldId();
    }

    /**
     * Current field id.
     *
     * @return string
     */
    public function  getFieldId(){

        return $this->field['id'];
    }

    /**
     * Rebuild $nodeField attribute using
     * default nodeField YML file.
     *
     * @param string $fieldStorageType plain_line, float, body
     * @return void
     */
    protected function resetField($fieldStorageType = 'textline_plain')
    {

        switch ($fieldStorageType) {

            case 'number_float':
                $this->field = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field/number_float.yml');
                break;

            case 'textline_plain':
            default:
                $this->field = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field/textline_plain.yml');

        }
    }

}