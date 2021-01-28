<?php

/*
 * This file is part of the Chameleon System (https://www.chameleonsystem.com).
 *
 * (c) ESONO AG (https://www.esono.de)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChameleonSystem\CoreBundle\Service;

/**
 * @deprecated since 7.0.12 - the breadcrumb in the backend is now a true breadcrumb (BreadcrumbBackendModule)
 */
interface BackendBreadcrumbServiceInterface
{
    public function getBreadcrumb(): ?\TCMSURLHistory;
}
