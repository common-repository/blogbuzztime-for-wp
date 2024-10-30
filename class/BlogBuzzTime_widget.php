<?php

class BlogBuzzTime_widget extends WP_Widget
{

    // constructor
    function BlogBuzzTime_widget()
    {
        $widget_ops = array(
            'description' =>  __('widget blogbuzztime description', 'blogbuzztime')
        );
        parent::WP_Widget(false, $name = 'BlogBuzzTime: ' .__('lectures en cours', 'blogbuzztime'), $widget_ops);
    }

    // widget form creation
    function form($instance)
    {
        if ($instance)
        {
            $title = esc_attr($instance['title']);
        }
        else
        {
            $title = '';
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'blogbuzztime'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php
    }

    // widget update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    // widget display
    function widget($args, $instance)
    {
        extract($args);
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        //echo $before_widget;
        // Display the widget
        echo '<div class="widget-text wp_widget_plugin_box">';

        // Check if title is set
        if ($title)
        {
            echo $before_title . $title . $after_title;
        }

        echo '<div class="_bbt-rt-target"></div></div>';
        //echo $after_widget;
    }

}