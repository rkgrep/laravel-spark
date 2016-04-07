<?php

namespace App\Ux\Settings;

class Tab
{
    /**
     * The tab key.
     *
     * @var string
     */
    public $key;

    /**
     * The tag's displayable name.
     *
     * @var string
     */
    public $name;

    /**
     * The view contents of the tab.
     *
     * @var string
     */
    public $view;

    /**
     * The FontAwesome icon for the tab.
     *
     * @var string
     */
    public $icon;

    /**
     * The callback that determines if the tab is displayable.
     *
     * @var callable
     */
    public $displayable;

    /**
     * Create a new tab instance.
     *
     * @param  string  $name
     * @param  string  $view
     * @param  string  $icon
     * @param  callable  $displayable
     * @return void
     */
    public function __construct($name, $view, $icon, callable $displayable = null)
    {
        $this->name = $name;
        $this->view = $view;
        $this->icon = $icon;
        $this->displayable = $displayable;
        $this->key = str_slug($this->name);
    }

    /**
     * Determine if the tab should be displayed.
     *
     * @return bool
     */
    public function displayable()
    {
        if ($this->displayable) {
            return call_user_func_array($this->displayable, func_get_args());
        }

        return true;
    }
}
