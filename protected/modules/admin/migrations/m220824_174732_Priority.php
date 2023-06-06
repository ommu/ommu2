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
class m220824_174732_Priority extends Migration
{
    public $tableName = 'ommu_queue';

    public function up()
    {
        $tableName = Yii::$app->db->tablePrefix . $this->tableName;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->addColumn($tableName, 'priority', $this->integer()->unsigned()->notNull()->defaultValue(1024)->after('delay'));
            $this->createIndex('priority', $tableName, 'priority');
        }
    }

    public function down()
    {
        $tableName = Yii::$app->db->tablePrefix . $this->tableName;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->dropIndex('priority', $tableName);
            $this->dropColumn($tableName, 'priority');
        }
    }
}
