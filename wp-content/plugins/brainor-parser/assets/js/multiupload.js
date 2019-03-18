jQuery(function($) {
 
    $('.repeatable-add').click(function(e) {
        e.preventDefault();
        tb_show('Добавить изображение', 'media-upload.php?type=image&referer=priprava_gallery&TB_iframe=true');
        window.send_to_editor = function(html) {
            if($(html).length) {
                field = $('.for_clone').clone(true).removeClass('for_clone').addClass('new');
                $('.custom_repeatable').append(field);
                formfield = $('.custom_upload_image:last');
                preview = $('.custom_upload_image:last').parent("div").siblings('img');
                fileUrl = $(html).attr('src');
                formfield.val(fileUrl);
                preview.attr('src', fileUrl);
                tb_remove();
            }
        }
    });
     
    $('.added-image-block-remove').click(function(){
        $(this).closest('li').remove();
        return false;
    });

    $('.custom_repeatable').sortable();
    $('.custom_repeatable').disableSelection();
 
});