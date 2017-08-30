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

class NodeType {

    /**
     * Node type config YML structure.
     *
     * @var array
     */
    protected $nodeType;

    /**
     * Get current node type config.
     *
     * @return array
     */
    public function getNodeType() {

        return $this->nodeType;
    }

    /**
     * Create node type.
     *
     * @param array $options [
     *  type: string required,
     *  name: string required
     * ]
     * @return string Node type identifier.
     */
    public function setNodeType($options) {
        $this->resetNodeType();

        $this->nodeType['type'] = $options['type'];
        $this->nodeType['name'] = $options['name'];

        return $this->nodeType['type'];
    }

    /**
     * Rebuild $nodeType attribute using
     * default nodeType YML file.
     *
     * @return void
     */
    protected function resetNodeType() {
        $this->nodeType = Yaml::parse(ContentType::resourcesPath() . '/yml/config/node/node.type._node.yml');
    }

    /**
     * Node type config.
     * Node type YML file name.
     *
     * @return string
     */
    public function getNodeTypeConfigKey() {

        return 'node.type.' . $this->getNodeTypeId();
    }

    /**
     * Node type id.
     *
     * @return string
     */
    public function getNodeTypeId() {

        return $this->nodeType["type"];
    }
}