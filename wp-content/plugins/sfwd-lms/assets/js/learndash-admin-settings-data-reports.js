jQuery( function() {
	jQuery( 'button.learndash-data-reports-button' ).on( 'click', function( e ) {
		e.preventDefault();

		let container 	= jQuery( this ).parents( '.ld-global-header-new-settings' );
		var data_nonce 	= jQuery( this ).attr( 'data-nonce' );
		var data_slug 	= jQuery( this ).attr( 'data-slug' );

		// Close all other progress meters
		jQuery( 'table#learndash-data-reports .learndash-data-reports-status' ).hide();

		// disable all other buttons
		jQuery( 'button.learndash-data-reports-button' ).prop( 'disabled', true );

		// Hide all download buttons
		jQuery( 'table#learndash-data-reports a.learndash-data-reports-download-link' ).hide();

		var post_data = {
			action: 'learndash-data-reports',
			data: {
				init: 1,
				slug: data_slug,
				nonce: data_nonce,
			},
		};

		learndash_data_reports_do_ajax( post_data, container );
	} );
} );

function learndash_data_reports_do_ajax( post_data, container ) {
	if ( ( typeof post_data === 'undefined' ) || ( post_data == '' ) ) {
		active_post_data = {};
		return false;
	}

	jQuery.ajax( {
		type: 'POST',
		url: ajaxurl,
		dataType: 'json',
		cache: false,
		data: post_data,
		complete: function() {
			// Re-enable the buttons
			jQuery( 'button.learndash-data-reports-button' ).prop( 'disabled', false );
		},
		error: function( jqXHR, textStatus, errorThrown ) {
		},
		success: function( reply_data ) {
			if (
				typeof reply_data === 'undefined'
				|| typeof reply_data.data === 'undefined'
			) {
				return;
			}

			// Update the progress meter
			// TODO: Rework. Progress meter no longer exists like this.
			if ( jQuery( '.learndash-data-reports-status', container ).length ) {
				jQuery( '.learndash-data-reports-status', container ).show();

				if ( typeof reply_data.data.progress_percent !== 'undefined' ) {
					jQuery( '.learndash-data-reports-status .progress-meter-image', container ).css( 'width', reply_data.data.progress_percent + '%' );
				}

				if ( typeof reply_data.data.progress_label !== 'undefined' ) {
					jQuery( '.learndash-data-reports-status .progress-label', container ).html( reply_data.data.progress_label );
				}
			}

			let total_count = 0;
			if ( typeof reply_data.data.total_count !== 'undefined' ) {
				total_count = parseInt( reply_data.data.total_count );
			}

			let result_count = 0;
			if ( typeof reply_data.data.result_count !== 'undefined' ) {
				result_count = parseInt( reply_data.data.result_count );
			}

			if ( result_count < total_count ) {
				post_data.data = reply_data.data;
				learndash_data_reports_do_ajax( post_data, container );
			} else if (
					typeof reply_data.data.report_download_link !== 'undefined'
					&& reply_data.data.report_download_link !== ''
				) {
				window.location.href = reply_data.data.report_download_link;
			}
		},
	} );
}
