<?php
/**
 * View a simplepie feed
 *
 * @package Simplepie
 */

$feed = get_entity(get_input('guid'));
if (!$feed) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

$page_owner = elgg_get_page_owner_entity();
if (elgg_instanceof($page_owner, 'group')) {
	elgg_push_breadcrumb($page_owner->name, "simplepie/group/{$page_owner->getGUID()}/all");
}

$title = $feed->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($feed, array('full_view' => true));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
