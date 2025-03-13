<?php

namespace FcfVendor\WPDesk\DeactivationModal\Model;

/**
 * Stores information about the the deactivation modal template.
 */
class FormTemplate
{
    /**
     * @var string
     */
    private $form_title;
    /**
     * @var string
     */
    private $form_desc;
    /**
     * @var string
     */
    private $plugin_name;
    /**
     * @param string $plugin_name The full name of the plugin.
     */
    public function __construct(string $plugin_name)
    {
        $this->plugin_name = $plugin_name;
    }
    public function set_form_title(string $form_title): self
    {
        $this->form_title = $form_title;
        return $this;
    }
    public function get_form_title(): string
    {
        return empty($this->form_title) ? sprintf(
            /* translators: %1$s: plugin name */
            __('You are deactivating %1$s plugin', 'flexible-checkout-fields'),
            $this->plugin_name
        ) : $this->form_title;
    }
    public function set_form_desc(string $form_desc): self
    {
        $this->form_desc = $form_desc;
        return $this;
    }
    public function get_form_desc(): string
    {
        return empty($this->form_desc) ? __('If you have a moment, please let us know why you are deactivating plugin (anonymous feedback):', 'flexible-checkout-fields') : $this->form_desc;
    }
}
