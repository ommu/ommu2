<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use Yii;
use yii\db\Migration;

/**
 * Example of migration for queue message storage.
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class m220824_174729_Queue extends Migration
{
    public $tableName = 'ommu_queue';

    public function up()
    {
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
            $this->createTable($tableName, [
                'id' => $this->primaryKey(),
                'channel' => $this->string()->notNull(),
                'job' => $this->binary()->notNull(),
                'created_at' => $this->integer()->notNull(),
                'started_at' => $this->integer(),
                'finished_at' => $this->integer(),
            ], $tableOptions);
        }

        $this->createIndex('channel', $tableName, 'channel');
        $this->createIndex('started_at', $tableName, 'started_at');
    }

    public function down()
    {
		$tableName = Yii::$app->db->tablePrefix . $this->tableName;
        $this->dropTable($tableName);
    }
}
