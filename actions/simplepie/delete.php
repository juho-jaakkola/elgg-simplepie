<?php
/**
 * Delete a simplepie feed
 *
 * @package Simplepie
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'simplepie') && $entity->canEdit()) {
	$container = $entity->getContainerEntity();
	if ($entity->delete()) {
		system_message(elgg_echo("simplepie:delete:success"));
		if (elgg_instanceof($container, 'group')) {
			forward("simplepie/group/$container->guid/all");
		} else {
			forward("simplepie/owner/$container->username");
		}
	}
}

register_error(elgg_echo("simplepie:delete:failed"));
forward(REFERER);
