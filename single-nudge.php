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
        $primary_post_category = null;
        
        $post_terms = get_the_terms(get_the_ID(), 'category');
        $i = 0;
        foreach($post_terms as $term) {
            if (isset($_GET['c'])) {
                if ($_GET['c'] == $term->term_id) {
                    $primary_post_category = $term;
                    break;
                }
            }
            
            if (++$i == 1) {
                $primary_post_category = $term;
                if (!isset($_GET['c'])) {
                    break;
                }
            }
        }
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
                                        <span class="stylish-font">Gutsy Nudge</span>
                                        <?php if ($primary_post_category) { ?>
                                            for 
                                            <span class="nudge-category stylish-font">
                                                <?php echo $primary_post_category->name; ?>
                                            </span>
                                        <?php } ?>
                                    </h2>
                                    <div id="nudge-description">
                                        <?php the_field('nudge_description'); ?>
                                        <?php
                                        $blog_post_link = get_field('blog_post_link');
                                        if (!empty($blog_post_link['url'])) {
                                            ?>
                                            <p>
                                                <a href="<?php echo $blog_post_link['url']; ?>">
                                                    Read more
                                                    <?php if ($primary_post_category) { ?>
                                                        about <?php echo $primary_post_category->name; ?>
                                                    <?php } ?>
                                                    on our blog.
                                                </a>
                                            </p>
                                            <?php
                                        }
                                        ?>
                                        <p class="author-meta">
                                            &mdash; Nudge by
                                            <a href="<?php echo get_permalink(257); // about writers ?>#<?php echo sanitize_title(get_the_author_meta('display_name')); ?>">
                                                <?php the_author_meta('display_name'); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                    <div class="vc_col-sm-4 wpb_column vc_column_container a13-sc-button_wrapper">
                                        <a href="<?php echo home_url(); ?>" class="a13-sc-button  vc_btn vc_btn_blue vc_btn_md vc_btn_rounded another-nudge-button" title="">
                                            <span>Get Another Nudge</span>
                                        </a>
                                    </div>
                                    <div class="vc_col-sm-4 wpb_column vc_column_container a13-sc-button_wrapper">
                                        <a href="<?php echo get_permalink(268); ?>" class="a13-sc-button  vc_btn vc_btn_blue vc_btn_md vc_btn_rounded email-question-button" title="">
                                            <span>Ask a Question</span>
                                        </a>
                                    </div>
                                    <div class="vc_col-sm-4 wpb_column vc_column_container a13-sc-button_wrapper">
                                        <?php                                            
                                        $external_button_text = 'Blog Posts on [category]';
                                        if (get_field('external_button_text')) {
                                            $external_button_text = get_field('external_button_text');
                                        }
                                        $external_button_text = replace_category_placeholder($external_button_text, $primary_post_category);
                                        
                                        $external_button_link = 'http://www.highhearthealing.com/blog/';
                                        if (get_field('external_button_link')) {
                                            $external_button_link = get_field('external_button_link');
                                        }
                                        ?>
                                        <a href="<?php echo $external_button_link; ?>" class="a13-sc-button  vc_btn vc_btn_blue vc_btn_md vc_btn_rounded blog-post-button" title="">
                                            <?php echo $external_button_text; ?>
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
