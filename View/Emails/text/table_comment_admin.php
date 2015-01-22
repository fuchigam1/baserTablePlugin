
/**
 * [EMAIL] メール送信
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>

                                           <?php echo date('Y-m-d H:i:s') ?>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　　　　　　　　◆◇　コメントを受付けました　◇◆
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

　<?php echo $TableContent['title'] ?> へのコメントを受け付けました。
　受信内容は下記のとおりです。

　「<?php echo $TablePost['name'] ?>」
　<?php echo $this->BcBaser->getUri('/' . $TableContent['name'] . '/archives/' . $TablePost['no'], false) ?>　

━━━━◇◆━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　◆ コメント内容
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━◆◇━━━━

送信者名： <?php echo ($TableComment['name']) ?>　
Ｅメール： <?php echo ($TableComment['email']) ?>　
ＵＲＬ　： <?php echo ($TableComment['url']) ?>　

<?php echo ($TableComment['message']) ?>　

コメントの公開状態を変更する場合は次のURLよりご確認ください。
<?php echo $this->BcBaser->getUri('/admin/table/table_comments/index/' . $TableContent['id'], false) ?>　
　
　
