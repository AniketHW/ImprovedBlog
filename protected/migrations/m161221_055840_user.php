<?php
class m161221_055840_user extends CDbMigration {

	public function safeUp() {
		$this->createTable(
			'user',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(255) NOT NULL',
				'email' => 'varchar(255) NOT NULL',
				'password' => 'varchar(255) NOT NULL',
				'status' => 'TINYINT(1)',
				'created_at' => 'int(11)',
				'updated_at' => 'int(11)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {
		$this->dropTable('user');

	}
}