<?php
/**
 * This file is part of NoiseLabs-SmartyBundle
 *
 * NoiseLabs-SmartyBundle is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * NoiseLabs-SmartyBundle is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with NoiseLabs-SmartyBundle; if not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Copyright (C) 2011-2014 Vítor Brandão
 *
 * @category    NoiseLabs
 * @package     SmartyBundle
 * @author      Vítor Brandão <vitor@noiselabs.org>
 * @copyright   (C) 2011-2014 Vítor Brandão <vitor@noiselabs.org>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link        http://www.noiselabs.org
 */

namespace NoiseLabs\Bundle\SmartyBundle\Form;

use NoiseLabs\Bundle\SmartyBundle\SmartyEngine;
use Symfony\Component\Form\AbstractRendererEngine;
use Symfony\Component\Form\FormView;

/**
 * Adapter for rendering form templates with the Smarty templating engine.
 *
 * @author Vítor Brandão <vitor@noiselabs.org>
 */
class SmartyRendererEngine extends AbstractRendererEngine implements SmartyRendererEngineInterface
{
    /**
     * @var SmartyEngine
     */
    protected $engine;

    /**
     * @param SmartyEngine $engine
     * @param array $defaultThemes The default themes. The type of these themes is open to the implementation.
     */
    public function __construct(SmartyEngine $engine, array $defaultThemes = array())
    {
        $this->engine = $engine;

        parent::__construct($defaultThemes);
    }

    /**
     * @var \Smarty
     */
	private $smarty;

    /**
     * {@inheritdoc}
     */
    public function setSmarty(\Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * {@inheritdoc}
     */
    public function renderBlock(FormView $view, $resource, $blockName, array $variables = array())
    {
        $cacheKey = $view->vars[self::CACHE_KEY_VAR];

        // TODO: Move body of FormExtension::render() to here.
    }

    /**
     * Loads the cache with the resource for a given block name.
     *
     * @see getResourceForBlockName()
     *
     * @param string   $cacheKey  The cache key of the form view.
     * @param FormView $view      The form view for finding the applying themes.
     * @param string   $blockName The name of the block to load.
     *
     * @return bool    True if the resource could be loaded, false otherwise.
     */
    protected function loadResourceForBlockName($cacheKey, FormView $view, $blockName)
    {
        // The caller guarantees that $this->resources[$cacheKey][$block] is
        // not set, but it doesn't have to check whether $this->resources[$cacheKey]
        // is set. If $this->resources[$cacheKey] is set, all themes for this
        // $cacheKey are already loaded (due to the eager population, see doc comment).
        if (isset($this->resources[$cacheKey])) {
            // As said in the previous, the caller guarantees that
            // $this->resources[$cacheKey][$block] is not set. Since the themes are
            // already loaded, it can only be a non-existing block.
            $this->resources[$cacheKey][$blockName] = false;

            return false;
        }

		// TODO: Check each theme whether it contains the searched block

        // Check the default themes once we reach the root view without success
        if (!$view->parent) {
            for ($i = count($this->defaultThemes) - 1; $i >= 0; --$i) {
			}
		}
    }
}
