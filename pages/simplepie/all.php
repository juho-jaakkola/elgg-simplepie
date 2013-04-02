<?php
/**
 * Lists all simplepie feeds
 *
 * @package Simplepie
 */

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('simplepie'));

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'simplepie',
	'limit' => 10,
	'full_view' => false,
	'view_toggle_type' => false
));

if (!$content) {
	$content = elgg_echo('simplepie:none');
}

$title = elgg_echo('simplepie:all');

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('simplepie/sidebar'),
));

echo elgg_view_page($title, $body);