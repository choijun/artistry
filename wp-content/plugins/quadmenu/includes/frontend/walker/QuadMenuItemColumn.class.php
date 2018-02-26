<?php

if (!defined('ABSPATH')) {
    die('-1');
}

class QuadMenuItemColumn extends QuadMenuItem {

    protected $type = 'column';

    function init() {
        $this->item->custom_type = 'column';
    }

    function get_start_el() {

        $item_output = '';

        $this->add_item_classes();

        $this->add_item_classes_prefix();

        $this->add_item_classes_columns();

        $id = $this->get_item_id();

        $class = $this->get_item_classes();

        $item_output .= '<li' . $id . $class . '>';

        $item_output .= $this->get_title();

        return $item_output;
    }

    function add_item_classes_columns() {

        $this->item_classes = array_diff($this->item_classes, array('quadmenu-item-type-custom'));

        $this->item_classes[] = 'quadmenu-item-level-' . $this->depth;

        $this->item_classes[] = 'quadmenu-item-type-' . $this->item->quadmenu;

        $this->item_classes[] = !empty($this->item->columns) && is_array($this->item->columns) ? join(' ', array_map('sanitize_html_class', $this->item->columns)) : '';
    }

    function get_title() {
        if ($this->item->title != 'Column') {
            return '<h4 class="quadmenu-title">' . $this->item->title . '</h4>';
        }
    }

    function add_dropdown_classes() {
        return false;
    }

    function add_dropdown_ul_classes() {
        return false;
    }

}