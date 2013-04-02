<?php

$access_id = (int) get_input("access");
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('guid');
$feed_url = get_input('feed_url');
$num_items = (int) get_input('num_items', 10);
$excerpt = get_input('excerpt');

if (!$feed_url) {
	register_error(elgg_echo('simplepie:save:failed'));
	forward(REFERER);
}

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

$entity = get_entity($guid);

if ($entity) {
	if (!elgg_instanceof($entity, 'object', 'simplepie') || !$entity->canEdit()) {
		system_message(elgg_echo('simplepie:save:failed'));
		forward(REFERRER);
	}
} else {
	$entity = new ElggObject();
	$entity->subtype = 'simplepie';
	$entity->container_guid = $container_guid;
}

$entity->access_id = $access_id;
$entity->feed_url = $feed_url;
$entity->excerpt = $excerpt;
$entity->num_items = $num_items;

elgg_load_library('simplepie');
$feed = new SimplePie($feed_url);
$entity->title = $feed->get_title();

if ($entity->save()) {
	elgg_clear_sticky_form('simplepie');
	system_message(elgg_echo('simplepie:saved'));
	forward($entity->getURL());
} else {
	register_error(elgg_echo('simplepie:error:notsaved'));
	forward(REFERER);
}
