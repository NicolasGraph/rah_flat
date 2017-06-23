<?php

/*
 * oui_flat - Flat templates for Textpattern CMS
 * https://github.com/nicolasgraph/oui_flat
 *
 * Copyright (C) 2017 Jukka Svahn
 *
 * This file is part of oui_flat.
 *
 * oui_flat is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, version 2.
 *
 * oui_flat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with oui_flat. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Imports textpacks.
 */

class Oui_Flat_Import_Textpacks extends Oui_Flat_Import_Base
{
    /**
     * {@inheritdoc}
     */

    public function importTemplate(Oui_Flat_TemplateIterator $file)
    {
        install_textpack('#@owner oui_flat' . n . $file->getTemplateContents());
    }

    /**
     * {@inheritdoc}
     */

    public function dropRemoved(Iterator $templates)
    {
    }
}
