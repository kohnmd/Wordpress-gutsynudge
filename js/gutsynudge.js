(function($) {
    
    var $nudge_selection_elems = $('.nudge-title, #nudge-select'),
        $nudge_single_elems = $('#nudge-single, .nudge-links'),
        $nudge_category_title = $('.nudge-category'),
        $nudge_description = $('#nudge-description'),
        $nudge_blog_link = $('#nudge-blog-link'),
        $nudge_blog_button = $('a.blog-post-button'),
        nudge_blog_button_base_text = $nudge_blog_button.text().trim(),
        nudge_blog_button_base_url = 'http://highhearthealing.com/blog/';
    
    
    // Gutsy Nudge dropdown functionality
    $('#nudge-dropdown').chosen({
        disable_search: true,
        placeholder_text_single: 'Where do you need a nudge?'
    }).on('change', function(e, params) {
        if (typeof params.selected != 'undefined') {
            var category_id = parseInt(params.selected);
            if (!isNaN(category_id)) {
                
                // Okay, we know which Nudge category was selected.
                // Now AJAXify a Nudge!
                
                var data = {
                    action: 'get_nudge',
                    category_id: category_id
                };
            
                $.post(ajaxurl, data, function(response) {
                    if (response.success == true) {
                        
                        // Place response data into nudge-single template.
                        $nudge_category_title.html(response.category_name);
                        $nudge_description.html(response.nudge_description);
                        
                        // Blog Posts button
                        var blog_category = str_to_slug(response.category_name);
                        $nudge_blog_button.attr('href', nudge_blog_button_base_url + blog_category)
                            .text(nudge_blog_button_base_text + ' ' + response.category_name);
                        
                        // Add a link to a blog post if there is one.
                        if (typeof response.blog_post_link != 'undefined' && response.blog_post_link != "") {
                            console.log(response.blog_post_link);
                            
                            $nudge_description.children('p').last().append(
                                $nudge_blog_link.clone().removeAttr('id')
                                    .addClass('blog-link')
                                    .attr('href', response.blog_post_link)
                                    .prepend(' ')
                            );
                        }
                        
                        // Show/hide shit.
                        $nudge_selection_elems.hide();
                        $nudge_single_elems.fadeIn(700);
                    }
                });   
            }
        }
    });
    
    // Always reset the nudge dropdown when page first loads
    reset_nudge_dropdown();
    
    // Utility function that turns a string into a slug
    function str_to_slug(str, sep) {
        if (typeof sep == 'undefined') {
            sep = '-';
        }
        
        // remove extra spaces and convert to lowercase
        str = str.toString().trim().toLowerCase();
    	// convert any character that's not alphanumeric into a separator
    	str = str.replace(/[^a-z0-9]/g, sep)
    	// replace any successive separators with a single one
    	var duplicate_seps_regex = new RegExp(sep+'+', 'g');
    	str = str.replace(duplicate_seps_regex, sep);
    	// remove any hanging separators at the end of the string and return
    	var rtrim_regex = new RegExp(sep+'*$', 'g');
    	return str.replace(rtrim_regex, "");
    	
    }
    
    // Resets the nudge dropdown
    function reset_nudge_dropdown() {
        $('#nudge-dropdown').val('').trigger('chosen:updated');
    }
    
}(jQuery));