<?php

/**
 * テーブルコントローラー基底クラス
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2014, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2008 - 2014, baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			Table.Controller
 * @since			baserCMS v 0.1.0
 * @license			http://basercms.net/license/index.html
 */
/**
 * Include files
 */
App::uses('BcPluginAppController', 'Controller');

/**
 * テーブルコントローラー基底クラス
 *
 * @package			Table.Controller
 * @property TablePost $TablePost
 * @property TableCategory $TableCategory
 */
class TableAppController extends BcPluginAppController {

/**
 * コメントを管理者メールへメール送信する
 *
 * @param int $postId
 * @param array $data
 * @return boolean
 * @access protected
 */
	protected function _sendCommentAdmin($postId, $data) {
		if (!$postId || !$data || empty($this->siteConfigs['email'])) {
			return false;
		}

		$data = array_merge($data, $this->TablePost->find('first', array(
				'conditions' => array('TablePost.id' => $postId),
				'recursive' => 0
		)));
		$data['SiteConfig'] = $this->siteConfigs;
		$to = $this->siteConfigs['email'];
		$title = '【' . $this->siteConfigs['name'] . '】コメントを受け付けました';
		return $this->sendMail($to, $title, $data, array(
				'template' => 'Table.table_comment_admin',
				'agentTemplate' => false
		));
	}

/**
 * コメント投稿者にアラートメールを送信する
 *
 * @param int $postId
 * @param array $data
 * @return boolean
 * @access protected
 */
	protected function _sendCommentContributor($postId, $data) {
		if (!$postId || !$data || empty($this->siteConfigs['email'])) {
			return false;
		}

		$_data = $this->TablePost->find('first', array(
			'conditions' => array(
				'TablePost.id' => $postId
			),
			'recursive' => 1
		));

		// 公開されているコメントがない場合は true を返して終了
		if (empty($_data['TableComment'])) {
			return true;
		}

		$tableComments = $_data['TableComment'];
		unset($_data['TableComment']);
		$data = array_merge($_data, $data);

		$data['SiteConfig'] = $this->siteConfigs;
		$title = '【' . $this->siteConfigs['name'] . '】コメントが投稿されました';

		$result = true;
		$sended = array();
		foreach ($tableComments as $tableComment) {
			if ($tableComment['email'] && $tableComment['status'] && !in_array($tableComment['email'], $sended) && $tableComment['email'] != $data['TableComment']['email']) {
				$result = $this->sendMail($tableComment['email'], $title, $data, array(
					'template' => 'Table.table_comment_contributor',
					'agentTemplate' => false
				));
			}
			$sended[] = $tableComment['email'];
		}

		return $result;
	}

/**
 * beforeFilter
 *
 * @return	void
 * @access 	public
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$user = $this->BcAuth->user();
		if (!$user) {
			return;
		}
		$newCatAddable = $this->TableCategory->checkNewCategoryAddable(
			$user['user_group_id'], $this->checkRootEditable()
		);
		$this->set('newCatAddable', $newCatAddable);
	}

}
