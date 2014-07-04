<?php
    $attributes = array();
    $attributes[ 'type' ] = 'hidden';
    $attributes[ 'value' ] = $value;
    $attributes[ 'tabindex' ] = 2;
    $attributes = PodsForm::merge_attributes( $attributes, $name, $form_field_type, $options );

    if ( pods_var( 'readonly', $options, false ) ) {
        $attributes[ 'readonly' ] = 'READONLY';

        $attributes[ 'class' ] .= ' pods-form-ui-read-only';
    }
?>
	<div class="pods-geocomplete-container">
    	<input<?php PodsForm::attributes( $attributes, $name, $form_field_type, $options ); ?> />
		<input type="text" class="pods-geocomplete-input" />
		<div class="pods-geocomplete-map"></div>
	</div>
<?php
    PodsForm::regex( $form_field_type, $options );
