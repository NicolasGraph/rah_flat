<?php

/*
 * rah_flat - Flat templates for Textpattern CMS
 * https://github.com/gocom/rah_flat
 *
 * Copyright (C) 2017 Jukka Svahn
 *
 * This file is part of rah_flat.
 *
 * rah_flat is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, version 2.
 *
 * rah_flat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with rah_flat. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Imports variables.
 */

class Rah_Flat_Import_Variables extends Rah_Flat_Import_Base
{
    /**
     * {@inheritdoc}
     */

    public function getPanelName()
    {
        return 'prefs.rah_flat_variables';
    }

    /**
     * {@inheritdoc}
     */

    public function getTableName()
    {
        return 'txp_prefs';
    }

    /**
     * {@inheritdoc}
     */

    public function importTemplate(Rah_Flat_TemplateIterator $file)
    {
        extract(lAtts(array(
            'value'      => '',
            'type'       => 'PREF_PLUGIN',
            'html'       => 'text_input',
            'position'   => '',
            'is_private' => false,
        ), $file->getTemplateJSONContents(), false));

        $name = 'rah_flat_variable_' . $file->getTemplateName();

        set_pref($name, $value, 'rah_flat_variables', constant($type), $html, $position, $is_private);
    }

    /**
     * {@inheritdoc}
     */

    public function dropRemoved(Iterator $templates)
    {
        $sql = $names = array();

        foreach ($templates as $template) {
            $names[] = 'rah_flat_variable_' . $template->getTemplateName();
        }

        $sql[] = "event = 'rah_flat_variables'";

        if ($names) {
            $sql[] = "name not in(" . implode(',', quote_list($names)) . ")";
        }

        safe_delete($this->getTableName(), implode(' and ', $sql));
    }
}
