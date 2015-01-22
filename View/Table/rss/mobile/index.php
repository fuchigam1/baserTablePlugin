<?php

/**
 * [MOBILE] RSS
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>
<?php
if($posts){
	echo $this->Rss->items($posts,'transformRSS');
}

function transformRSS($data) {
	return array(
		'title' => $data['TablePost']['name'],
		'link' => Router::url('/' . Configure::read('BcRequest.agentAlias') . '/' . $data['TableContent']['name'] . '/archives/' . $data['TablePost']['no']),
		'guid' => Router::url('/' . Configure::read('BcRequest.agentAlias') . '/' . $data['TableContent']['name'] . '/archives/' . $data['TablePost']['no']),
		'category' => $data['TableCategory']['title'],
		'description' => $data['TablePost']['content'],
		'pubDate' => $data['TablePost']['posts_date']
	);
}
?>
