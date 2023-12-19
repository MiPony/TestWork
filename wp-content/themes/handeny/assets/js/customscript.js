jQuery(function($){
    //Update custom featured image
    $('body').on('click', '.custom_upload_image_button', function(e){
        e.preventDefault();
            var button = $(this),
                custom_uploader = wp.media({
            title: 'Insert image',
            library : {
                type : 'image'
            },
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
        })
        .open();
    });

    //Remove image event
    $('body').on('click', '.custom_remove_image_button', function(){
        $(this).hide().prev().val('').prev().addClass('button').html('Upload image');
        let post_id = $('#post_ID')[0].value

        data = {
            action : 'remove_custom_image',
            post_id : post_id
        };
        $.post( ajaxurl, data, function( response ) {
            coonsole.log("Removed custom image!")
          }
        )
        return false;
    });

    //Remove custom fields
    $('.remove_custom_field_button').click(function(e) {
        e.preventDefault();

        $('#custom_product_select_field').prop('selectedIndex',0);
        $('#_custom_product_date_field').val('').attr('type', 'text').attr('type', 'date');
        $('.custom__remove_image_button').hide().prev().val('').prev().addClass('button').html('Upload image');
        let post_id = $('#post_ID')[0].value

        data = {
            action : 'remove_custom_fields',
            post_id : post_id
        };
        $.post( ajaxurl, data, function( response ) {
            coonsole.log("Removed custom fields!")
          }
        )
    });

    //Update product page
    jQuery('.metabox_submit').click(function(e) {
        e.preventDefault();
        jQuery('#publish').click();
    });
});