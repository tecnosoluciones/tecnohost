<?php
/*
 * The file is included from view-hardcoded-default.php, and it's relevant only for the hardcoded assets (non-enqueued)
*/

use WpAssetCleanUp\HardcodedAssets;
use WpAssetCleanUp\Misc;

if (! isset($data)) {
    exit; // no direct access
}

$assetType  = $data['row']['asset_type'];
$assetTypeS = substr($data['row']['asset_type'], 0, -1); // "styles" to "style" & "scripts" to "script"

$isCoreFile       = isset($data['row']['obj']->wp) && $data['row']['obj']->wp;
$isGroupUnloaded  = $data['row']['is_group_unloaded'];

// Unloaded site-wide
if ($data['row']['global_unloaded']) {
    $data['row']['class'] .= ' wpacu_is_global_unloaded';
}

// Unloaded site-wide OR on all posts, pages, etc.
if ($isGroupUnloaded) {
    $data['row']['class'] .= ' wpacu_is_bulk_unloaded';
}

// Probably most reliable to use in order to check the unloaded assets; it might be the only one used in future plugin versions
if (isset($data['current_unloaded_all'][$assetType]) && strpos($data['row']['class'], 'wpacu_not_load') === false && in_array($data['row']['obj']->handle, $data['current_unloaded_all'][$assetType])) {
    $data['row']['class'] .= ' wpacu_not_load';
}

$rowIsContracted   = '';
$dashSign          = 'minus';
$dataRowStatusAttr = 'expanded';

