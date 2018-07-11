<?php

/*
 * This file is part of the Chameleon System (https://www.chameleonsystem.com).
 *
 * (c) ESONO AG (https://www.esono.de)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChameleonSystem\MediaManager\JavascriptPlugin;

/**
 * used to return any rendered content for the media manger javascript plugin.
 */
class JavascriptPluginRenderedContent
{
    public $hasError = false;

    public $errorMessage = '';

    public $contentHtml;

    public $mediaItemName;
}
