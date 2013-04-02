<?php
/**
 * Individual's or group's feeds
 */

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('feed/all');
}

elgg_push_breadcrumb($owner->name);

elgg_register_title_button();

$params = array();

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user looking at own feeds
	$params['filter_context'] = 'mine';
} else if (elgg_instanceof($owner, 'user')) {
	// someone else's feeds
	// do not show select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
} else {
	// group feeds
	$params['filter'] = '';
}

$title = elgg_echo("simplepie:user", array($owner->name));

// List feeds
$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'simplepie',
	'container_guid' => $owner->guid,
	'limit' => 10,
	'full_view' => FALSE,
));
if (!$content) {
	$content = elgg_echo("simplepie:none");
}

$params['content'] = $content;
$params['title'] = $title;
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
