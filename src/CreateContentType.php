<?php

namespace Drupal\content_type_tool;

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
     * Add field configuration.
     *
     * @param array $options
     * @param string $contentType
     * @return string
     */
    public function addField($options, $contentType = 'textline_plain'){

        $this->fieldInstance = new Field();
        $this->fieldStorageInstance = new FieldStorage();

        $options['node_type_id'] = $this->nodeTypeInstance->getNodeTypeId();
        $options['node_type_config_key'] = $this->nodeTypeInstance->getNodeTypeConfigKey();
        $options['storage_config_key'] = $this->fieldStorageInstance->setFieldStorage($contentType);
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
}