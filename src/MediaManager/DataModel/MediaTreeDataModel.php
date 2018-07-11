<?php

/*
 * This file is part of the Chameleon System (https://www.chameleonsystem.com).
 *
 * (c) ESONO AG (https://www.esono.de)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChameleonSystem\MediaManager\DataModel;

class MediaTreeDataModel
{
    /**
     * @var MediaTreeNodeDataModel
     */
    private $rootNode;

    /**
     * @param MediaTreeNodeDataModel $rootNode
     */
    public function __construct(MediaTreeNodeDataModel $rootNode)
    {
        $this->rootNode = $rootNode;
    }

    /**
     * @return MediaTreeNodeDataModel
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }
}
