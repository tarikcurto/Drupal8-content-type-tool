<?php

namespace Drupal\custom_content_type;

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
    protected $nodeFieldMap;

    /**
     * Field node config YML structure.
     *
     * @var array
     */
    protected $nodeField;

    /**
     * Create nodeType
     *
     * @param array $options [
     *  id: required string,
     *  name: required string
     * ]
     */
    public function __construct($options)
    {
        $this->resetNodeType();

        $this->nodeType['type'] = $options['type'];
        $this->nodeType['name'] = $options['name'];
    }

    /**
     * Node type id
     *
     * @return string
     */
    public function getNodeTypeId(){

        return $this->nodeType["type"];
    }

    /**
     * Add node field to current content type.
     *
     * @param array $options [
     *  id: string required,
     *  field_name: string required,
     *  label: string
     * ]
     */
    public function addNodeField($options){

        //TODO: Add Node field. Add partial content to form.
        // TODO: $nodeField['bundle'] = $this->getNodeTypeId();
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
     * Rebuild $nodeField attribute using
     * default nodeField YML file.
     *
     *
     * @param string $fieldType plain_line, float, body
     * @return void
     */
    protected function resetNodeField($fieldType = 'body')
    {

        switch ($fieldType) {

            case 'plain_line':
                $this->nodeField = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field.field.node._node._field_float.yml');
                break;

            case 'float':
                $this->nodeField = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field.field.node._node._field_plain_line.yml');
                break;

            case 'body':
            default:
                $this->nodeField = Yaml::parse(ContentType::resourcesPath() . '/yml/config/field/field.field.node._node.body.yml');
        }
    }
}