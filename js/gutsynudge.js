(function($) {
    
    $('#nudge-category').chosen({
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
                    
                });
                
            }
        }
    });
    
    
    
}(jQuery));