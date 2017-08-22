<?php

namespace Drupal\content_type_tool;


use Symfony\Component\Yaml\Yaml;

class FieldStorage
{

    /**
     * Field storage.
     * One field storage, multiple node fields.
     *
     * @var array
     */
    protected $fieldStorage;

    /**
     * Set field storage config.
     * Not use this method if your field storage is 'body'.
     *
     * @param string $fieldName
     * @param $fieldStorageType
     * @return string Field storage config key
     */
    public function setFieldStorage($fieldName, $fieldStorageType){

        $this->resetFieldStorage($fieldStorageType);

        $this->fieldStorage['id'] = 'node.' . $fieldName;
        $this->fieldStorage['field_name'] = $fieldName;

        return $this->getFieldStorageConfigKey();
    }

    /**
     * Get current field storage.
     *
     * @return array
     */
    public function getFieldStorage(){

        return $this->fieldStorage;
    }

    /**
     * Get current field storage config key.
     * Field storage YML file name.
     *
     * @return string
     */
    public function getFieldStorageConfigKey(){

        return 'field.storage.' . $this->fieldStorage['id'];
    }

    /**
     * Rebuild $fieldStorage attribute using
     * assigned YML file.
     *
     * @param string $fieldStorageType number_float, string_textfield
     * @return void
     */

    protected function resetFieldStorage($fieldStorageType){

        switch ($fieldStorageType){

            case 'number_float':
                $this->fieldStorage = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/storage/number_float.yml');
                break;

            case 'string_textfield':
            default:
                $this->fieldStorage = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/storage/string_textfield.yml');
        }
    }
}