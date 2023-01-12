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
class m220824_174731_Retry extends Migration
{
    public $tableName = 'ommu_queue';

    public function up()
    {
        if ($this->db->driverName !== 'sqlite') {
            $tableName = Yii::$app->db->tablePrefix . $this->tableName;
            if (Yii::$app->db->getTableSchema($tableName, true)) {
                $this->renameColumn($tableName, 'created_at', 'pushed_at');
                $this->addColumn($tableName, 'ttr', $this->integer()->notNull()->after('pushed_at'));
                $this->renameColumn($tableName, 'timeout', 'delay');
                $this->dropIndex('started_at', $tableName);
                $this->renameColumn($tableName, 'started_at', 'reserved_at');
                $this->createIndex('reserved_at', $tableName, 'reserved_at');
                $this->addColumn($tableName, 'attempt', $this->integer()->after('reserved_at'));
                $this->renameColumn($tableName, 'finished_at', 'done_at');
            }
        } else {
            $this->dropTable($this->tableName);
            $this->createTable($this->tableName, [
                'id' => $this->primaryKey(),
                'channel' => $this->string()->notNull(),
                'job' => $this->binary()->notNull(),
                'pushed_at' => $this->integer()->notNull(),
                'ttr' => $this->integer()->notNull(),
                'delay' => $this->integer()->notNull(),
                'reserved_at' => $this->integer(),
                'attempt' => $this->integer(),
                'done_at' => $this->integer(),
            ]);
            $this->createIndex('channel', $this->tableName, 'channel');
            $this->createIndex('reserved_at', $this->tableName, 'reserved_at');
        }
    }

    public function down()
    {
        if ($this->db->driverName !== 'sqlite') {
            $tableName = Yii::$app->db->tablePrefix . $this->tableName;
            if (Yii::$app->db->getTableSchema($tableName, true)) {
                $this->renameColumn($tableName, 'done_at', 'finished_at');
                $this->dropColumn($tableName, 'attempt');
                $this->dropIndex('reserved_at', $tableName);
                $this->renameColumn($tableName, 'reserved_at', 'started_at');
                $this->createIndex('started_at', $tableName, 'started_at');
                $this->renameColumn($tableName, 'delay', 'timeout');
                $this->dropColumn($tableName, 'ttr');
                $this->renameColumn($tableName, 'pushed_at', 'created_at');
            }
        } else {
            $this->dropTable($this->tableName);
            $this->createTable($this->tableName, [
                'id' => $this->primaryKey(),
                'channel' => $this->string()->notNull(),
                'job' => $this->binary()->notNull(),
                'created_at' => $this->integer()->notNull(),
                'timeout' => $this->integer()->notNull(),
                'started_at' => $this->integer(),
                'finished_at' => $this->integer(),
            ]);
            $this->createIndex('channel', $this->tableName, 'channel');
            $this->createIndex('started_at', $this->tableName, 'started_at');
        }
    }
}
