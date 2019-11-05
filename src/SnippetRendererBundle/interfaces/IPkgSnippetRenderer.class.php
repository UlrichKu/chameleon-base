<?php

/*
 * This file is part of the Chameleon System (https://www.chameleonsystem.com).
 *
 * (c) ESONO AG (https://www.esono.de)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

interface IPkgSnippetRenderer
{
    const SOURCE_TYPE_STRING = 0;
    const SOURCE_TYPE_FILE = 1;
    const SOURCE_TYPE_CMSMODULE = 2;

    /**
     * Returns a new instance. The instance uses the given string as snippet source.
     * It is possible to optionally provide it with a path to a file containing the snippet code.
     *
     * @static
     *
     * @param $sSource - the snippet source (or path to a file containing it)
     * @param int $iSourceType - set to one of self::SOURCE_TYPE_*
     *
     * @return TPkgSnippetRenderer
     */
    public static function GetNewInstance($sSource, $iSourceType = self::SOURCE_TYPE_STRING);

    /**
     * Set the snippet source.
     * It is possible to override the initially given source this way afterwards.
     *
     * @param $sSource - the snippet source
     */
    public function setSource($sSource);

    /**
     * Set the path to the snippet code.
     * It is possible to override the initially given source this way afterwards.
     *
     * @param $sPath - the path to the snippet code
     */
    public function setFilename($sPath);

    /**
     * Set a variable/block to be substituted in the snippet.
     *
     * @param $sName - variable/block name
     * @param $sValue - the string to use in place of the variable/block
     */
    public function setVar($sName, $sValue);

    /**
     * Set a variable/block content using a buffer.
     * e.g.:
     * <code>.
     *
     *   $oSnippetRenderer->setCapturedVarStart("foo");
     *   echo "bar";
     *   $oSnippetRenderer->setCapturedVarStop();
     *
     * </code>
     * After this, the variable "foo" will be set to "bar".
     *
     * The method will throw a <code>badMethodCallException</code> if it is called while another
     * similar call is already active,
     *
     * @param $sName - the variable/block name
     *
     * @throws BadMethodCallException
     */
    public function setCapturedVarStart($sName);

    /**
     * Stops the active captured variable call and writes the buffer to the variable.
     *
     * The method will throw a <code>badMethodCallException</code> if it is called while no capturing session is active.
     *
     * @throws BadMethodCallException
     */
    public function setCapturedVarStop();

    /**
     * Renders the snippet and returns the rendered content.
     *
     * @return string - the rendered content
     */
    public function render();

    public function InitializeSource($sSource, $iSourceType = self::SOURCE_TYPE_STRING);

    /**
     * clears the objects state.
     */
    public function clear();
}
