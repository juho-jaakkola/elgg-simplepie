<?php
/**
 * Add a new feed 
 */

gatekeeper();

$page = get_input('page');

if ($page == 'add') {
	$vars = simplepie_prepare_form_vars();
	
	$container_guid = (int) get_input('guid');
	$container = get_entity($container_guid);
	if (!$container) {
		$container = elgg_get_logged_in_user_entity();
	}

	$title = elgg_echo('simplepie:add');
} else {
	$guid = get_input('guid');
	$feed = get_entity($guid);
	$vars = simplepie_prepare_form_vars($feed);

	$title = elgg_echo('simplepie:edit');
}

elgg_push_breadcrumb($title);

$content = elgg_view_form('simplepie/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
