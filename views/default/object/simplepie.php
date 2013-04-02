<?php
/**
 * View for simplepie objects
 *
 * @package Simplepie
 */

$full = elgg_extract('full_view', $vars, FALSE);
$feed = elgg_extract('entity', $vars, FALSE);

if (!$feed) {
	return TRUE;
}

$owner = $feed->getOwnerEntity();
$container = $feed->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = $feed->excerpt;
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($feed->description);
}

$owner_icon = elgg_view_entity_icon($owner, 'tiny');

$author_text = elgg_echo('byline', array($owner->name));
$date = elgg_view_friendly_time($feed->time_created);

$metadata = elgg_view_menu('entity', array(
	'entity' => $feed,
	'handler' => 'simplepie',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {

	$body = elgg_view('output/longtext', array(
		'value' => $feed->description,
	));

	$body .= elgg_view('simplepie/feed', $vars);

	$params = array(
		'entity' => $feed,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'summary' => $summary,
		'icon' => $owner_icon,
		'body' => $body,
	));

} else {
	// brief view

	$params = array(
		'entity' => $feed,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($owner_icon, $list_body);
}
