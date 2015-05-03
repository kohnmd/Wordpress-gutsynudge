(function($) {
    
    var $nudge_dropdown = $('#nudge-dropdown')
        .on('chosen:showing_dropdown', function(e, chosen) {
            $('ul.chosen-results li:last-child', chosen.chosen.dropdown).addClass('ask');
        })
        .chosen({
            disable_search: true,
            placeholder_text_single: 'Where do you need a nudge?'
        })
        .on('change', function(e, params) {
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
                        if (typeof response.success != 'undefined' && response.success == true
                            && typeof response.nudge_url != 'undefined' && response.nudge_url != ""
                        ) {
                            window.location.href = response.nudge_url;
                        } else {
                            //console.log(response);
                            
                            alert("We can't find a nudge in that category! Please try again.");
                            reset_nudge_dropdown();
                        }
                    });
                } else if (params.selected == 'ask') {
                    window.location.href = '/contact/ask-a-question/';
                    
                } else {
                    //console.log(params.selected);
                    
                    alert("We can't find a nudge in that category! Please try again.");
                    reset_nudge_dropdown();
                }
            }
        });
    
    // Always reset the nudge dropdown when page first loads
    reset_nudge_dropdown();
    
    // Resets the nudge dropdown
    function reset_nudge_dropdown() {
        $('#nudge-dropdown').val('').trigger('chosen:updated');
    }
    
}(jQuery));