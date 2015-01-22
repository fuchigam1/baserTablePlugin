<?php

/**
 * [PUBLISH] RSS
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
		'link' => '/' . $data['TableContent']['name'] . '/archives/' . $data['TablePost']['no'],
		'guid' => '/' . $data['TableContent']['name'] . '/archives/' . $data['TablePost']['no'],
		'category' => $data['TableCategory']['title'],
		'description' => $data['TablePost']['content'] . $data['TablePost']['detail'],
		'pubDate' => $data['TablePost']['posts_date']
	);
}
?>
