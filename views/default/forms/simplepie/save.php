<?php
/**
 * Simplepie edit form body
 */

$user = elgg_get_logged_in_user_entity();
$entity = elgg_extract('entity', $vars);

$feed_url_label = elgg_echo('simplepie:feed_url');
$feed_url_input = elgg_view("input/url", array(
	'name' => 'feed_url',
	'value' => $vars['feed_url'],
	'entity' => $entity,
));

$access_label = elgg_echo('access');
$access_input = elgg_view("input/access", array(
	'name' => 'access',
	'value' => $vars['access_id'],
));

$num_items_label = elgg_echo('simplepie:num_items');
$num_items_input = elgg_view('input/dropdown', array(
	'name' => 'num_items',
	'value' => $vars['num_items'],
	'options' => array(3, 5, 8, 10, 12, 15, 20),
));

$excerpt_label = elgg_echo('simplepie:excerpt');
$excerpt_input = elgg_view("input/dropdown", array(
	'name' => 'excerpt',
	'value' => $vars['excerpt'],
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no')
	)
));

$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['guid'],
));

$container_guid_input = elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));

$submit_input = elgg_view('input/submit', array('value' => elgg_echo('save')));

echo <<<FORM
<div>
	<label>$feed_url_label</label>
	$feed_url_input
</div>
<div>
	<label>$access_label</label>
	$access_input
</div>
<div>
	<label>$num_items_label</label>
	$num_items_input
</div>
<div>
	<label>$excerpt_label</label>
	$excerpt_input
</div>
<div class="elgg-foot">
	$guid_input
	$container_guid_input
	$submit_input
</div>
FORM;

