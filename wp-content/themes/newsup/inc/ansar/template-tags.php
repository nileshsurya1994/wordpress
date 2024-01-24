<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package newsup
 */

if (!function_exists('newsup_post_categories')) :
    function newsup_post_categories($separator = '&nbsp')
    {
        $global_show_categories = newsup_get_option('global_show_categories');
        if ($global_show_categories == 'no') {
            return;
        }

        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {

            global $post;

            $post_categories = get_the_category($post->ID);
            if ($post_categories) {
                $output = '';
                foreach ($post_categories as $post_category) {
                    $t_id = $post_category->term_id;
                    $color_id = "category_color_" . $t_id;

                    // retrieve the existing value(s) for this meta field. This returns an array
                    $term_meta = get_option($color_id);
                    $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : 'category-color-1';

                    $output .= '<a class="newsup-categories ' . esc_attr($color_class) . '" href="' . esc_url(get_category_link($post_category)) . '" alt="' . esc_attr(sprintf(__('View all posts in %s', 'newsup'), $post_category->name)) . '"> 
                                 ' . esc_html($post_category->name) . '
                             </a>';
                }
                $output .= '';
                echo $output;

            }
        }
    }
endif;



if (!function_exists('newsup_get_category_color_class')) :

    function newsup_get_category_color_class($term_id)
    {

        $color_id = "category_color_" . $term_id;
        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option($color_id);
        $color_class = ($term_meta) ? $term_meta['color_class_term_meta'] : '';
        return $color_class;


    }
endif;

if (!function_exists('newsup_post_meta')) :

    function newsup_post_meta()
    { ?>
        <div class="mg-blog-meta"> 
        <?php $global_post_date = get_theme_mod('global_post_date_author_setting','show-date-author');
        if($global_post_date =='show-date-author') { ?>
            <span class="mg-blog-date"><i class="fas fa-clock"></i>
                <a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
                <?php echo esc_html(get_the_date(get_option('date_format', 'M j, Y'))); ?>
                </a>
            </span>
            <a class="auth" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>">
                <i class="fas fa-user-circle"></i><?php the_author(); ?>
            </a>
            
        <?php } elseif ($global_post_date =='show-date-only') { ?>
                <span class="mg-blog-date"><i class="fas fa-clock"></i>
                    <a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
                    <?php echo esc_html(get_the_date(get_option('date_format', 'M j, Y'))); ?>
                    </a>
                </span>
        <?php } elseif ($global_post_date =='show-author-only') { ?>
                <a class="auth" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>">
                    <i class="fas fa-user-circle"></i><?php the_author(); ?>
                </a>
        <?php } elseif ($global_post_date =='hide-date-author') { } ?>
        <?php $all_post_comment_disable = get_theme_mod('all_post_comment_disable',false);
        if($all_post_comment_disable == true) { ?>    
            <span class="comments-link"><i class="fas fa-comments"></i>
                <a href="<?php the_permalink(); ?>"><?php echo get_comments_number(); ?> <?php esc_html_e('Comments','newsup'); ?></a> 
            </span>  
        <?php } ?>
        <?php edit_post_link( __( 'Edit', 'newsup' ), '<span class="post-edit-link"><i class="fas fa-edit"></i>', '</span>' ); ?>  
    </div> 
<?php }
endif;

function newsup_read_more() {
    
    global $post;
    
    $readbtnurl = '<br><a class="btn btn-theme post-btn" href="' . get_permalink() . '">'.__('Read More','newsup').'</a>';
    
    return $readbtnurl;
}
add_filter( 'the_content_more_link', 'newsup_read_more' );


if (!function_exists('newsup_page_pagination')) :

    function newsup_page_pagination()
    {
        ?>
            <div class="col-md-12 text-center d-flex justify-content-center">
                <?php //Previous / next page navigation
                    $prev_text =  (is_rtl()) ? "right" : "left";
                    $next_text =  (is_rtl()) ? "left" : "right";
                    the_posts_pagination( array(
                       'prev_text'          => '<i class="fa fa-angle-'.$prev_text.'"></i>',
                       'next_text'          => '<i class="fa fa-angle-'.$next_text.'"></i>',
                       ) 
                    ); ?>                            
            </div>
        <?php
    }
endif;

if ( ! function_exists( 'newsup_the_excerpt' ) ) :

    /**
     * Generate excerpt.
     *
     */
    function newsup_the_excerpt( $length = 0, $post_obj = null ) {

        global $post;

        if ( is_null( $post_obj ) ) {
            $post_obj = $post;
        }

        $length = absint( $length );

        if ( 0 === $length ) {
            return;
        }

        $source_content = $post_obj->post_content;

        if ( ! empty( get_the_excerpt($post_obj) ) ) {
            $source_content = get_the_excerpt($post_obj);
        } 
        // Check if non-breaking space exists in the text with variations
        if (preg_match('/\s*(&nbsp;|\xA0)\s*/u', $source_content)) {
            // Remove non-breaking space and its variations from the text
            $source_content = preg_replace('/\s*(&nbsp;|\xA0)\s*/u', ' ', $source_content);
            
        }

        $source_content = preg_replace( '`\[[^\]]*\]`', '', $source_content );
        $trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );
        return $trimmed_content;

    }
endif;
