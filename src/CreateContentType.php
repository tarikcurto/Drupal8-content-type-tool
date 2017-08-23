<?php

namespace Drupal\content_type_tool;
use Symfony\Component\Yaml\Yaml;

/**
 * TODO: add support for field types: html, plain_text
 *
 * @package Drupal\custom_content_type
 */
class CreateContentType
{

    protected $nodeTypeInstance;

    /**
     * @var Field[]
     */
    protected $fieldInstanceList;
    protected $fieldInstance;

    /**
     * @var FieldStorage[]
     */
    protected $fieldStorageInstanceList;
    protected $fieldStorageInstance;
    protected $entityDisplayInstance;
    protected $configMap;

    public function __construct()
    {

        $this->nodeTypeInstance = new NodeType();
        $this->entityDisplayInstance = new EntityDisplay();
    }

    /**
     * Create node type configuration.
     *
     * @param $options
     * @return string
     */
    public function setNodeType($options){

        $nodeTypeId = $this->nodeTypeInstance->setNodeType($options);
        return $nodeTypeId;
    }

    /**
     * Add body field configuration.
     *
     * @return string
     */
    public function addFieldBody(){

        $this->fieldInstance = new Field();

        $options = [];
        $options['node_type_id'] = $this->nodeTypeInstance->getNodeTypeId();
        $options['node_type_config_key'] = $this->nodeTypeInstance->getNodeTypeConfigKey();
        $fieldId = $this->fieldInstance->setBody($options);

        $this->fieldInstanceList[$this->fieldInstance->getFieldConfigKey()] = $this->fieldInstance;
        return $fieldId;
    }

    /**
     * Add field configuration.
     *
     * @param array $options
     * @param string $contentType
     * @return string
     */
    public function addField($options, $contentType = 'string_textfield'){

        $this->fieldInstance = new Field();
        $this->fieldStorageInstance = new FieldStorage();

        $options['node_type_id'] = $this->nodeTypeInstance->getNodeTypeId();
        $options['node_type_config_key'] = $this->nodeTypeInstance->getNodeTypeConfigKey();
        $options['storage_config_key'] = $this->fieldStorageInstance->setFieldStorage($options['field_name'], $contentType);
        $fieldId = $this->fieldInstance->setField($options, $contentType);

        $this->fieldInstanceList[$this->fieldInstance->getFieldConfigKey()] = $this->fieldInstance;
        $this->fieldStorageInstanceList[$this->fieldStorageInstance->getFieldStorageConfigKey()] = $this->fieldStorageInstance;
        return $fieldId;
    }

    /**
     * Create entity display configuration
     *
     * @return void
     */
    public function setEntityDisplay(){

        $this->entityDisplayInstance->setEntityDisplay($this->nodeTypeInstance, $this->fieldInstanceList);
    }

    /**
     * Save all configuration of content type.
     *
     * Only call to method when you have all you
     * content type structure.
     *
     * @return void
     */
    public function save(){

        $this->setConfigMap();

        foreach ($this->configMap as $configKey => $config) {
            echo PHP_EOL . PHP_EOL . $configKey . PHP_EOL . Yaml::dump($config);
        }
    }

    /**
     * Build configuration map.
     *
     * @return void
     */
    protected function setConfigMap(){

        $this->configMap[$this->nodeTypeInstance->getNodeTypeConfigKey()] = $this->nodeTypeInstance->getNodeType();
        foreach ($this->fieldInstanceList as $configKey => $fieldInstance)
            $this->configMap[$configKey] = $fieldInstance->getField();
        foreach ($this->fieldStorageInstanceList as $configKey => $fieldStorageInstance)
            $this->configMap[$configKey] = $fieldStorageInstance->getFieldStorage();
        $this->configMap[$this->entityDisplayInstance->getFormDisplayDefaultConfigKey()] = $this->entityDisplayInstance->getFormDisplayDefault();
        $this->configMap[$this->entityDisplayInstance->getViewDisplayDefaultConfigKey()] = $this->entityDisplayInstance->getViewDisplayDefault();
        $this->configMap[$this->entityDisplayInstance->getViewDisplayTeaserConfigKey()] = $this->entityDisplayInstance->getViewDisplayTeaser();
    }
}