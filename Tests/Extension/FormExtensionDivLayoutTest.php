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
 * @copyright   (C) 2011-2014 Vítor Brandão <vitor@noiselabs.org>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 */

namespace NoiseLabs\Bundle\SmartyBundle\Tests\Extension;

use NoiseLabs\Bundle\SmartyBundle\SmartyEngine;
use NoiseLabs\Bundle\SmartyBundle\Form\SmartyRenderer;
use NoiseLabs\Bundle\SmartyBundle\Form\SmartyRendererEngine;
use NoiseLabs\Bundle\SmartyBundle\Extension\FormExtension;
use NoiseLabs\Bundle\SmartyBundle\Tests\Extension\Fixtures\ProjectTemplateLoader;
use NoiseLabs\Bundle\SmartyBundle\Tests\Extension\Fixtures\ProjectTranslator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Tests\AbstractDivLayoutTest;

class FormExtensionDivLayoutTest extends AbstractDivLayoutTest
{
    private $loader;
    private $renderer;
    private $engine;
    protected $extension;

    protected function setUp()
    {
        parent::setUp();

        // Get Smarty
        $smarty = new \Smarty();

        $this->loader = new ProjectTemplateLoader();

        // Get Smarty engine
        $container = new ContainerBuilder(new ParameterBag(array_merge(array(
            'kernel.bundles'          => array('SmartyBundle' => 'NoiseLabs\\Bundle\\SmartyBundle\\SmartyBundle'),
            'kernel.cache_dir'        => __DIR__,
            'kernel.compiled_classes' => array(),
            'kernel.debug'            => false,
            'kernel.environment'      => 'test',
            'kernel.name'             => 'kernel',
            'kernel.root_dir'         => __DIR__,
        ), array())));

        $smartyOptions = array(
            'caching'       => false,
            'compile_dir'   => sys_get_temp_dir().'/noiselabs-smarty-bundle-test/templates_c',
        );
        $this->engine = new SmartyEngine(
            $smarty,
            $container,
            new TemplateNameParser(),
            $this->loader,
            $smartyOptions,
            null, // global
            null // logger
        );
        $this->engine->setTemplateDir(__DIR__.'/../../Resources/views/Form');

        $rendererEngine = new SmartyRendererEngine($this->engine, array(
            'form_div_layout.html.smarty',
        ));
        $this->renderer = new SmartyRenderer(
            $rendererEngine,
            $this->getMock('Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface')
        );

        $this->extension = new FormExtension($this->renderer);

        /* Register modifiers */
        $smarty->registerPlugin('modifier', 'trans', array(new ProjectTranslator, 'trans'));
        $smarty->registerPlugin('modifier', 'humanize', array($this->extension, 'humanize'));
        $smarty->registerPlugin('modifier', 'selectedchoice', array($this->extension, 'isSelectedChoice'));
    }

    protected function renderForm(FormView $view, array $vars = array())
    {
		$blockName = 'form';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

		return $engine->renderBlock($view, $resource, $blockName, $vars);
    }

    protected function renderEnctype(FormView $view)
    {
        return $this->renderer->searchAndRenderBlock($view, 'enctype');
    }

    protected function renderLabel(FormView $view, $label = null, array $vars = array())
    {
		$blockName = 'form_label';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

        $vars += array('label' => $label);

		return $engine->renderBlock($view, $resource, $blockName, $vars);
    }

    protected function renderErrors(FormView $view)
    {
		$blockName = 'form_errors';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

		return $engine->renderBlock($view, $resource, $blockName, array());
    }

    protected function renderWidget(FormView $view, array $vars = array())
    {
        $blockName = 'form_widget';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

        return $engine->renderBlock($view, $resource, $blockName, $vars);
    }

    protected function renderRow(FormView $view, array $vars = array())
    {
		$blockName = 'form_row';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

		return $engine->renderBlock($view, $resource, $blockName, $vars);
    }

    protected function renderRest(FormView $view, array $vars = array())
    {
  		$blockName = 'form_rest';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

		return $engine->renderBlock($view, $resource, $blockName, $vars);
    }

    protected function renderStart(FormView $view, array $vars = array())
    {
  		$blockName = 'form_start';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

		return $engine->renderBlock($view, $resource, $blockName, $vars);
    }

    protected function renderEnd(FormView $view, array $vars = array())
    {
  		$blockName = 'form_end';
        $engine = $this->renderer->getEngine();

        $resource = $engine->getResourceForBlockName($view, $blockName);

		return $engine->renderBlock($view, $resource, $blockName, $vars);
    }

    protected function setTheme(FormView $view, array $themes)
    {
    }
}
