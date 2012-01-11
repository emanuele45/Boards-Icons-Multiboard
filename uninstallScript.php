<?php
/**
 * Board Icons Multiboard (BIM)
 *
 * @package BIM
 * @author emanuele
 * @copyright 2011 emanuele, Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 0.1.0
 */

// If we have found SSI.php and we are outside of SMF, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');

$smcFunc['db_query']('alter_table_icons', '
	ALTER TABLE {db_prefix}message_icons
	ORDER BY icon_order, id_board',
	array(
		'db_error_skip' => true,
	)
);

$request = $smcFunc['db_query']('', '
	SELECT id_icon, title, filename, id_board, icon_order
	FROM {db_prefix}message_icons',
	array()
);

$icons = array();
$trueOrder = 0;
while ($row = $smcFunc['db_fetch_assoc']($request))
	$icons[$row['id_icon']] = array(
			'title' => $row['title'],
			'filename' => $row['filename'],
			'board_id' => $row['id_board'],
			'true_order' => $trueOrder++,
		);

$iconInsert = array();
foreach ($icons as $id => $icon)
	$iconInsert[] = array($id, $icon['board_id'], $icon['title'], $icon['filename'], $icon['true_order']);

$smcFunc['db_insert']('replace',
	'{db_prefix}message_icons',
	array('id_icon' => 'int', 'id_board' => 'int', 'title' => 'string-80', 'filename' => 'string-80', 'icon_order' => 'int'),
	$iconInsert,
	array('id_icon')
);

if (SMF == 'SSI')
	echo 'Database adaptation successful!';
?>