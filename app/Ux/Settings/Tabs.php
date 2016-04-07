<?php

namespace App\Ux\Settings;

use App\Spark;

class Tabs
{
    /**
     * The settings tabs configuration.
     *
     * @var array
     */
    public $tabs = [];

    /**
     * Create a new settings tabs instance.
     *
     * @param  array  $tabs
     * @return void
     */
    public function __construct(array $tabs = [])
    {
        $this->tabs = $tabs;
    }

    /**
     * Define the settings tabs configuration.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function configure(callable $callback)
    {
        $this->tabs = array_filter(call_user_func($callback, $this));

        return $this;
    }

    /**
     * Create a new custom tab instance.
     *
     * @param  string  $name
     * @param  string  $view
     * @param  string  $icon
     * @return \App\Ux\Settings\Tab
     */
    public function make($name, $view, $icon, callable $displayable = null)
    {
        return new Tab($name, $view, $icon, $displayable);
    }

    /**
     * Get an array of the displayable tabs.
     *
     * @param  dynamic
     * @return array
     */
    public function displayable()
    {
        $arguments = func_get_args();

        return array_values(array_filter($this->tabs, function ($tab) use ($arguments) {
            return call_user_func_array([$tab, 'displayable'], $arguments);
        }));
    }
}
