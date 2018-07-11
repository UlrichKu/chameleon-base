<?php

/*
 * This file is part of the Chameleon System (https://www.chameleonsystem.com).
 *
 * (c) ESONO AG (https://www.esono.de)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ChameleonSystem\CoreBundle\Service\ActivePageServiceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * SINGLETON holding information about the current page. Use only if you are
 * making use of the template engine
 * this class definition will be preloaded in TUserController, so you can just use the
 * GetInstance method without having to load the class definition again.
/**/
class TCMSActivePage extends TdbCmsTplPage
{
    /**
     * returns the language id for the current page.
     *
     * @return string|int
     *
     * @deprecated since 6.0.0 - use \ChameleonSystem\CoreBundle\ServiceLocator::get('chameleon_system_core.language_service')->getActiveLanguageId() instead
     */
    public function GetActiveLanguage()
    {
        return self::getLanguageService()->getActiveLanguageId();
    }

    /**
     * returns the language db object of the current page/division/portal language.
     *
     * @return TdbCmsLanguage
     *
     * @deprecated since 6.0.0 - use \ChameleonSystem\CoreBundle\ServiceLocator::get('chameleon_system_core.language_service')->getActiveLanguage() instead
     */
    public function GetActiveLanguageObject()
    {
        return self::getLanguageService()->getActiveLanguage();
    }

    /**
     * returns pointer to singleton of the active page.
     *
     * @deprecated
     *
     * @return TCMSActivePage
     */
    public static function GetInstanceWithoutCache()
    {
        static $oPage;
        if (!$oPage) {
            $oPage = new self();
        }

        return $oPage;
    }

    /**
     * returns a singleton of the active page.
     *
     * @param bool $bReload
     *
     * @return TCMSActivePage
     *
     * @deprecated - use \ChameleonSystem\CoreBundle\ServiceLocator::get('chameleon_system_core.active_page_service')->getActivePage() instead
     */
    public static function GetInstance($bReload = false)
    {
        return self::getActivePageService()->getActivePage($bReload);
    }

    /**
     * returns the link to the current page including all current parameters.
     *
     * @var array  $aAdditionalParameters additional parameters will be added to the exclude parameter list. Note that
     *             a pagedef parameter in this list will never be appended to the returned URL.
     *             Possible input: real array (array("level1"=>array("level2"=>"value"))). This will delete all url array parameters with key level1.
     *             string array (Myarray[level1][level2]). This will delete only url array parameter level2.
     * @var array  $aExcludeParameters
     * @var bool   $bUseFullURL - adds http to url if true
     * @var string $sLanguageIsoName
     *
     * @return string
     *
     * @deprecated since 6.1.0 - use chameleon_system_core.active_page_service::getLinkToActivePage*() instead.
     */
    public function GetRealURL($aAdditionalParameters = array(), $aExcludeParameters = array(), $bUseFullURL = false, $sLanguageIsoName = '')
    {
        $language = null;
        if (!empty($sLanguageIsoName)) {
            $language = self::getLanguageService()->getLanguageFromIsoCode($sLanguageIsoName);
        }
        if ($bUseFullURL) {
            $request = $this->getCurrentRequest();
            $isSecure = null === $request ? false : $request->isSecure();

            return self::getActivePageService()->getLinkToActivePageAbsolute($aAdditionalParameters, $aExcludeParameters, $language, $isSecure);
        } else {
            return self::getActivePageService()->getLinkToActivePageRelative($aAdditionalParameters, $aExcludeParameters, $language);
        }
    }

    /**
     * returns the real url to the current page WITHOUT any parameters aside from
     * the paramters you add via $aAdditionalParameters.
     *
     * @param array  $aAdditionalParameters
     * @param bool   $bUseFullURL
     * @param string $sLanguageIsoName
     *
     * @return string
     */
    public function GetRealURLPlain($aAdditionalParameters = array(), $bUseFullURL = false, $sLanguageIsoName = '')
    {
        $oGlobal = TGlobal::instance();
        $aExcludes = array_keys($oGlobal->GetUserData());

        return $this->GetRealURL($aAdditionalParameters, $aExcludes, $bUseFullURL, $sLanguageIsoName);
    }

    /**
     * returns the tree node attached to the active page (based on primary tree id hidden), or null if nothing is attached.
     *
     * @return TCMSTreeNode
     */
    public function &GetTreeNode()
    {
        $sCacheName = 'treeNode';
        $oTreeNode = &$this->GetFromInternalCache($sCacheName);
        if (null !== $oTreeNode) {
            return $oTreeNode;
        }

        $oTreeNode = self::getTreeService()->getById($this->fieldPrimaryTreeIdHidden);
        if (null === $oTreeNode) {
            $oTreeNode = parent::GetTreeNode();
            TTools::WriteLogEntry('Misconfigured page ID: '.$this->id.' -> missing or invalid primary_tree_id_hidden', 1, __FILE__, __LINE__);
        }
        $this->SetInternalCache($sCacheName, $oTreeNode);

        return $oTreeNode;
    }

    /**
     * for backwards compatibility only.
     *
     * @param string $name
     *
     * @return null|TdbCmsDivision|string|TdbCmsLanguage|TCMSPageBreadcrumb|TGlobalBase
     */
    public function __get($name)
    {
        switch ($name) {
            case 'sActivePageNumber':
                return $this->id;
                break;
            case 'oGlobal':
                return \ChameleonSystem\CoreBundle\ServiceLocator::get('chameleon_system_core.global');
                break;
            case 'oActivePortal':
                return \ChameleonSystem\CoreBundle\ServiceLocator::get('chameleon_system_core.portal_domain_service')->getActivePortal();
                break;
            case 'oBreadcrumb':
                return $this->getBreadcrumb();
                break;
            case 'oActiveDivision':
                return $this->getDivision();
                break;
            case 'oLanguage':
                return self::getLanguageService()->getActiveLanguage();
                break;
        }

        return null;
    }

    /**
     * @return ActivePageServiceInterface
     */
    private static function getActivePageService()
    {
        return \ChameleonSystem\CoreBundle\ServiceLocator::get('chameleon_system_core.active_page_service');
    }

    /**
     * @return Request|null
     */
    private function getCurrentRequest()
    {
        return \ChameleonSystem\CoreBundle\ServiceLocator::get('request_stack')->getCurrentRequest();
    }
}
