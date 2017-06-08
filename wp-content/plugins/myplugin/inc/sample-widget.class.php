<?php

/**
 * Created by PhpStorm.
 * User: ivaylopenev
 * Date: 5/30/17
 * Time: 15:26
 */
class Sample_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('sample_widget', __('Sample_Widget'), array('classname' => 'sample_widget', 'description' => __('Event Widget')), array());
    }

}

register_widget('Sample_Widget');
