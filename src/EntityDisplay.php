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

class EntityDisplay {

    /**
     * @var NodeType
     */
    protected $nodeType;

    /**
     * @var Field[]
     */
    protected $fieldList;

    /**
     *
     * @var array
     */
    protected $formDisplayDefault;

    /**
     *
     * @var array
     */
    protected $viewDisplayDefault;

    /**
     * @var array
     */
    protected $viewDisplayTeaser;


    /**
     *
     * @param NodeType $nodeType
     * @param $fieldList Field[]
     */
    public function setEntityDisplay(NodeType $nodeType, $fieldList) {

        $this->nodeType = $nodeType;
        $this->fieldList = $fieldList;

        $this->setFormDisplayDefault();
        $this->setViewDisplayDefault();
        $this->setViewDisplayTeaser();
    }

    public function getFormDisplayDefault() {

        return $this->formDisplayDefault;
    }

    /**
     * @return void
     */
    protected function setFormDisplayDefault() {

        $this->resetFormDisplayDefault();

        // Node type
        $this->formDisplayDefault['id'] = 'node.' . $this->nodeType->getNodeTypeId() . '.default';
        $this->formDisplayDefault['bundle'] = $this->nodeType->getNodeTypeId();
        $this->formDisplayDefault['dependencies']['config'][] = $this->nodeType->getNodeTypeConfigKey();

        // Fields
        $weightCounter = 30;
        foreach ($this->fieldList as $field) {

            $this->formDisplayDefault['dependencies']['config'][] = $field->getFieldConfigKey();
            $fieldContent = [];

            switch ($field->getField()['field_type']) {

                // TODO: check this (only float)
                case 'float':
                    $fieldContent = [
                        'weight' => ++$weightCounter,
                        'region' => 'content',
                        'third_party_settings' => [],
                        'type' => 'number',
                        'settings' => [
                            'placeholder' => ''
                        ]
                    ];
                    break;

                case 'text_with_summary':
                    $fieldContent = [
                        'weight' => ++$weightCounter,
                        'region' => 'content',
                        'third_party_settings' => [],
                        'type' => 'text_textarea_with_summary',
                        'settings' => [
                            'placeholder' => '',
                            'summary_rows' => 3,
                            'rows' => 9
                        ]
                    ];
                    break;

                case 'string':
                default:
                    $fieldContent = [
                        'weight' => ++$weightCounter,
                        'region' => 'content',
                        'third_party_settings' => [],
                        'type' => 'string_textfield',
                        'settings' => [
                            'placeholder' => '',
                            'size' => 60
                        ]
                    ];

            }

            $this->formDisplayDefault['content'][$field->getField()['field_name']] = $fieldContent;
        }
    }

    public function getFormDisplayDefaultConfigKey() {

        return 'core.entity_form_display.node.' . $this->nodeType->getNodeTypeId() . '.default';
    }

    public function getViewDisplayDefault() {

        return $this->viewDisplayDefault;
    }

    /**
     * @return void
     */
    protected function setViewDisplayDefault() {

        $this->resetViewDisplayDefault();

        // Node type
        $this->viewDisplayDefault['id'] = 'node.' . $this->nodeType->getNodeTypeId() . '.default';
        $this->viewDisplayDefault['bundle'] = $this->nodeType->getNodeTypeId();
        $this->viewDisplayDefault['dependencies']['config'][] = $this->nodeType->getNodeTypeConfigKey();

        // Fields
        $weightCounter = 100;
        foreach ($this->fieldList as $field) {

            $this->viewDisplayDefault['dependencies']['config'][] = $field->getFieldConfigKey();
            $fieldContent = [];

            switch ($field->getField()['field_type']) {

                // TODO: check this (only float)
                case 'float':
                    $fieldContent = [
                        'weight' => ++$weightCounter,
                        'region' => 'content',
                        'third_party_settings' => [],
                        'type' => 'number',
                        'settings' => [
                            'placeholder' => ''
                        ]
                    ];
                    break;

                case 'text_with_summary':
                    $fieldContent = [
                        'weight' => ++$weightCounter,
                        'label' => 'hidden',
                        'settings' => [],
                        'third_party_settings' => [],
                        'type' => 'text_default',
                        'region' => 'content',
                    ];
                    break;

                case 'string':
                default:
                    $fieldContent = [
                        'weight' => ++$weightCounter,
                        'label' => 'above',
                        'settings' => [
                            'link_to_entity' => false,
                        ],
                        'third_party_settings' => [],
                        'type' => 'string',
                        'region' => 'content',
                    ];

            }

            $this->viewDisplayDefault['content'][$field->getField()['field_name']] = $fieldContent;
        }
    }

    public function getViewDisplayDefaultConfigKey() {

        return 'core.entity_view_display.node.' . $this->nodeType->getNodeTypeId() . '.default';
    }

    public function getViewDisplayTeaser() {

        return $this->viewDisplayTeaser;
    }

    /**
     * @return void
     */
    protected function setViewDisplayTeaser() {

        $this->resetViewDisplayTeaser();

        // Node type
        $this->viewDisplayTeaser['id'] = 'node.' . $this->nodeType->getNodeTypeId() . '.teaser';
        $this->viewDisplayTeaser['bundle'] = $this->nodeType->getNodeTypeId();
        $this->viewDisplayTeaser['dependencies']['config'][] = $this->nodeType->getNodeTypeConfigKey();

        // Fields
        $weightCounter = 100;
        foreach ($this->fieldList as $field) {

            switch ($field->getField()['field_name']) {

                case 'body':
                    $fieldContent = [
                        'label' => 'hidden',
                        'type' => 'text_summary_or_trimmed',
                        'weight' => ++$weightCounter,
                        'settings' => [
                            'trim_length' => 600
                        ],
                        'third_party_settings' => [],
                        'region' => 'content',
                    ];
                    $this->viewDisplayTeaser['dependencies']['config'][] = $field->getFieldConfigKey();
                    $this->viewDisplayTeaser['content'][$field->getField()['field_name']] = $fieldContent;
                    break;
            }

        }
    }

    public function getViewDisplayTeaserConfigKey() {

        return 'core.entity_view_display.node.' . $this->nodeType->getNodeTypeId() . '.teaser';
    }

    /**
     * @return void
     */
    protected function resetFormDisplayDefault() {

        $this->formDisplayDefault = Yaml::parse(ContentType::resourcesPath() . '/yml/config/core/core.entity_form_display.node._node.default.yml');
    }

    /**
     * @return void
     */
    protected function resetViewDisplayDefault() {

        $this->viewDisplayDefault = Yaml::parse(ContentType::resourcesPath() . '/yml/config/core/core.entity_view_display.node._node.default.yml');
    }

    /**
     * @return void
     */
    protected function resetViewDisplayTeaser() {

        $this->viewDisplayTeaser = Yaml::parse(ContentType::resourcesPath() . '/yml/config/core/core.entity_view_display.node._node.teaser.yml');
    }
}