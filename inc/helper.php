<?php

function timeline_post_types(){
    $args = array(
        'public' => true
    );

    $skip_post_types = ['attachment'];

    $post_types = get_post_types($args);
    return $post_types;
}

function timeline_post_data($args){
    $defaults = array(
        'posts_per_page'   => 5,
        'offset'           => 0,
        'category'         => '',
        'category_name'    => '',
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'author'	   => '',
        'author_name'	   => '',
        'post_status'      => 'publish',
        'suppress_filters' => true
    );

    $atts = wp_parse_args($args,$defaults);

    $posts = get_posts($atts);

    return $posts;
}

function timeline_post_settings($settings){
    $post_args['post_type'] = $settings['post_type'];

    if($settings['post_type'] == 'post'){
        $post_args['category'] = $settings['category'];
    }

    $post_args['posts_per_page'] = $settings['num_posts'];
    $post_args['offset'] = $settings['post_offset'];
    $post_args['orderby'] = $settings['orderby'];
    $post_args['order'] = $settings['order'];

    return $post_args;
}