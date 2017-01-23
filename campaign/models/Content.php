<?php
namespace campaign\models;

use Yii;
use yii\db\ActiveRecord;

class Content extends ActiveRecord{
    /**
     * 图片的字段名称
     * @var unknown
     */
    const FIELD_IMAGE = "image";
    /**
     * 线路介绍的字段名称
     * @var unknown
     */
    const FIELD_lINE_INTRODUCTION = "lineIntroduction";
    const FIELD_LINE_INTRODUCTION_NAME = "线路介绍";
    /**
     * 行程安排的字段名称
     * @var unknown
     */
    const FIELD_SCHEDULING = "scheduling";
    const FIELD_SCHEDULING_NAME = "行程安排";
    /**
     * 费用说明的字段名称
     * @var unknown
     */
    const FIELD_EXPENSE_EXPLANATION = "expenseExplanation";
    const FIELD_EXPENSE_EXPLANATION_NAME = "费用说明";
    /**
     * 更多介绍的字段名称
     * @var unknown
     */
    const FIELD_MORE_INTRODUCTION = "moreIntroduction";
    const FIELD_MORE_INTRODUCTION_NAME = "更多介绍";
    public static function getDb() {
        return Yii::$app->db_camp;
    }
    public static function tableName() {
        return '{{campaign_content}}';
    }
}