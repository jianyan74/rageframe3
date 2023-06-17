<?php

namespace common\components;

use Yii;
use common\helpers\ArrayHelper;

/**
 * Class Migration
 * @package common\components
 */
class Migration extends \yii\db\Migration
{
    /**
     * 修改字段名称
     *
     * @param string $table
     * @param string $column
     * @param string $type
     * @throws \yii\db\Exception
     */
    public function renameColumn($table, $name, $newName)
    {
        if (!empty($data = Yii::$app->db->createCommand("SHOW COLUMNS FROM $table LIKE '$name'")->queryAll())) {
            parent::renameColumn($table, $name, $newName);
        }
    }

    /**
     * 修改字段类型
     *
     * @param string $table
     * @param string $column
     * @param string $type
     * @throws \yii\db\Exception
     */
    public function alterColumn($table, $column, $type)
    {
        if (!empty($data = Yii::$app->db->createCommand("SHOW COLUMNS FROM $table LIKE '$column'")->queryAll())) {
            parent::alterColumn($table, $column, $type);
        }
    }

    /**
     * 添加字段
     *
     * @param string $table
     * @param string $column
     * @param string $type
     */
    public function addColumn($table, $column, $type)
    {
        if (empty($data = Yii::$app->db->createCommand("SHOW COLUMNS FROM $table LIKE '$column'")->queryAll())) {
            parent::addColumn($table, $column, $type);
        }
    }

    /**
     * 创建表
     *
     * @param string $table
     * @param array $columns
     * @param null $options
     */
    public function createTable($table, $columns, $options = null)
    {
        $tmpTable = Yii::$app->db->getSchema()->getRawTableName($table);
        if (empty($data = Yii::$app->db->createCommand("SHOW TABLES LIKE '$tmpTable';")->queryAll())) {
            parent::createTable($table, $columns, $options);
        }
    }

    /**
     * 修改表名称
     *
     * @param string $table
     * @param string $newName
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    public function renameTable($table, $newName)
    {
        $tmpTable = Yii::$app->db->getSchema()->getRawTableName($table);
        $tmpNewName = Yii::$app->db->getSchema()->getRawTableName($newName);
        if (
            !empty($data = Yii::$app->db->createCommand("SHOW TABLES LIKE '$tmpTable';")->queryAll()) &&
            empty($dataNew = Yii::$app->db->createCommand("SHOW TABLES LIKE '$tmpNewName';")->queryAll())
        ) {
            parent::renameTable($table, $newName);
        }
    }

    /**
     * 创建索引
     *
     * @param string $name
     * @param string $table
     * @param array|string $columns
     * @param false $unique
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    public function createIndex($name, $table, $columns, $unique = false)
    {
        $tmpTable = Yii::$app->db->getSchema()->getRawTableName($table);
        $data = Yii::$app->db->createCommand("SHOW INDEX FROM $tmpTable")->queryAll();
        $columnName = ArrayHelper::getColumn($data, 'Column_name');
        $tmpColumns = explode(',', $columns);
        $status = false;
        foreach ($tmpColumns as $column) {
            if (in_array($column, $columnName)) {
                $status = true;
            }
        }

        if ($status === false) {
            parent::createIndex($name, $table, $columns, $unique);
        }
    }
}