if (isset($data['handle_rows_contracted'][$assetType][$data['row']['obj']->handle]) && $data['handle_rows_contracted'][$assetType][$data['row']['obj']->handle]) {
    $rowIsContracted   = 1;
    $dashSign          = 'plus';
    $dataRowStatusAttr = 'contracted';
}
?>
<tr data-<?php echo $assetTypeS; ?>-handle-row="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
    data-is-hardcoded-asset="true"
    class="wpacu_asset_row <?php echo esc_attr($data['row']['class']); ?>">
    <td style="position: relative;" data-wpacu-row-status="<?php echo esc_attr($dataRowStatusAttr); ?>">
        <!-- [reference field] -->
        <input type="hidden" name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[<?php echo $assetType; ?>][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>]" value="" />
        <!-- [/reference field] -->
        <div class="wpacu_handle_row_expand_contract_area">
            <a data-wpacu-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
               data-wpacu-handle-for="<?php echo $assetTypeS; ?>"
               class="wpacu_handle_row_expand_contract"
               href="#"><span class="dashicons dashicons-<?php echo esc_attr($dashSign); ?>"></span></a>
        </div>
        <?php
        $insideIeCommentHtml = '<div class="wpacu_inside_cond_comm"><img style="vertical-align: middle;" width="25" height="25" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-ie.svg" alt="" title="Microsoft / Public domain" />&nbsp;<span style="font-weight: 400; color: #1C87CF;">Loads only in Internet Explorer based on the following condition:</span> <em>if '.esc_html($data['row']['obj']->inside_conditional_comment).'</em></div>';

        if (isset($data['row']['obj']->src) && trim($data['row']['obj']->src)) {
            // Source
            include dirname(__DIR__).'/_common/_asset-single-row-hardcoded-source.php';
            ?>
            <div class="wpacu_asset_size_area">File Size: <?php echo apply_filters('wpacu_get_asset_size', $data['row']['obj'], 'for_print'); ?></div>
            <?php
            if ($data['row']['obj']->inside_conditional_comment) {
                echo Misc::stripIrrelevantHtmlTags($insideIeCommentHtml);
            }
            ?>
            <div class="wpacu_hardcoded_part_if_expanded">
                <div style="margin: 10px 0;" class="wpacu-hardcoded-code-area">
                    HTML Output: <code><?php echo htmlentities( $data['row']['obj']->tag_output ); ?></code>
                </div>
            </div>
            <?php
        } else {
            $tagOutput = trim($data['row']['obj']->tag_output);

            // default values (could be changed below)
            $totalCodeLines = 1;
            $enableViewMore = false;

            if (strpos($tagOutput, "\n") !== false) {
                $totalCodeLines = count(explode("\n", $tagOutput));

                if ($totalCodeLines > 18) {
                    $enableViewMore = true;
                }
            }

            if (strlen($tagOutput) > 600) {
                $enableViewMore = true;
            }
            ?>
            <div class="wpacu-hardcoded-code-area">
                <?php
                if ($tagBelongsToArray = HardcodedAssets::belongsTo($data['row']['obj']->tag_output)) {
                    $tagBelongsTo = $tagBelongsToArray['text'];
                    echo '<div style="margin-bottom: 10px;">'.esc_html__('Belongs to', 'wp-asset-clean-up').': <strong>'.$tagBelongsTo . '</strong></div>';
                }

                if ($data['row']['obj']->inside_conditional_comment) {
                    echo Misc::stripIrrelevantHtmlTags($insideIeCommentHtml);
                }

                $extraInfo            = array();
                $assetHandleHasSrc    = true;
                $assetPosition        = isset($data['row']['obj']->position)     ? $data['row']['obj']->position     : '';
                $assetPositionNew     = isset($data['row']['obj']->position_new) ? $data['row']['obj']->position_new : $assetPosition;
                $assetLocationChanged = $assetPositionNew !== $assetPosition;

                include dirname(__DIR__) . '/_common/_asset-single-row-position.php';
                ?>

                <?php if (isset($extraInfo[0]) && $extraInfo[0]) { ?>
                    <div class="wpacu_position_hardcoded_wrap_tag_with_no_src" style="margin-bottom: 10px; margin-right: 15px;">
                        <?php echo $extraInfo[0]; ?>
                        <?php
                        // [wpacu_pro]
                        if ($assetLocationChanged) {
                            ?>
                            <div style="display: inline-block; color: #004567; font-style: italic; font-size: 90%; font-weight: 600; margin: 4px 0 10px;">
                                <span class="dashicons dashicons-info" style="font-size: 19px; line-height: normal;"></span> <?php _e('This file has its initial location changed.', 'wp-asset-clean-up'); ?>
                            </div>
                            <?php
                        }
                        // [/wpacu_pro]
                        ?>
                    </div>
                <?php } ?>

                <div class="wpacu_asset_size_area wpacu_for_hardcoded_tag_with_no_src">HTML Tag Size: <?php echo apply_filters('wpacu_get_asset_size', $tagOutput, 'for_print', 'tag'); ?></div>

                <div class="wpacu_hardcoded_part_if_expanded <?php if ($enableViewMore) { ?>wpacu-has-view-more<?php } ?>">
                    <div>
                        <pre><code><?php echo htmlentities( $data['row']['obj']->tag_output ); ?></code></pre>
                    </div>
                    <?php if ($enableViewMore) {
                        $wpacuViewMoreCodeBtnClass = ! is_admin() ? 'wpacu-view-more-code' : 'button';
                        ?>
                        <p class="wpacu-view-more-link-area" style="margin: 0 !important; padding: 15px !important;"><a href="#" class="<?php echo esc_attr($wpacuViewMoreCodeBtnClass); ?>"><?php esc_html_e('View more', 'wp-asset-clean-up'); ?></a></p>
                    <?php } ?>
                </div>

                <div class="wpacu_hardcoded_part_if_contracted">
                    <code>
                        <?php
                        if (strlen($data['row']['obj']->tag_output) > 100) {
                            $tagOutputPart = substr( $data['row']['obj']->tag_output, 0, 100 ). '...';
                        } else {
                            $tagOutputPart = $data['row']['obj']->tag_output;
                        }

                        echo htmlentities($tagOutputPart);
                        ?>
                    </code>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="wpacu_handle_row_expanded_area <?php if ($rowIsContracted) { echo 'wpacu_hide'; } ?>">
            <div class="wrap_bulk_unload_options">
                <?php
                if ($assetType === 'styles') {
                    $tagType = '';

                    if (isset($data['row']['obj']->tag_output)) {
                        $tagType = (strpos($data['row']['obj']->tag_output, '<link') !== false) ? 'LINK' : 'STYLE';
                    }
                }

                // Unload on this page
                include dirname(__DIR__).'/_common/_asset-single-row-unload-per-page.php';

                // Unload site-wide (everywhere)
                include dirname(__DIR__).'/_common/_asset-single-row-unload-site-wide.php';

                // Unload on all pages of [post] post type (if applicable)
                include dirname(__DIR__).'/_common/_asset-single-row-unload-post-type.php';

                // Unload on all pages where this [post] post type has a certain taxonomy set for it (e.g. a Tag or a Category) (if applicable)
                // There has to be at least a taxonomy created for this [post] post type in order to show this option
                if (isset($data['post_type']) && $data['post_type'] !== 'attachment' && ! $data['row']['is_post_type_unloaded'] && ! empty($data['post_type_has_tax_assoc'])) {
                    // Unload on all pages where this [post] post type has a certain taxonomy set for it (e.g. a Tag or a Category) (if applicable)
                    include dirname(__DIR__).'/_common/_asset-single-row-unload-post-type-taxonomy.php';
                }

                // Unload via RegEx (if site-wide is not already chosen)
                include dirname(__DIR__).'/_common/_asset-single-row-unload-via-regex.php';

                do_action('wpacu_pro_bulk_unload_output', $data, $data['row']['obj'], $assetType);

                // [wpacu_pro]
                // If any bulk unload rule is set, show the load exceptions
                include dirname( __DIR__ ) . '/_common/_asset-single-row-hardcoded-load-exceptions.php';
                // [/wpacu_pro]
                ?>
                <div class="wpacu_clearfix"></div>
            </div>
            <?php
            if (isset($data['row']['obj']->src) && trim($data['row']['obj']->src)) {
                include dirname(__DIR__).'/_asset-'.$assetTypeS.'-single-row/_loaded-rules.php';
            }

            // Handle Note
            include dirname(__DIR__).'/_common/_asset-single-row-notes.php';
            ?>
        </div>
        <img style="display: none;"
             class="wpacu_ajax_loader"
             src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-ajax-loading-spinner.svg" alt="<?php esc_html_e('Loading'); ?>..." />

        <!-- [wpacu_pro] -->
        <input type="hidden"
               id="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>_hardcoded_data"
               name="wpacu_assets_info_hardcoded_data[<?php echo $assetType; ?>][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>]"
               value="<?php echo esc_attr($data['row']['obj']->hardcoded_data); ?>" />
        <!-- [/wpacu_pro] -->
    </td>
</tr>
