(function($) {
    
    var $nudge_selection_elems = $('.nudge-title, #nudge-select'),
        $nudge_single_elems = $('#nudge-single'),
        $nudge_category_title = $('.nudge-category'),
        $nudge_short_description = $('#nudge-short-description'),
        $nudge_long_description = $('#nudge-long-description'),
        $nudge_more_link = $('#nudge-more-link');
    
    
    
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
                    console.log(response);
                    
                    if (response.success == true) {
                        
                        // Place response data into nudge-single template.
                        $nudge_category_title.html(response.category_name);
                        $nudge_short_description.html(response.short_description);
                        
                        // Add a "More" link to the short description if there's a long description.
                        if (response.long_description != "") {
                            $nudge_short_description.find(':last-child').append(
                                $nudge_more_link.clone().removeAttr('id').addClass('more').prepend(' ')
                            );
                            $nudge_long_description.html(response.long_description);
                        }
                        
                        // Show/hide shit.
                        $nudge_selection_elems.hide();
                        $nudge_single_elems.fadeIn(700);
                    }
                });   
            }
        }
    });
    
    $nudge_short_description.on('click', 'a.more', function() {
        $nudge_short_description.hide();
        $nudge_long_description.fadeIn(700);
    });
    
    
    
}(jQuery));