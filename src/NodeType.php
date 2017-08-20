<?php

namespace Drupal\content_type_tool;


use Symfony\Component\Yaml\Yaml;

class NodeType
{

    /**
     * Node type config YML structure.
     *
     * @var array
     */
    protected $nodeType;

    /**
     * Create node type.
     *
     * @param array $options [
     *  type: string required,
     *  name: string required
     * ]
     * @return string Node type identifier.
     */
    public function setNodeType($options)
    {
        $this->resetNodeType();

        $this->nodeType['type'] = $options['type'];
        $this->nodeType['name'] = $options['name'];
    }

    /**
     * Get current node type config.
     *
     * @return array
     */
    public function getNodeType(){

        return $this->nodeType;
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
}