<?php
/**
 * The Template for displaying all single posts.
 *
 */

global $a13_option;
global $content_width;


get_header(); 
?>

<article id="content" class="clearfix">

    <?php while (have_posts()) : the_post(); ?>
        <?php
        //pre_print(wp_get_post_categories());
        ?>

        <div id="col-mask">
            <div id="post-<?php the_ID(); ?>" <?php post_class('post-content'); ?>>
                <div class="real-content">
                    
                    <!-- Why is this theme so markup-heavy? Sheesh. -->
                    <div class="vc_row wpb_row vc_row-fluid ">
                        <div class="vc_col-sm-1 wpb_column vc_column_container">
                            <!-- empty 1-column -->
                        </div><!-- 1 column -->
                        
                        <div class="vc_col-sm-10 wpb_column vc_column_container">
                            <div class="wpb_wrapper">
                                
                                <div class="nudge-wrapper">
                                    <h2 class="page-title">
                                        Your 
                                        <span class="stylish-font">Gutsy Nudge</span> for 
                                        <span class="nudge-category stylish-font"><?php // MIKE ?></span>
                                    </h2>
                                    <div id="nudge-description">
                                        <?php the_field('nudge_description'); ?>
                                        <?php
                                        $blog_post_link = get_field('blog_post_link');
                                        if (!empty($blog_post_link['url'])) {
                                            ?>
                                            <p>
                                                <a href="<?php echo $blog_post_link['url']; ?>">
                                                    Read more about <?php // MIKE ?> on our blog...
                                                </a>
                                            </p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="vc_col-sm-4 wpb_column vc_column_container a13-sc-button_wrapper">
                                        <a class="a13-sc-button  vc_btn vc_btn_blue vc_btn_md vc_btn_rounded another-nudge-button" title="" href="<?php home_url(); ?>">
                                            <span>Get Another Nudge</span>
                                        </a>
                                    </div>
                                    <div class="vc_col-sm-4 wpb_column vc_column_container a13-sc-button_wrapper">
                                        <a class="a13-sc-button  vc_btn vc_btn_blue vc_btn_md vc_btn_rounded email-question-button" title="" href="<?php // MIKE ?>">
                                            <span>Email a Question</span>
                                        </a>
                                    </div>
                                    <div class="vc_col-sm-4 wpb_column vc_column_container a13-sc-button_wrapper">
                                        <a class="a13-sc-button  vc_btn vc_btn_blue vc_btn_md vc_btn_rounded blog-post-button" title="" href="http://highhearthealing.com/blog/<?php // MIKE ?>">
                                            Blog Posts on Courage
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="vc_col-sm-12 wpb_column vc_column_container">
                                        <div class="wpb_wrapper">
                                            <div class="wpb_text_column wpb_content_element  talk-to-intuitive-link">
                                                <div class="wpb_wrapper">
                                                    <p style="text-align: center;">
                                                        <a href="http://highhearthealing.com">
                                                            <i class="fa fa-user"></i>&nbsp;&nbsp;Talk to an Intuitive
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- 10 columns -->
                        
                        <div class="vc_col-sm-1 wpb_column vc_column_container">
                            <div class="wpb_wrapper">
                                <!-- empty 1-column -->
                            </div>
                        </div><!-- 1 column -->
                    </div><!-- vc_row -->
                    
                </div> <!-- .real-content -->
            </div><!-- .post-content -->
            
            <?php get_sidebar(); ?>
        </div><!-- #col-mask -->
        
    <?php endwhile; ?>
    
</article>

<?php get_footer(); ?>
