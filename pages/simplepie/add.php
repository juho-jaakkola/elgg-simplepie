<?php
/**
 * Add a new feed 
 */

gatekeeper();

$container_guid = (int) get_input('guid');
$container = get_entity($container_guid);
if (!$container) {
	$container = elgg_get_logged_in_user_entity();
}

//elgg_set_page_owner_guid($page_owner->getGUID());

$title = elgg_echo('simplepie:add');
elgg_push_breadcrumb($title);

$vars = simplepie_prepare_form_vars(null, $parent_guid);
$content = elgg_view_form('simplepie/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
