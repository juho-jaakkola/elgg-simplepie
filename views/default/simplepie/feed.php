<?php

$entity = elgg_extract('entity', $vars);

$view_excerpt   = $entity->excerpt;
$post_date = $entity->post_date;

$cache_location = elgg_get_data_path() . '/simplepie_cache/';
if (!file_exists($cache_location)) {
	mkdir($cache_location, 0777);
}

$feed = new SimplePie($entity->feed_url, $cache_location);

$num_posts_in_feed = $feed->get_item_quantity();

if ($num_posts_in_feed) {
	$num_items = $entity->num_items;

	// don't display more feed items than user requested
	if ($num_items > $num_posts_in_feed) {
		$num_items = $num_posts_in_feed;
	}

	$items = '';
	foreach ($feed->get_items(0, $num_items) as $item) {
		$item_link = elgg_view('output/url', array(
			'href' => $item->get_permalink(),
			'text' => $item->get_title(),
		));

		if ($view_excerpt != 'no') {
			$text = strip_tags($item->get_description(true), $allowed_tags);

			$body = elgg_view('output/longtext', array(
				'value' => elgg_get_excerpt($text),
			));
		}

		if ($post_date) {
			$item_date_label = elgg_echo('simplepie:postedon');
			$item_date = $item->get_date('j F Y | g:i a');
			$post_date = "$item_date_label $item_date";
		}

		$author_text = '';
		if ($author = $item->get_author()) {
			$author_text = $item->get_author()->get_name();
		}

		$date = elgg_view_friendly_time($item->get_date('U'));
		$subtitle = "$author_text $date";

		$params = array(
			'entity' => $feed,
			'title' => $item_link,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'content' => $body,
		);
		$summary = elgg_view('object/elements/summary', $params);

		$block = elgg_view_image_block($owner_icon, $summary);

		$items .= "<li class=\"elgg-item\">$block</li>";
	}

	$feed_link = elgg_view('output/url', array(
		'href' => $feed->get_permalink(),
		'text' => elgg_echo('link:view:all'),
	));

	echo "<ul class=\"elgg-list\">$items</ul>$feed_link";
} else {
	echo '<p>' . elgg_echo('simplepie:notfind') . '</p>';
}
