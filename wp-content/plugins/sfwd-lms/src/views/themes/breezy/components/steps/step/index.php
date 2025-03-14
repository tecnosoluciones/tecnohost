<?php
/**
 * View: Step.
 *
 * @since 4.6.0
 * @version 4.15.1
 *
 * @var Template $this Current Instance of template engine rendering this template.
 *
 * @package LearnDash\Core
 */

/** NOTICE: This code is currently under development and may not be stable.
 *  Its functionality, behavior, and interfaces may change at any time without notice.
 *  Please refrain from using it in production or other critical systems.
 *  By using this code, you assume all risks and liabilities associated with its use.
 *  Thank you for your understanding and cooperation.
 **/

use LearnDash\Core\Template\Template;
?>
<?php $this->template( 'components/steps/step/start' ); ?>
	<div class="ld-steps__info-left">
		<?php $this->template( 'components/steps/step/title' ); ?>

		<?php $this->template( 'components/steps/step/contents' ); ?>
	</div>

	<div class="ld-steps__info-right">
		<?php $this->template( 'components/steps/step/progress' ); ?>

		<?php $this->template( 'components/steps/step/action' ); ?>
	</div>
<?php $this->template( 'components/steps/step/end' ); ?>
