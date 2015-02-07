(function($) {
    
    var $nudge_selection_elems = $('.nudge-title, #nudge-select'),
        $nudge_single_elems = $('#nudge-single, .nudge-links'),
        $nudge_category_title = $('.nudge-category'),
        $nudge_description = $('#nudge-description'),
        $nudge_blog_link = $('#nudge-blog-link');
    
    
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
                        
                        // Add a link to a blog post if there is one.
                        if (response.blog_link != "") {
                            $nudge_description.append(
                                $nudge_blog_link.clone().removeAttr('id').addClass('blog-link').prepend(' ')
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
    
}(jQuery));