<?php

namespace ChameleonSystem\CoreBundle\DataModel;

use JsonSerializable;

class BackendTreeNodeDataModel implements JsonSerializable
{
    /**
     * @var string
     */
    private $id = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $cmsIdent = '';

    /**
     * @var BackendTreeNodeDataModel[]
     */
    private $children = [];

    /**
     * @var bool
     */
    private $childrenAjaxLoad = false;

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var bool
     */
    private $selected = false;

    /**
     * @var bool
     */
    private $disabled = false;

    /**
     * @var bool
     */
    private $opened = false;

    /**
     * Key = HTML attribute name.
     *
     * @var array
     */
    private $listAttributes = [];

    /**
     * Key = HTML attribute name.
     *
     * @var array
     */
    private $linkAttributes = [];

    /**
     * @var array
     */
    private $linkHtmlClasses = [];

    /**
     * @var array
     */
    private $listHtmlClasses = [];

    /**
     * @var string
     */
    private $connectedPageId;

    public function __construct($id, $name, $cmsIdent, $connectedPageId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cmsIdent = $cmsIdent;
        $this->listAttributes = ['cmsIdent' => $cmsIdent];
        $this->connectedPageId = $connectedPageId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCmsIdent(): string
    {
        return $this->cmsIdent;
    }

    /**
     * @return BackendTreeNodeDataModel[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param BackendTreeNodeDataModel $children
     */
    public function addChildren(BackendTreeNodeDataModel $treeNodeDataModel): void
    {
        $this->children[] = $treeNodeDataModel;
    }

    public function isChildrenAjaxLoad(): bool
    {
        return $this->childrenAjaxLoad;
    }

    public function setChildrenAjaxLoad(bool $childrenAjaxLoad): void
    {
        $this->childrenAjaxLoad = $childrenAjaxLoad;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function isSelected(): bool
    {
        return $this->selected;
    }

    public function setSelected(bool $selected): void
    {
        $this->selected = $selected;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
    }

    public function isOpened(): bool
    {
        return $this->opened;
    }

    public function setOpened(bool $opened): void
    {
        $this->opened = $opened;
    }

    public function getListAttributes(): array
    {
        return \array_merge($this->listAttributes, ['class' => \implode(' ', $this->getListHtmlClasses())]);
    }

    public function setListAttributes(array $listAttributes): void
    {
        $this->listAttributes = $listAttributes;
    }

    public function addListAttribute(string $liAttribute, string $liAttributeValue): void
    {
        $this->listAttributes[$liAttribute] = $liAttributeValue;
    }

    public function getLinkAttributes(): array
    {
        return \array_merge($this->linkAttributes, ['class' => \implode(' ', $this->getLinkHtmlClasses())]);
    }

    public function setLinkAttributes(array $linkAttributes): void
    {
        $this->linkAttributes = $linkAttributes;
    }

    public function addLinkAttribute(string $linkAttribute, string $linkAttributeValue): void
    {
        $this->linkAttributes[$linkAttribute] = $linkAttributeValue;
    }

    public function getConnectedPageId(): string
    {
        return $this->connectedPageId;
    }

    public function getLinkHtmlClasses(): array
    {
        return $this->linkHtmlClasses;
    }

    public function setLinkHtmlClasses(array $linkHtmlClasses): void
    {
        $this->linkHtmlClasses = $linkHtmlClasses;
    }

    public function addLinkHtmlClass(string $linkHtmlClass): void
    {
        $this->linkHtmlClasses[] = $linkHtmlClass;
    }

    public function getListHtmlClasses(): array
    {
        return $this->listHtmlClasses;
    }

    public function setListHtmlClasses(array $listHtmlClasses): void
    {
        $this->listHtmlClasses = $listHtmlClasses;
    }

    public function addListHtmlClass(string $listHtmlClass): void
    {
        $this->listHtmlClasses[] = $listHtmlClass;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see https://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by json_encode,
     *               which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $jsTreeItem = [
            'id' => $this->id,
            'text' => $this->name,
            'type' => $this->type,
            'state' => [
                'opened' => $this->opened,
                'disabled' => $this->disabled,
                'selected' => $this->selected,
            ],
            'li_attr' => \array_merge($this->getListAttributes()),
            'a_attr' => \array_merge($this->getLinkAttributes()),
        ];

        if ($this->isChildrenAjaxLoad()) {
            $jsTreeItem['children'] = $this->childrenAjaxLoad;
        } else {
            $jsTreeItem['children'] = $this->children;
        }

        return $jsTreeItem;
    }
}
