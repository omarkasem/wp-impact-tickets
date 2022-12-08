jQuery( document ).ready( function ($) {
	'use strict';
    
    $('.performer,.category,.venue,.city').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
         {
           $('.sg_search').click();
           return false;  
         }
       });   
       


    $('.sg_search').on('click',function(e){
        e.preventDefault();
        var performer = $('.performer').val();
        var category = $('.category').val();
        var venue = $('.venue').val();
        var city = $('.city').val();

        var vars = {};
        vars.performer = performer;
        vars.category = category;
        vars.venue = venue;
        vars.city = city;

        jQuery.ajax({
            type: "POST",
            url: wp_impact.ajax_url,
            data: { action: 'wp_impact_search',vars:vars},
            success: function(response){
                $('.sg_shortcodes .results').html(response.data.output);
            },
        });


    });


    $('.sg_shortcodes .sg_generate').click(function(e){
        e.preventDefault();
        var performer = $('.performer').val();
        var category = $('.category').val();
        var venue = $('.venue').val();
        var city = $('.city').val();

        var shortcode_div ='';
        if(performer){
            shortcode_div+= 'performer="'+performer+'"';
        }
        if(category){
            shortcode_div+= ' category="'+category+'"';
        }
        if(venue){
            shortcode_div+= ' venue="'+venue+'"';
        }
        if(city){
            shortcode_div+= ' city="'+city+'"';
        }

        var shortcode = `<li><input type="text" disabled value='[WP_IMPACT_TICKETS `+shortcode_div+`]'><button class="sg_copy">Copy</button></li>`;
        $('.sg_shortcodes ul.shortcodes').prepend(shortcode);
    });

    $(document).on("click",".sg_shortcodes .sg_copy",function(e) {
        e.preventDefault();
        var input = $(this).parent().find('input');
        console.log(input.val());
        copyToClipboard(input);
    });

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();
    }

});
