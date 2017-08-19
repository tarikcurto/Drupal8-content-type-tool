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

    /**
     * Node type config YML structure.
     *
     * @var array
     */
    protected $nodeType;

    /**
     * Field node config YML container.
     * All config fields of current nodeType.
     *
     * [fieldId => nodeField]
     *
     * @var array[]
     */
    protected $fieldMap;

    /**
     * Field node config YML structure.
     *
     * [nodeTypeId => [ fieldId => fieldYmlFile, ...], ...]
     *
     * @var array
     */
    protected $field;

    /**
     * Field storage map
     *
     * [fieldStorageId => fieldStorage]
     *
     * @var array[]
     */
    protected $fieldStorageMap;

    /**
     * Field storage.
     * One field storage, multiple node fields.
     *
     * @var array
     */
    protected $fieldStorage;

    /**
     * Create nodeType
     *
     * @param array $options [
     *  id: required string,
     *  name: required string
     * ]
     */

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
     *
     * @var array
     */
    protected $viewDisplayTeaser;

    public function __construct($options)
    {
        $this->resetNodeType();

        $this->nodeType['type'] = $options['type'];
        $this->nodeType['name'] = $options['name'];
    }

    /**
     * Rebuild $nodeType attribute using
     * default nodeType YML file.
     *
     * @return void
     */
    protected function resetNodeType()
    {
        $this->nodeType = Yaml::parse(ContentType::resourcesPath() . '/yml/config/node/node.type._node.yml');
    }

    /**
     * Node type id.
     *
     * @return string
     */
    public function getNodeTypeId(){

        return $this->nodeType["type"];
    }

    /**
     * Node type config.
     * Node type YML file name.
     *
     * @return string
     */
    public function getNodeTypeConfigKey(){

        return 'node.type.' . $this->getNodeTypeId();
    }

    // =================================================================================================================

    /**
     * Add node field to current content type.
     *
     * @param string $fieldStorageType textline_plain, number_float
     * @param array $options [
     *  field_name: string required,
     *  label: string,
     * content_type: string [plain_line, float, body]
     * ]
     * @return string Field identifier.
     */
    public function addField($options, $fieldStorageType = 'textline_plain'){

        $this->addFieldStorage($fieldStorageType);
        $this->resetField($fieldStorageType);

        $this->field['field_name']  = $options['field_name'];
        $this->field['id'] = 'node.' . $this->getNodeTypeId() . '.' . $this->field['field_name'];
        $this->field['bundle'] = $this->getNodeTypeId();
        $this->field['dependencies']['config'][] = $this->getNodeTypeConfigKey();
        $this->field['dependencies']['config'][] = $this->getFieldStorageConfigKey();

        if(isset($options['label']))
            $this->field['label'] = $options['label'];

        $this->fieldMap[$this->getFieldConfigKey()] = $this->field;

        return $this->getFieldId();
    }

    /**
     * Get current nodeField config key.
     * Node field YML file name.
     *
     * @return string Field identifier.
     */
    protected function getFieldConfigKey(){

        return 'field.field.' . $this->field['id'];
    }

    /**
     * Current field id.
     *
     * @return string
     */
    protected function  getFieldId(){

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

    // =================================================================================================================

    /**
     * Add field storage config.
     * Not use this method if your field storage is 'body'.
     *
     * @param $fieldStorageType
     */
    public function addFieldStorage($fieldStorageType){

        if(isset($this->fieldMap['field.field.node.' . $fieldStorageType]) || $fieldStorageType == 'body')
            return;

        $this->resetFieldStorage($fieldStorageType);
        $this->fieldStorageMap[$this->getFieldStorageConfigKey()] = $this->fieldStorage;
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
     * @param string $fieldStorageType number_float, textline_plain
     * @return void
     */

    protected function resetFieldStorage($fieldStorageType){

        switch ($fieldStorageType){

            case 'number_float':
                $this->fieldStorage = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/storage/number_float.yml');
                break;

            case 'textline_plain':
            default:
                $this->fieldStorage = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/storage/textline_plain.yml');
        }
    }

    // =================================================================================================================

    public function setCoreEntity(){

        $this->setFormDisplayDefault();
        $this->setViewDisplayDefault();
        $this->setViewDisplayTeaser();

        echo json_encode(get_object_vars($this));
    }

    /**
     * @return void
     */
    protected function setFormDisplayDefault(){

        $this->resetFormDisplayDefault();

        // Base + node tpe
        $this->formDisplayDefault['id'] = 'node.' . $this->getNodeTypeId() . '.default';
        $this->formDisplayDefault['bundle'] = $this->getNodeTypeId();
        $this->formDisplayDefault['dependencies']['config'][] = $this->getNodeTypeConfigKey();

        // TODO: Fields
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

        // Base + node tpe
        $this->viewDisplayDefault['id'] = 'node.' . $this->getNodeTypeId() . '.default';
        $this->viewDisplayDefault['bundle'] = $this->getNodeTypeId();
        $this->viewDisplayDefault['dependencies']['config'][] = $this->getNodeTypeConfigKey();

        // TODO: Fields
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

        // Base + node tpe
        $this->viewDisplayTeaser['id'] = 'node.' . $this->getNodeTypeId() . '.teaser';
        $this->viewDisplayTeaser['bundle'] = $this->getNodeTypeId();
        $this->viewDisplayTeaser['dependencies']['config'][] = $this->getNodeTypeConfigKey();

        // TODO: Fields
    }

    /**
     * @return void
     */
    protected function resetViewDisplayTeaser(){

        $this->viewDisplayTeaser = Yaml::parse(ContentType::resourcesPath() . '/yml/config/core/core.entity_view_display.node._node.teaser.yml');
    }
}