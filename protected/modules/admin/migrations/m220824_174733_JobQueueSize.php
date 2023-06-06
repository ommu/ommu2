<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\db\Migration;

/**
 * Example of migration for queue message storage.
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class m220824_174733_JobQueueSize extends Migration
{
    public $tableName = 'ommu_queue';

    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableName = Yii::$app->db->tablePrefix . $this->tableName;
            if (Yii::$app->db->getTableSchema($tableName, true)) {
                $this->alterColumn($tableName, 'job', 'LONGBLOB NOT NULL');
            }
        }
    }

    public function down()
    {
        if ($this->db->driverName === 'mysql') {
            $tableName = Yii::$app->db->tablePrefix . $this->tableName;
            if (Yii::$app->db->getTableSchema($tableName, true)) {
                $this->alterColumn($tableName, 'job', $this->binary()->notNull());
            }
        }
    }
}
