<?php

namespace Drupal\content_type_tool;

use Symfony\Component\Yaml\Yaml;

class EntityDisplay
{

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
    public function setEntityDisplay(NodeType $nodeType, $fieldList){

        $this->nodeType = $nodeType;
        $this->fieldList = $fieldList;

        $this->setFormDisplayDefault();
        $this->setViewDisplayDefault();
        $this->setViewDisplayTeaser();
    }

    /**
     * @return void
     */
    protected function setFormDisplayDefault(){

        $this->resetFormDisplayDefault();

        // Node type
        $this->formDisplayDefault['id'] = 'node.' . $this->nodeType->getNodeTypeId() . '.default';
        $this->formDisplayDefault['bundle'] = $this->nodeType->getNodeTypeId();
        $this->formDisplayDefault['dependencies']['config'][] = $this->nodeType->getNodeTypeConfigKey();

        // Fields
        $weightCounter = 40;
        foreach ($this->fieldList as $field){

            $this->formDisplayDefault['dependencies']['config'][] = $field->getFieldConfigKey();

            $fieldContent = [
                'weight' => ++$weightCounter,
                'region' => 'content',
                'third_party_settings' => [],
                'settings' => [
                    'placeholder' => ''
                ]
            ];
            switch ($field->getField()['field_type']){

                case 'float':

                    $fieldContent['type'] = 'number';
                    break;

                case 'string':
                default:
                    $fieldContent['settings']['size'] = 60;
                    $fieldContent['type'] = 'string_textfield';
            }

            $this->formDisplayDefault['content'][$field->getField()['field_name']] = $fieldContent;
        }
    }

    public function getFormDisplayDefault(){

        return $this->formDisplayDefault;
    }

    public function getFormDisplayDefaultConfigKey(){

        return 'core.entity_form_display.node.' . $this->nodeType->getNodeTypeId() . '.default';
    }

    /**
     * @return void
     */
    protected function resetFormDisplayDefault(){

        $this->formDisplayDefault = Yaml::parse(ContentType::resourcesPath() . '/yml/config/core/core.entity_form_display.node._node.default.yml');
    }

    /**
     * @return void
     */
    protected function setViewDisplayDefault(){

        $this->resetViewDisplayDefault();

        // Node type
        $this->viewDisplayDefault['id'] = 'node.' . $this->nodeType->getNodeTypeId() . '.default';
        $this->viewDisplayDefault['bundle'] = $this->nodeType->getNodeTypeId();
        $this->viewDisplayDefault['dependencies']['config'][] = $this->nodeType->getNodeTypeConfigKey();

        // Fields
    }

    public function getViewDisplayDefault(){

        return $this->viewDisplayDefault;
    }

    public function getViewDisplayDefaultConfigKey(){

        return 'core.entity_view_display.node.' . $this->nodeType->getNodeTypeId() . '.default';
    }

    /**
     * @return void
     */
    protected function resetViewDisplayDefault(){

        $this->viewDisplayDefault = Yaml::parse(ContentType::resourcesPath() . '/yml/config/core/core.entity_view_display.node._node.default.yml');
    }

    /**
     * @return void
     */
    protected function setViewDisplayTeaser(){

        $this->resetViewDisplayTeaser();

        // Node type
        $this->viewDisplayTeaser['id'] = 'node.' . $this->nodeType->getNodeTypeId() . '.teaser';
        $this->viewDisplayTeaser['bundle'] = $this->nodeType->getNodeTypeId();
        $this->viewDisplayTeaser['dependencies']['config'][] = $this->nodeType->getNodeTypeConfigKey();

        // Fields
    }

    public function getViewDisplayTeaser(){

        return $this->viewDisplayTeaser;
    }

    public function getViewDisplayTeaserConfigKey(){

        return 'core.entity_view_display.node.' . $this->nodeType->getNodeTypeId() . '.teaser';
    }

    /**
     * @return void
     */
    protected function resetViewDisplayTeaser(){

        $this->viewDisplayTeaser = Yaml::parse(ContentType::resourcesPath() . '/yml/config/core/core.entity_view_display.node._node.teaser.yml');
    }
}