<?php
/**
 * The template for displaying all pages.
 *
 */
$with_builder = a13_has_shortcode('vc_row' );
get_header(); ?>
<?php if ( have_posts() ) : the_post(); ?>

<article id="content" class="clearfix">

    <div id="col-mask">

        <div id="post-<?php the_ID(); ?>" <?php post_class('post-content'); ?>>
            <?php
                //a13_top_image_video_slider();
            ?>

            <div class="real-content">
                <?php if (!$with_builder) { ?>
                    <div class="pure_page_wrapper">
                <?php } ?>
                
                    <?php if (!is_front_page()) { ?>
                        <div class="vc_row wpb_row vc_row-fluid">
                            <div class="vc_col-sm-12 wpb_column vc_column_container">
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                            </div>
                        </div>
                    <?php } ?>
                    <?php the_content(); ?>
                
                <?php if (!$with_builder) { ?>
                    </div>
                <?php } ?>
                
                <div class="clear"></div>

                <?php
                wp_link_pages( array(
                        'before' => '<div id="page-links">'.__fe('Pages: '),
                        'after'  => '</div>')
                );
                ?>
            </div>

        </div>

        <?php //get_sidebar(); ?>

    </div>

</article>

<?php endif; ?>

<?php get_footer(); ?>