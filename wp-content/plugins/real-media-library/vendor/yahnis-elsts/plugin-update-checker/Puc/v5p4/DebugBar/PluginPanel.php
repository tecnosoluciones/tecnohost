<?php

namespace MatthiasWeb\RealMediaLibrary\Vendor\YahnisElsts\PluginUpdateChecker\v5p4\DebugBar;

use MatthiasWeb\RealMediaLibrary\Vendor\YahnisElsts\PluginUpdateChecker\v5p4\Plugin\UpdateChecker;
if (!\class_exists(PluginPanel::class, \false)) {
    /** @internal */
    class PluginPanel extends Panel
    {
        /**
         * @var UpdateChecker
         */
        protected $updateChecker;
        protected function displayConfigHeader()
        {
            $this->row('Plugin file', \htmlentities($this->updateChecker->pluginFile));
            parent::displayConfigHeader();
        }
        protected function getMetadataButton()
        {
            $requestInfoButton = '';
            if (\function_exists('get_submit_button')) {
                $requestInfoButton = \get_submit_button('Request Info', 'secondary', 'puc-request-info-button', \false, array('id' => $this->updateChecker->getUniqueName('request-info-button')));
            }
            return $requestInfoButton;
        }
        protected function getUpdateFields()
        {
            return \array_merge(parent::getUpdateFields(), array('homepage', 'upgrade_notice', 'tested'));
        }
    }
}
