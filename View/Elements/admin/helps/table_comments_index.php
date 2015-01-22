<?php
/**
 * [ADMIN] テーブル記事 コメント一覧　ヘルプ
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>

<p>テーブル記事に対するコメントの管理が行えます。</p>
<ul>
	<li>コメントが投稿された場合、サイト基本設定で設定された管理者メールアドレスに通知メールが送信されます。</li>
	<li>コメントが投稿された場合、コメント承認機能を利用している場合は、コメントのステータスは「非公開」となっています。
		内容を確認して問題なければ、<?php $this->BcBaser->img('admin/icn_tool_publish.png') ?>をクリックします。</li>
</ul>