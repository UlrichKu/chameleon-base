<?php

/*
 * This file is part of the Chameleon System (https://www.chameleonsystem.com).
 *
 * (c) ESONO AG (https://www.esono.de)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChameleonSystem\ExternalTrackerBundle\EventListener;

use ChameleonSystem\ShopBundle\objects\ArticleList\Event\ArticleListFilterExecutedEvent;
use ChameleonSystem\ShopBundle\objects\ArticleList\Interfaces\StateInterface;

class CategoryTriggerCmsObserverListener
{
    public function onArticleListResultGenerated(ArticleListFilterExecutedEvent $event)
    {
        if (false === $this->isCategoryEvent($event)) {
            return;
        }

        $oPkgExternalTracker = \TdbPkgExternalTrackerList::GetActiveInstance();
        $oPkgExternalTracker->AddEvent(
            \TPkgExternalTrackerState::EVENT_PKG_SHOP_PRODUCT_LIST,
            array(
                'numberOfResults' => $this->getNumberOfResults($event),
                'items' => $this->getResultItems($event),
            )
        );
    }

    private function isCategoryEvent(ArticleListFilterExecutedEvent $event)
    {
        $categoryFilterClasses = array('TShopModuleArticlelistFilterAllArticlesOfActiveCategoryTree',
                                       'TShopModuleArticlelistFilterAllArticlesOfActiveCategory',
                                       'TShopModuleArticlelistFilterAllArticlesOfActiveNonLeafCategory', );
        foreach ($categoryFilterClasses as $categoryFilterClass) {
            if ($event->getFilter() instanceof $categoryFilterClass) {
                return true;
            }
        }

        return false;
    }

    private function getResultItems(ArticleListFilterExecutedEvent $event)
    {
        return $event->getResultData()->asArray();
    }

    private function getNumberOfResults(ArticleListFilterExecutedEvent $event)
    {
        return $event->getResultData()->getTotalNumberOfResults();
    }

    private function getSearchParameters(ArticleListFilterExecutedEvent $event)
    {
        $stateQuery = $event->getState()->getState(StateInterface::QUERY, array());

        $searchParameter = array(
            \TShopModuleArticlelistFilterSearch::PARAM_QUERY => isset($stateQuery[\TShopModuleArticlelistFilterSearch::PARAM_QUERY]) ? $stateQuery[\TShopModuleArticlelistFilterSearch::PARAM_QUERY] : '',
            \TShopModuleArticlelistFilterSearch::URL_FILTER => isset($stateQuery[\TShopModuleArticlelistFilterSearch::URL_FILTER]) ? $stateQuery[\TShopModuleArticlelistFilterSearch::URL_FILTER] : array(),
        );

        return $searchParameter;
    }
}
