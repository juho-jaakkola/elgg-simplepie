<?php
/**
 * Simplepie function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $entity
 * @return array
 */
function simplepie_prepare_form_vars($entity = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $entity,
		'feed_url' => '',
		'num_items' => '',
		'excerpt' => 'yes',
	);

	if ($entity) {
		foreach (array_keys($values) as $field) {
			if (isset($entity->$field)) {
				$values[$field] = $entity->$field;
			}
		}
	}

	if (elgg_is_sticky_form('simplepie')) {
		$sticky_values = elgg_get_sticky_values('simplepie');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('simplepie');

	return $values;
}
