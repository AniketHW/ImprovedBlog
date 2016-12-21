<?php

class m161221_061727_like extends CDbMigration
{
	public function safeUp() {
		$this->createTable(
			'like',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'user_id'=>'int(11) UNSIGNED NOT NULL',
				'post_id'=>'int(11) UNSIGNED NOT NULL',
				'status' => 'TINYINT(1)',
				'created_at'=> 'int(11)',
				'updated_at'=>'int(11)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {
		$this->dropTable('like');

	}
}