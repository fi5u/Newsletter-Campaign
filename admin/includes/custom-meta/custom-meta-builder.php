<div class="nc-builder">
    <div class="nc-builder__posts">
        <?php
        // Store the vals for builder in a var
        $meta_vals = get_post_meta( $post->ID, '_' . $post_type . '_builder', true );


        // We're going to get all the posts but we don't want to output posts here that
        // will be output later in the builder output boxes

        // Setup the exclude array to be used later
        $exclude_arr = [];

        // Get array of special post hidden ids
        $campaign_template_id = get_post_meta( $post->ID, '_campaign_template-select', true );

        // If this is not a new campaign with anything saved
        if (isset($campaign_template_id) && $campaign_template_id !== '') {

            $special_posts = get_post_meta( $campaign_template_id, '_template_repeater', true );

            if ($special_posts) {
                // Put the special template ids in an array so we can match them with what's saved
                $special_ids = [];
                foreach ($special_posts as $special_post) {
                    $special_ids[] = $special_post['newsletter_campaign_template_hidden'];
                }
            }

            if ($meta_vals) {
                foreach ($meta_vals as $meta_val_key => $value) {

                    // Find the last part of the key (the hash)
                    $this_key = explode('_', $meta_val_key);
                    $this_key_val = end($this_key);

                    // If the hash appears in $special_ids, exclude it
                    if ($this_key_val === 'post' || in_array($this_key_val, $special_ids)) {
                        foreach ($value as $val) {
                            $exclude_arr[] = $val;
                        }
                    }
                }
            }
        }

        // Fetch the list of posts
        $builder_posts_args = apply_filters(
            'newsletter_campaign_campaign_builder_posts_args', array(
                'posts_per_page'   => -1,
                'offset'           => 0,
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'exclude'          => $exclude_arr
            )
        );
        $builder_posts = get_posts( $builder_posts_args );

        // Output the list of posts
        if ($builder_posts) {
            foreach ($builder_posts as $builder_post) {
                echo outputBuilderPost($builder_post);
            }
        } else {
            echo '<p>' . __('No posts', 'newsletter-campaign') . '</p>';
        }
        ?>
    </div>

    <?php // The area for regular posts to be dropped into ?>
    <h4>Posts</h4>
    <div class="nc-builder__output" style="background:lightgray;min-height:50px;" data-name="post">
        <?php
        if ($meta_vals) {
            foreach ($meta_vals as $meta_val => $value) {
                if ($meta_val === 'newsletter_campaign_builder_post') {
                    foreach ($value as $this_post) {
                        $selected_post = get_post($this_post);
                        echo outputBuilderPost($selected_post, 'post');
                    }
                }
            }
        }?>
    </div>

    <?php // Get details for template associated with this campaign
    $campaign_template_id = get_post_meta( $post->ID, '_campaign_template-select', true );
    $special_posts = get_post_meta( $campaign_template_id, '_template_repeater', true );
    if (isset($special_posts) && !empty($special_posts)) {
        foreach ($special_posts as $special_post) {
            // Only output the special template block if the name field is there (hidden field will be there even for an empty template)
            if (isset($special_post['newsletter_campaign_template_special-name'])) {
                // One or more special posts have been saved, output the special posts container ?>
                <h4><?php echo $special_post['newsletter_campaign_template_special-name']; ?></h4>
                <?php // Store the special post hash id as data-name to be used for consistent saving ?>
                <div class="nc-builder__output" style="background:lightgray;min-height:50px;" data-name="<?php echo $special_post['newsletter_campaign_template_hidden']; ?>">
                    <?php
                    if ($meta_vals) {
                        foreach ($meta_vals as $meta_val => $value) {
                            if ($meta_val === 'newsletter_campaign_builder_' . $special_post['newsletter_campaign_template_hidden']) {
                                foreach ($value as $this_post) {
                                    $selected_post = get_post($this_post);
                                    echo outputBuilderPost($selected_post, $special_post['newsletter_campaign_template_hidden']);
                                }
                            }
                        }
                    }?>

                </div>
                <?php
            }
        }
    }
    ?>
</div>

<?php

    /*
     * Output the html for the draggable post element
     */

    function outputBuilderPost($post, $name_suffix = null) {

        $return_str = '<div class="nc-builder__post">
            <input type="hidden" class="nc-builder__post-id" value="' . $post->ID . '"';
        $return_str .= $name_suffix ? 'name="newsletter_campaign_builder_' . $name_suffix . '[]"': '';
        $return_str .= '>
            <div class="nc-builder__post-title">' . $post->post_title . '</div>
            <div class="nc-builder__post-excerpt">';
        $return_str .= $post->post_excerpt ? $post->post_excerpt : $post->post_content;

        $return_str .= '</div>
        </div>';

        return $return_str;

    }
?>