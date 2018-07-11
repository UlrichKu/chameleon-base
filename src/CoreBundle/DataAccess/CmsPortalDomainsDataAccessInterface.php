<?php

/*
 * This file is part of the Chameleon System (https://www.chameleonsystem.com).
 *
 * (c) ESONO AG (https://www.esono.de)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChameleonSystem\CoreBundle\DataAccess;

use TdbCmsPortalDomains;

/**
 * CmsPortalDomainsDataAccessInterface defines a service that acts as data access interface for the PortalDomainService.
 */
interface CmsPortalDomainsDataAccessInterface
{
    /**
     * Returns the primary domain object for the given $portalId and the given $languageId, or null if none was found.
     *
     * @param string $portalId
     * @param string $languageId
     *
     * @return TdbCmsPortalDomains|null
     */
    public function getPrimaryDomain($portalId, $languageId);
}
