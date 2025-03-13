<?php


/*
Author:      Chris Jean
Author URI:  https://chrisjean.com/
*/

namespace NobunaPlugins\Updaters;

use WPHelpers\Package;
use NobunaPlugins\Model\NobunaProduct;
use NobunaPlugins\Model\NobunaBackup;

use Plugin_Upgrader;

class PluginUpgrader extends Plugin_Upgrader {

    protected $nobunaProduct;
    protected $nobunaPackage;
    
    public function set_nobuna_product(NobunaProduct $nobunaProduct, Package $package) {
        $this->nobunaProduct = $nobunaProduct;
        $this->nobunaPackage = $package;
    }
    
    public function install_package($args = array()) {
        global $wp_filesystem;

        if (empty($args['source']) || empty($args['destination'])) {
            // Only run if the arguments we need are present.
            return parent::install_package($args);
        }

        $source_files = array_keys($wp_filesystem->dirlist($args['source']));
        $remote_destination = $wp_filesystem->find_folder($args['destination']);

        // Locate which directory to copy to the new folder, This is based on the actual folder holding the files.
        if (1 === count($source_files) && $wp_filesystem->is_dir(trailingslashit($args['source']) . $source_files[0] . '/')) { // Only one folder? Then we want its contents.
            $destination = trailingslashit($remote_destination) . trailingslashit($source_files[0]);
        } elseif (0 === count($source_files)) {
            // Looks like an empty zip, we'll let the default code handle this.
            return parent::install_package($args);
        } else { // It's only a single file, the upgrader will use the folder name of this file as the destination folder. Folder name is based on zip filename.
            $destination = trailingslashit($remote_destination) . trailingslashit(basename($args['source']));
        }

        if (is_dir($destination)) {
            // This is an upgrade, clear the destination.
            $args['clear_destination'] = true;

            // Switch template strings to use upgrade terminology rather than install terminology.
            $this->upgrade_strings();

            // Replace default remove_old string to make the messages more meaningful.
            $this->strings['installing_package'] = __nb('Upgrading the plugin&#8230;');
            $this->strings['remove_old'] = __nb('Backing up the old version of the plugin&#8230;');
        }

        return parent::install_package($args);
    }

    public function clear_destination($destination) {
        global $wp_filesystem;

        if (!is_dir($destination)) {
            // This is an installation not an upgrade.
            return parent::clear_destination($destination);
        }

        $data = Package::GetPluginInfo($directory);

        if (false === $data) {
            // The existing directory is not a valid plugin, skip backup.
            return parent::clear_destination($destination);
        }

        $result = $this->createBackup($destination);

        if (!is_wp_error($result)) {
            // Restore default strings and display the original remove_old message.
            $this->upgrade_strings();
            $this->skin->feedback('remove_old');

            return parent::clear_destination($destination);
        }

        $this->skin->error($result);
        $this->skin->feedback(__nb('Moving the old version of the plugin to a new directory&#8230;'));

        $base_name = basename($destination) . "-{$data['Version']}";
        $directory = dirname($destination);

        $new_name = $this->getDirectoryForBackup($base_name, $directory);

        if (is_dir("$directory/$new_name")) {
            // We gave it our best effort. Time to give up on the idea of having a backup.
            $this->skin->error(__nb('Unable to find a new directory name to move the old version of the plugin to. No backup will be created.'));
        } else {
            $result = $wp_filesystem->move($destination, "$directory/$new_name");

            if ($result) {
                /* translators: 1: new plugin directory name */
                $this->skin->feedback(__nb('Moved the old version of the plugin to a new plugin directory named %1$s. This directory should be backed up and removed from the site.', "<code>$new_name</code>"));
            } else {
                $this->skin->error(__nb('Unable to move the old version of the plugin to a new directory. No backup will be created.'));
            }
        }

        // Restore default strings and display the original remove_old message.
        $this->upgrade_strings();
        $this->skin->feedback('remove_old');

        return parent::clear_destination($destination);
    }
    
    private function getDirectoryForBackup($base_name, $directory) {
        $new_name = $base_name;
        for ($x = 0; $x < 20; $x++) {
            $test_name = sprintf('%s-%s', $base_name, Str::GetRandomCharacters(10, 20));
            if (!is_dir("$directory/$test_name")) {
                $new_name = $test_name;
                break;
            }
        }
        return $new_name;
    }

    private function createBackup($directory) {
        return NobunaBackup::CreateBackup($directory, $this->nobunaProduct, $this->skin);
    }

}
