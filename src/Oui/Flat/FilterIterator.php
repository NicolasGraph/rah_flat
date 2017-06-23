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
 * Filters template iterator results.
 *
 * This class iterates over template files.
 *
 * <code>
 * $filteredTemplates = new Oui_Flat_FilterIterator(
 *    Oui_Flat_TemplateIterator('/path/to/dir')
 * );
 * </code>
 *
 * @see \RecursiveFilterIterator
 */

class Oui_Flat_FilterIterator extends RecursiveFilterIterator
{
    /**
     * {@inheritdoc}
     */

    public function accept() {
        return $this->isDir() || $this->isValidTemplate();
    }
}
