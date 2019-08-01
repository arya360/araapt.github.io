<?php

function lae_get_all_post_type_options() {

    $post_types = get_post_types(array('public' => true), 'objects');

    $options = ['' => ''];

    foreach ($post_types as $post_type) {
        $options[$post_type->name] = $post_type->label;
    }

    return apply_filters('lae_post_type_options', $options);
}

/**
 * Action to handle searching taxonomy terms.
 */
function lae_get_all_taxonomy_options() {

    $taxonomies = lae_get_all_taxonomies();

    $results = array();
    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms(array('taxonomy' => $taxonomy));
        foreach ($terms as $term)
            $results[$term->taxonomy . ':' . $term->slug] = $term->taxonomy . ':' . $term->name;
    }

    return apply_filters('lae_taxonomy_options', $results);
}

function lae_build_query_args($settings) {

    $query_args = [
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'ignore_sticky_posts' => 1,
        'post_status' => 'publish',
    ];

    if (!empty($settings['post_in'])) {
        $query_args['post_type'] = 'any';
        $query_args['post__in'] = explode(',', $settings['post_in']);
        $query_args['post__in'] = array_map('intval', $query_args['post__in']);
    }
    else {
        if (!empty($settings['post_types'])) {
            $query_args['post_type'] = $settings['post_types'];
        }

        if (!empty($settings['tax_query'])) {
            $tax_queries = $settings['tax_query'];

            $query_args['tax_query'] = array();
            $query_args['tax_query']['relation'] = 'OR';
            foreach ($tax_queries as $tq) {
                list($tax, $term) = explode(':', $tq);

                if (empty($tax) || empty($term))
                    continue;
                $query_args['tax_query'][] = array(
                    'taxonomy' => $tax,
                    'field' => 'slug',
                    'terms' => $term
                );
            }
        }
    }

    $query_args['posts_per_page'] = $settings['posts_per_page'];

    $query_args['offset'] = $settings['offset'];

    $query_args['paged'] = max(1, get_query_var('paged'), get_query_var('page'));

    return apply_filters('lae_posts_query_args', $query_args, $settings);
}
