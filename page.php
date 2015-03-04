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
                                
                                <div class="wrapper">
                                    <h2 class="page-title">
                                        <?php the_title(); ?>
                                    </h2>
                                    <div class="page-content">
                                        <?php the_content(); ?>
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
        
        
            <?php if (is_page(12)) { // about ?>
                <div class="authors">
                    <?php
                    $authors = get_field('authors');
                    foreach ($authors as $author) {
                        ?>
                        <div class="author">
                            <a name="<?php echo sanitize_title($author['author']['display_name'] ); ?>" class="author-anchor"></a>
                            <?php
                            $author_photo_id = $author['author_photo'];
                            if ($author_photo_id) {
                                ?>
                                <div class="author-image">
                                    <?php
                                    $author_photo = wp_get_attachment_image($author_photo_id, 'apollo-people');
                                    echo $author_photo;
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="author-content">
                                <?php
                                if(isset($subtitle) && strlen($subtitle) ){
                                    echo '<h6 class="author-subtitle">'.$subtitle.'</h6>';
                                }
                                echo '<h3 class="author-title">' . $author['author']['display_name'] . '</h3>';
                                ?>
                                <div class="main-content">
                                    <?php echo $author['author_bio']; ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            <?php } ?>
        
        </div><!-- #col-mask -->
        
    <?php endwhile; ?>
    
</article>

<?php get_footer(); ?>
