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
     * {@inheritdoc}
     */
    public function renderBlock(FormView $view, $resource, $blockName, array $variables = array())
    {
        return $this->engine->fetchTemplateFunction($resource, $blockName, array_merge($view->vars, $variables));
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
        $this->resources[$cacheKey][$blockName] = false;
        // Check each theme whether it contains the searched block
        if (isset($this->themes[$cacheKey])) {
            for ($i = count($this->themes[$cacheKey]) - 1; $i >= 0; --$i) {
                if ($template = $this->templateFunctionExists($this->themes[$cacheKey][$i], $blockName)) {
                    $this->resources[$cacheKey][$blockName] = $template;
                    break;
                }
            }
        }

        // Check the default themes once we reach the root view without success
        if (!$this->resources[$cacheKey][$blockName]) {
            for ($i = count($this->defaultThemes) - 1; $i >= 0; --$i) {
                if ($template = $this->templateFunctionExists($this->defaultThemes[$i], $blockName)) {
                    $this->resources[$cacheKey][$blockName] = $template;
                    break;
                }
            }
        }

        return $this->resources[$cacheKey][$blockName];
    }

    /**
     * {@inheritdoc}
     */
    public function templateFunctionExists($template, $name)
    {
        $tplPrefix = 'SmartyBundle:Form:';

        if (!$template instanceof \Smarty_Internal_Template) {
            $template = $tplPrefix.$template;
            $template = $this->engine->createTemplate($template);
        }

        return (is_callable($function = 'smarty_template_function_'.$name)) ? $template: false;
    }
}
