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
 * @author      Eugene Wissner <belka.ew@gmail.com>
 * @copyright   (C) 2014 Eugene Wissner <belka.ew@gmail.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link        http://www.noiselabs.org
 */

namespace NoiseLabs\Bundle\SmartyBundle\Tests\Extension\Fixtures;

use Symfony\Component\Translation\TranslatorInterface;

class ProjectTranslator implements TranslatorInterface
{
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return '[trans]'.$id.'[/trans]';
    }

    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return '[trans]'.$id.'[/trans]';
    }

    public function setLocale($locale)
    {
    }

    public function getLocale()
    {
    }
}
