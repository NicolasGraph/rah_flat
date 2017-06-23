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
 * Main plugin class.
 *
 * @internal
 */

class Oui_Flat
{
    /**
     * Constructor.
     */

    public function __construct()
    {
        global $event;

        add_privs('prefs.oui_flat', '1');
        add_privs('prefs.oui_flat_variables', '1');
        register_callback(array($this, 'install'), 'plugin_lifecycle.oui_flat', 'installed');
        register_callback(array($this, 'uninstall'), 'plugin_lifecycle.oui_flat', 'deleted');

        if (get_pref('oui_flat_path')) {
            new Oui_Flat_Import_Prefs('prefs');
            new Oui_Flat_Import_Variables('variables');
            new Oui_Flat_Import_Sections('sections');
            new Oui_Flat_Import_Pages('pages');
            new Oui_Flat_Import_Forms('forms');
            new Oui_Flat_Import_Styles('styles');
            new Oui_Flat_Import_Textpacks('textpacks');

            register_callback(array($this, 'endpoint'), 'textpattern');
            register_callback(array($this, 'initWrite'), 'oui_flat.import');

            if (in_list(get_pref('production_status'), get_pref('oui_flat_status')) && $event !== 'plugin') {
                register_callback(array($this, 'callbackHandler'), 'textpattern');
                register_callback(array($this, 'callbackHandler'), 'admin_side', 'body_end');
            }
            
            register_callback(array($this, 'setVariables'), 'textpattern');
        }
    }

    /**
     * Installer.
     */

    public function install()
    {
        $position = 250;

        $options = array(
            'oui_flat_path'   => array('text_input', '../../src/templates'),
            'oui_flat_key'    => array('text_input', md5(uniqid(mt_rand(), true))),
            'oui_flat_status' => array('oui_flat_status', 'debug, testing'),
        );

        foreach ($options as $name => $val) {
            if (get_pref($name, false) === false) {
                set_pref($name, $val[1], 'oui_flat', PREF_PLUGIN, $val[0], $position);
            }

            $position++;
        }
    }

    /**
     * Uninstaller.
     */

    public function uninstall()
    {
        remove_pref(null, 'oui_flat');
        remove_pref(null, 'oui_flat_variables');
    }

    /**
     * Initializes the importers.
     */

    public function initWrite()
    {
        callback_event('oui_flat.import_to_database');
    }

    /**
     * Initializes template variables.
     */

    public function setVariables()
    {
        global $prefs, $variable;

        $prefix = 'oui_flat_variable_';
        $offset = strlen($prefix);

        foreach ($prefs as $name => $value) {
            if (strpos($name, $prefix) === 0) {
                $variable[substr($name, $offset)] = $value;
            }
        }
    }

    /**
     * Registered callback handler.
     */

    public function callbackHandler()
    {
        try {
            callback_event('oui_flat.import');
        } catch (Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    /**
     * Import endpoint.
     */

    public function endpoint()
    {
        if (!get_pref('oui_flat_key') || get_pref('oui_flat_key') !== gps('oui_flat_key')) {
            return;
        }

        header('Content-Type: application/json; charset=utf-8');

        try {
            callback_event('oui_flat.import');
        } catch (Exception $e) {
            txp_status_header('500 Internal Server Error');

            die(json_encode(array(
                'success' => false,
                'error'   => $e->getMessage(),
            )));
        }

        update_lastmod();

        die(json_encode(array(
            'success' => true,
        )));
    }
}

/**
 * Set oui_flat_status pref
 */
function oui_flat_status($name, $val)
{
     $vals = array(
        'debug'          => gTxt('production_debug'),
        'testing'        => gTxt('production_test'),
        'debug, testing' => gTxt('production_debug').', '.lcfirst(gTxt('production_test')),
    );
    return selectInput($name, $vals, $val, true, '', $name);
}

new Oui_Flat();
