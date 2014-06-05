<div class="nc-builder">
    <div class="nc-builder__posts">
        <?php
        $builder_posts_args = apply_filters(
            'newsletter_campaign_campaign_builder_posts_args', array(
                'posts_per_page'   => -1,
                'offset'           => 0,
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'post_type'        => 'post',
                'post_status'      => 'publish'
            )
        );
        $builder_posts = get_posts( $builder_posts_args );

        $meta_vals = get_post_meta( $post->ID, '_' . $post_type . '_multi', true );

        if ($builder_posts) {
            foreach ($builder_posts as $builder_post) { ?>
                <div class="nc-builder__post">
                    <input type="text" class="nc-builder__post-id" value="<?php echo $builder_post->ID; ?>" name="newsletter_campaign_campaign_builder[]">
                    <div class="nc-builder__post-title"><?php echo $builder_post->post_title ?></div>
                    <div class="nc-builder__post-excerpt">
                        <?php echo $builder_post->post_excerpt ? $builder_post->post_excerpt : $builder_post->post_content; ?>
                    </div>
                </div>
            <?php
            }
        } else {
            _e('No posts', 'newsletter-campaign');
        }
        ?>
    </div>
    <div class="nc-builder__output">
        <div class="nc-builder__output-text" style="background:lightgray;min-height:50px;">

        </div>
    </div>
</div>