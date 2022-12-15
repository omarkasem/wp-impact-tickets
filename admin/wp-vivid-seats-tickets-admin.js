jQuery( document ).ready( function ($) {
	'use strict';
    
    $('.performer,.category,.venue,.city,.keyword').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
         {
           $('.vst_search').click();
           return false;  
         }
       });   
       


    $('.vst_search').on('click',function(e){
        e.preventDefault();
        var loader = $('.vst_search img');
        loader.show();
        var performer = $('.performer').val();
        var keyword = $('.keyword').val();
        var category = $('.category').val();
        var venue = $('.venue').val();
        var city = $('.city').val();

        var vars = {};
        vars.performer = performer;
        vars.keyword = keyword;
        vars.category = category;
        vars.venue = venue;
        vars.city = city;

        jQuery.ajax({
            type: "POST",
            url: wp_vivid_seats.ajax_url,
            data: { action: 'wp_vivid_seats_search',vars:vars},
            success: function(response){
                loader.hide();
                $('.vst_block .results').html(response.data.output);
            },
        });


    });


    $('.vst_generate').click(function(e){
        e.preventDefault();
        var performer = $('.performer').val();
        var keyword = $('.keyword').val();
        var category = $('.category').val();
        var venue = $('.venue').val();
        var city = $('.city').val();

        var shortcode_div ='';
        if(performer){
            shortcode_div+= 'performer="'+performer+'"';
        }
        if(keyword){
            shortcode_div+= 'keyword="'+keyword+'"';
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

        var shortcode = `<li><input type="text" disabled value='[WP_VIVID_SEATS_TICKETS `+shortcode_div+`]'><button class="sg_copy">Copy</button></li>`;
        $('ul.vst_shortcodes').prepend(shortcode);
    });

    $(document).on("click",".vst_block .sg_copy",function(e) {
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
