<?php

namespace MatthiasWeb\RealMediaLibrary\Vendor\YahnisElsts\PluginUpdateChecker\v5p4\Vcs;

if (!\interface_exists(BaseChecker::class, \false)) {
    /** @internal */
    interface BaseChecker
    {
        /**
         * Set the repository branch to use for updates. Defaults to 'master'.
         *
         * @param string $branch
         * @return $this
         */
        public function setBranch($branch);
        /**
         * Set authentication credentials.
         *
         * @param array|string $credentials
         * @return $this
         */
        public function setAuthentication($credentials);
        /**
         * @return Api
         */
        public function getVcsApi();
    }
}
