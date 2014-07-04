(function($, exports, undefined) {
	$('document').ready(function() {

		var geoSuccessCallbackForInput = function($acInput, $input) {
			return function(event, result) {
				$input.val($.toJSON({
					address: $acInput.val(),
					lat: result.geometry.location.lat(),
					lng: result.geometry.location.lng()
				}));
			}
		};
		
		var geoFailureCallbackForInput = function($input) {
			alert('Unable to find that location');
		};

		var initializeExistingValue = function($acInput, $input) {
			var existingValue;
			try {
				existingValue = $.parseJSON($input.val());
			} catch(e) {
				return;
			}
			$acInput.val(existingValue.address).trigger('geocode');
		};

		// Initialize geocomplete on any inputs expecting it
		$('.pods-geocomplete-input').each(function() {
			$map = $(this).parent().find('.pods-geocomplete-map');
			$latlngInput = $(this).parent().find('.pods-form-ui-field-name-pods-meta-address');

			$(this).geocomplete({map: $map})
				.bind('geocode:result', geoSuccessCallbackForInput($(this), $latlngInput));

			initializeExistingValue($(this), $latlngInput);
		});
	});
}(jQuery, window));
