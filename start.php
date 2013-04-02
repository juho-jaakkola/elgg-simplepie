<?php
/**
 * Simplepie Plugin
 *
 * Loads the simplepie feed parser library and provides a widget
 */

elgg_register_event_handler('init', 'system', 'simplepie_init');

function simplepie_init() {
	$path = elgg_get_plugins_path() . 'simplepie/';
	elgg_register_library('simplepie', $path . 'vendors/simplepie.php');
	elgg_register_library('elgg:simplepie', $path . 'lib/simplepie.php');

	elgg_register_widget_type(
		'feed_reader',
		elgg_echo('simplepie:widget'),
		elgg_echo('simplepie:description'),
		array('all'),
		'all',
		true
	);

	elgg_extend_view('css/elgg', 'simplepie/css');

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'simplepie_owner_block_menu');
	elgg_register_plugin_hook_handler('entity:url', 'object', 'simplepie_url_handler');

	elgg_register_page_handler('simplepie', 'simplepie_page_handler');

	$actions_path = elgg_get_plugins_path() . 'simplepie/actions/simplepie';
	elgg_register_action('simplepie/save', "$actions_path/save.php");
	elgg_register_action('simplepie/delete', "$actions_path/delete.php");

	// Allow own feeds for groups
	add_group_tool_option('simplepie', elgg_echo('simplepie:enablefeeds'), true);
	elgg_extend_view('groups/tool_latest', 'simplepie/group_module');
}

/**
 * Dispatches simplepie pages.
 * 
 * URLs take the form of
 *  All feeds:       simplepie/all
 *  View feed:       simplepie/view/<guid>/<title>
 *  New feed:        simplepie/add/<guid>
 *  Edit feed:       simplepie/edit/<guid>
 *  Group feeds:     simplepie/group/<guid>/all
 *
 * @param array $page
 * @return bool
 */
function simplepie_page_handler($page) {
	elgg_load_library('elgg:simplepie');
	elgg_load_library('simplepie');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('simplepie'), "simplepie/all");

	$file_dir = elgg_get_plugins_path() . 'simplepie/pages/simplepie';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			include "$file_dir/owner.php";
			break;
		case 'friends':
			include "$file_dir/friends.php";
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$file_dir/view.php";
			break;
		case 'add':
			set_input('page', $page[0]);
			set_input('guid', $page[1]);
			include "$file_dir/save.php";
			break;
		case 'edit':
			set_input('page', $page[0]);
			set_input('guid', $page[1]);
			include "$file_dir/save.php";
			break;
		case 'group':
			include "$file_dir/owner.php";
			break;
		case 'all':
			include "$file_dir/all.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Add a menu item to an ownerblock
 */
function simplepie_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'group')) {
		$group = $params['entity'];
		if ($group->simplepie_enable != "no") {
			$url = "simplepie/group/{$group->getGUID()}/all";
			$item = new ElggMenuItem('simplepie', elgg_echo('simplepie:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Format and return the URL for feed page.
 *
 * @param ElggObject $entity Object
 * @return string URL of feed page.
 */
function simplepie_url_handler($hook, $type, $url, $params) {
	$entity = elgg_extract('entity', $params);

	if (!elgg_instanceof($entity, 'object', 'simplepie')) {
		return $url;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);

	return "simplepie/view/{$entity->guid}/$friendly_title";
}
