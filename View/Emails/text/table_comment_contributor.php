<?php
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
　　　　　　　　◆◇　コメントが投稿されました　◇◆
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

<?php echo $TableComment['name'] ?>さんが、
「<?php echo $TablePost['name'] ?>」にコメントしました。
<?php echo $this->BcBaser->getUri('/' . $TableContent['name'] . '/archives/' . $TablePost['no'], false) ?>　

<?php echo ($TableComment['message']) ?>　
　
　

