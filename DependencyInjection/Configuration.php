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
 * Copyright (C) 2011 Vítor Brandão
 *
 * @category    NoiseLabs
 * @package     SmartyBundle
 * @author      Vítor Brandão <noisebleed@noiselabs.org>
 * @copyright   (C) 2011 Vítor Brandão <noisebleed@noiselabs.org>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link        http://www.noiselabs.org
 * @since       0.1.0
 */

namespace NoiseLabs\Bundle\SmartyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the SmartyBundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @since  0.1.0
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 */
class Configuration implements ConfigurationInterface
{
	/**
	 * {@inheritDoc}
	 *
	 * Example configuration (YAML):
	 * <code>
	 * smarty:
	 *
	 *     # Smarty options
	 *     options:
	 *         cache_dir:     %kernel.cache_dir%/smarty/cache
	 *         compile_dir:   %kernel.cache_dir%/smarty/templates_c
	 *         config_dir:    %kernel.root_dir%/config/smarty
	 *         template_dir:  %kernel.root_dir%/Resources/views
	 *         use_sub_dirs:  true
	 * </code>
	 *
	 * @since  0.1.0
	 * @author Vítor Brandão <noisebleed@noiselabs.org>
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('smarty');

		$rootNode
			->treatNullLike(array('enabled' => true))
		->end();

		$this->addGlobalsSection($rootNode);
		$this->addSmartyOptions($rootNode);

		return $treeBuilder;
	}

	/**
	 * @since  0.1.0
	 * @author Vítor Brandão <noisebleed@noiselabs.org>
	 */
	private function addGlobalsSection(ArrayNodeDefinition $rootNode)
	{
		$rootNode
			->fixXmlConfig('global')
			->children()
				->arrayNode('globals')
					->useAttributeAsKey('key')
					->prototype('array')
						->beforeNormalization()
							->ifTrue(function($v){ return is_string($v) && '@' === substr($v, 0, 1); })
							->then(function($v){ return array('id' => substr($v, 1), 'type' => 'service'); })
						->end()
						->beforeNormalization()
							->ifTrue(function($v){
								if (is_array($v)) {
									$keys = array_keys($v);
									sort($keys);

									return $keys !== array('id', 'type') && $keys !== array('value');
								}

								return true;
							})
							->then(function($v){ return array('value' => $v); })
						->end()
						->children()
							->scalarNode('id')->end()
							->scalarNode('type')
								->validate()
									->ifNotInArray(array('service'))
									->thenInvalid('The %s type is not supported')
								->end()
							->end()
							->variableNode('value')->end()
						->end()
					->end()
				->end()
			->end()
		;
	}

	/**
	 * Smarty options.
	 *
	 * The whole list can be seen here: {@link http://www.smarty.net/docs/en/api.variables.tpl}
	 *
	 * @since  0.1.0
	 * @author Vítor Brandão <noisebleed@noiselabs.org>
	 */
	private function addSmartyOptions(ArrayNodeDefinition $rootNode)
	{
		$rootNode
			->children()
				->arrayNode('options')
					->addDefaultsIfNotSet()
					->children()
						->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/smarty/cache')->end()
						->scalarNode('compile_dir')->defaultValue('%kernel.cache_dir%/smarty/templates_c')->end()
						->scalarNode('config_dir')->defaultValue('%kernel.root_dir%/config/smarty')->end()
						->scalarNode('default_resource_type')->defaultValue('file')->end()
						->scalarNode('template_dir')->defaultValue('%kernel.root_dir%/Resources/views')->end()
						->scalarNode('use_include_path')->defaultFalse()->end()
						->scalarNode('use_sub_dirs')->defaultTrue()->end()
					->end()
				->end()
			->end()
		;
	}
}
