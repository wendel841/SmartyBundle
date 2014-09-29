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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Templating\Loader\Loader;
use Symfony\Component\Templating\Storage\StringStorage;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Component\Templating\TemplateReference;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Tests\AbstractDivLayoutTest;

class FormExtensionDivLayoutTest extends AbstractDivLayoutTest
{
    private $loader;
    private $smarty;
    private $engine;
    protected $extension;

    protected function setUp()
    {
        parent::setUp();

        // Get Smarty
        $this->smarty = new \Smarty();
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
            $this->smarty,
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
        $renderer = new SmartyRenderer(
            $rendererEngine,
            $this->getMock('Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface')
        );

        $this->extension = new FormExtension($renderer);
    }

    protected function renderForm(FormView $view, array $vars = array())
    {
        return '';
    }

    protected function renderEnctype(FormView $view)
    {
        return '';
    }

    protected function renderLabel(FormView $view, $label = null, array $vars = array())
    {
        return '';
    }

    protected function renderErrors(FormView $view)
    {
        return '';
    }

    protected function renderWidget(FormView $view, array $vars = array())
    {
        return '';
    }

    protected function renderRow(FormView $view, array $vars = array())
    {
        return '';
    }

    protected function renderRest(FormView $view, array $vars = array())
    {
        return '';
    }

    protected function renderStart(FormView $view, array $vars = array())
    {
        return '';
    }

    protected function renderEnd(FormView $view, array $vars = array())
    {
        return '';
    }

    protected function setTheme(FormView $view, array $themes)
    {
    }
}

class ProjectTemplateLoader extends Loader
{
    public $templates = array();

    public function setTemplate($name, $content)
    {
        $template = new TemplateReference($name, 'smarty');
        $this->templates[$template->getLogicalName()] = $content;
    }

    public function load(TemplateReferenceInterface $template)
    {
        if (isset($this->templates[$template->getLogicalName()])) {
            $storage = new StringStorage($this->templates[$template->getLogicalName()]);

            return 'string:'.$storage->getContent();
        }

        return false;
    }

    public function isFresh(TemplateReferenceInterface $template, $time)
    {
        return false;
    }
}
