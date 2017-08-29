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

/**
 * TODO: add support for field types: html, plain_text
 *
 * @package Drupal\custom_content_type
 */
class CreateContentType {

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

    public function __construct() {

        $this->nodeTypeInstance = new NodeType();
        $this->entityDisplayInstance = new EntityDisplay();
    }

    /**
     * Create node type configuration.
     *
     * @param $options
     * @return string
     */
    public function setNodeType($options) {

        $nodeTypeId = $this->nodeTypeInstance->setNodeType($options);
        return $nodeTypeId;
    }

    /**
     * Add body field configuration.
     *
     * @return string
     */
    public function addFieldBody() {

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
    public function addField($options, $contentType = 'string_textfield') {

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
    public function setEntityDisplay() {

        $this->entityDisplayInstance->setEntityDisplay($this->nodeTypeInstance, $this->fieldInstanceList);
    }

    /**
     * Save all configuration of content type in
     * a compressed module stored in a public file
     * system of a drupal installation.
     *
     * Only call to method when you have all you
     * content type structure.
     *
     * @return string Compressed module file uri.
     */
    public function save() {

        $this->setConfigMap();

        // Build compressed tar.gz module with config install.
        $tempDir = sys_get_temp_dir();
        $moduleName = 'custom_post_type' . '_' . time();
        $moduleTar = $moduleName . '.tar';
        $moduleTarGz = $moduleTar . '.gz';
        $moduleTarPath = $tempDir . '/' . $moduleName . '.tar';
        $moduleTarGzPath = $moduleTarPath . '.gz';
        $modulePharData = new \PharData($moduleTarPath);

        // Build module.info.yml
        $moduleInfoFile = Yaml::parse(ContentType::resourcesPath() . '/yml/module/module.info.yml');
        $moduleInfoFile['description'] .= " {$moduleName}}";
        $modulePharData["/{$moduleName}/{$moduleName}.info.yml"] = Yaml::dump($moduleInfoFile);

        // Build config/install YML files.
        foreach ($this->configMap as $configKey => $config)
            $modulePharData["/{$moduleName}/config/install/{$configKey}.yml"] = Yaml::dump($config);

        // Build tar.gz
        $modulePharData->compress(\Phar::GZ);

        // Register file in drupal file manager. Make public.
        $moduleTarGzContent = file_get_contents($moduleTarGzPath);
        $moduleTarGzDestination = "public://$moduleTarGz";
        $moduleTarGzFile = file_save_data($moduleTarGzContent, $moduleTarGzDestination, FILE_EXISTS_RENAME);
        $moduleTarGzUri = $moduleTarGzFile->getFileUri();

        return $moduleTarGzUri;
    }

    /**
     * Build configuration map.
     *
     * @return void
     */
    protected function setConfigMap() {

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