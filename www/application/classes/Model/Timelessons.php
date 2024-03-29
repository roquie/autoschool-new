<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Timelessons extends ORM
{
    protected $_db = 'default';
    protected $_table_name  = 'timelessons';
    protected $_primary_key = 'id';

    protected $_table_columns = array(
        'id' => array('data_type' => 'int', 'is_nullable' => false),
        'group_id' => array('data_type' => 'int', 'is_nullable' => false),
        'lesson' => array('data_type' => 'string', 'is_nullable' => false),
        'day_of_week' => array('data_type' => 'string', 'is_nullable' => true),
        'time_start' => array('data_type' => 'string', 'is_nullable' => true),
        'time_end' => array('data_type' => 'string', 'is_nullable' => true),
    );

    protected $_belongs_to = array(
        'lesson' => array(
            'model' => 'Lessons',
            'foreign_key' => 'lesson_id',
        )
    );

    public function rules()
    {
        return array(
            'group_id' => array(
                array('not_empty'),
                array('digit')
            ),
            'lesson' => array(
                array('not_empty'),
            ),
            'day_of_week' => array(
                array('not_empty'),

            ),
            'time_start' => array(
                array('not_empty'),
                array('date'),
            ),
            'time_end' => array(
                array('not_empty'),
                array('date'),
            ),

        );
    }

    public function labels()
    {
        return array(
            'group_id' => 'Номер группы',
            'day_of_week' => 'День недели',
            'time_start' => 'Время начала занятий',
            'time_end' => 'Время окончания занятий',
            'lesson' => 'Предмет',
        );
    }

    public function filters()
    {
        return array(
            true => array(
                array('trim'),
                array('Security::xss_clean', array(':value')),
            )
        );
    }
}