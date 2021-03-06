<?php
/**
 * See https://gravitywiz.com/documentation/how-do-i-install-a-snippet/ for details on how to install snippets like these.
 *
 * The following snippet allows you to use a checkbox field as choices.
 *
 * This will use whatever property is selected in the "Value Template" for the choices.
 *
 * FORMID is the form that the field with dynamically populated choices is on
 * FIELDID is the field ID of the field that you wish to modify the choices for
 */
add_filter( 'gppa_input_choices_FORMID_FIELDID', 'gppa_populate_checkboxes_as_choices', 10, 3 );
function gppa_populate_checkboxes_as_choices( $choices, $field, $objects ) {
	$choices   = array();
	$templates = rgar( $field, 'gppa-choices-templates', array() );
	foreach ( $objects as $object ) {
		$field_id = str_replace( 'gf_field_', '', rgar( $templates, 'value' ) );

		foreach ( $object as $meta_key => $meta_value ) {
			if ( absint( $meta_key ) === absint( $field_id ) ) {
				/**
				 * Some fields such as the multi-select store the selected values in one meta value.
				 *
				 * Other fields such as checkboxes store them as individual meta values.
				 */
				$meta_value = GFAddOn::maybe_decode_json( $meta_value );
				if ( empty( $meta_value ) ) {
					continue;
				}

				if ( is_array( $meta_value ) ) {
					foreach ( $meta_value as $value ) {
						$choices[] = array(
							'value' => $value,
							'text'  => $value,
						);
					}
				} else {
					$choices[] = array(
						'value' => $meta_value,
						'text'  => $meta_value,
					);
				}
			}
		}
	}

	return $choices;
}
