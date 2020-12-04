!function($) {
    $('.rf-vc-image-select .rf-vc-image-option').click(function(e){
        e.preventDefault();
        var $selectField = $(this).parent();
        $(this).addClass('selected');
        $(this).siblings().removeClass('selected');
        var $field = $selectField.find('.image_select_field');
        $field.val( $(this).data('template') ).trigger("change");
    });
}(window.jQuery);