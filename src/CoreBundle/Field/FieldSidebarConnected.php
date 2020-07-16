<?php

namespace ChameleonSystem\CoreBundle\Field;

use ChameleonSystem\CoreBundle\Bridge\Chameleon\Module\Sidebar\MenuItem;
use ChameleonSystem\CoreBundle\DataAccess\MenuItemDataAccessInterface;
use ChameleonSystem\CoreBundle\DataModel\MenuCategoryAndItem;
use ChameleonSystem\CoreBundle\ServiceLocator;

class FieldSidebarConnected extends \TCMSFieldVarchar
{
    /**
     * @var MenuItemDataAccessInterface
     */
    private $menuItemDataAccess;

    public function __construct(?MenuItemDataAccessInterface $menuItemDataAccess = null)
    {
        // TODO isVirtual?

        if (null === $menuItemDataAccess) {
            $menuItemDataAccess = ServiceLocator::get('chameleon_system_core.sidebar.menu_access');
        }
        $this->menuItemDataAccess = $menuItemDataAccess;
    }

    /**
     * {@inheritDoc}
     */
    public function GetReadOnly(){
        $menuPath = $this->getMenuPath($this->recordId);

        // TODO propery render "readonly" also without class "form-control" ?
        return sprintf('<div class="form-control form-control-sm" readonly>%s</div>', $menuPath);
    }

    private function getMenuPath(string $tableId): string
    {
        $menuEntry = $this->getMatchingEntryFromMenu($tableId);

        if (null === $menuEntry) {
            return '';
        }

        return $menuEntry->getMenuCategory()->getName().' / '.$menuEntry->getMenuItem()->getName();
    }

    private function getMatchingEntryFromMenu(string $tableId): ?MenuCategoryAndItem
    {
        $tablePointerMenuItems = $this->getMenuItemsPointingToTable();

        if (false === \array_key_exists($tableId, $tablePointerMenuItems)) {
            return null;
        }

        return $tablePointerMenuItems[$tableId];
    }

    private function getMenuItemsPointingToTable(): array
    {
        // TODO / NOTE this method also exists in BreadcrumbBackendModule

        $tableMenuItems = [];

        $menuCategories = $this->menuItemDataAccess->getMenuCategories();

        foreach ($menuCategories as $menuCategory) {
            foreach ($menuCategory->getMenuItems() as $menuItem) {
                if (null !== $menuItem->getTableId()) {
                    $tableMenuItems[$menuItem->getTableId()] = new MenuCategoryAndItem($menuCategory, $menuItem);
                }
            }
        }

        return $tableMenuItems;
    }

    /**
     * {@inheritDoc}
     */
    public function CreateFieldDefinition($returnDDL = false, $oField = null)
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function ChangeFieldDefinition($oldName, $newName, &$postData = null)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function DeleteFieldDefinition()
    {
    }
}
