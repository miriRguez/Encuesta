<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Simple checkbox.
 */
class Gatuf_Form_Widget_RadioInput extends Gatuf_Form_Widget {
    public $choices = array ();
    /**
     * Renders the HTML of the input.
     *
     * @param string Name of the field.
     * @param array Value for the field, can be a non valid value.
     * @param array Extra attributes to add to the input form (array())
     * @param array Extra choices (array())
     * @return string The HTML string of the input.
     */
    
    public function __construct ($attrs=array()) {
        $this->choices = $attrs['choices'];
        unset ($attrs['choices']);
        parent::__construct($attrs);
    }
    
    public function render($name, $value, $extra_attrs=array(), 
                           $choices=array()) {
        $output = array();
        if ($value === null or $value == '') {
            $value = array();
        }
        $final_attrs = $this->buildAttrs($extra_attrs);
        $output[] = '<ul>';
        $choices = array_merge($this->choices, $choices);
        $i=0;
        $base_id = $final_attrs['id'];
        foreach ($choices as $option_label=>$option_value) {

            $final_attrs['id'] = $base_id.'_'.$i;
            $final_attrs['value'] = htmlspecialchars($option_value, ENT_COMPAT, 'UTF-8');
            if (in_array($option_value, $value)) {
                $final_attrs['checked'] = 'checked';
            } else {
                unset ($final_attrs['checked']);
            }
            $check_attrs = $this->buildAttrs(array('name' => $name, 
                                               'type' => 'radio'),
                                         $final_attrs);
            $rendered = new Gatuf_Template_SafeString('<input'.Gatuf_Form_Widget_Attrs($check_attrs).' />', true);
            $output[] = sprintf('<li>%s %s</li>', $rendered,
                                htmlspecialchars($option_label, ENT_COMPAT, 'UTF-8'));
            $i++;
        }
        $output[] = '</ul>';
        return new Gatuf_Template_SafeString(implode("\n", $output), true);
    }

    public function idForLabel($id) {
        if ($id) {
            $id += '_0';
        }
        return $id;
    }
}
