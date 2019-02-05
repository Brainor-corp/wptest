$(document).on('keyup', "input", function (event) {
    disable_button($(this));
});
$(document).on('click', "input", function (event) {
    disable_button($(this));
});

function disable_button(dex) {
    if (!checked(dex)) {
        dex.addClass('error');
    } else {
        dex.addClass('ok');
    }
    var form = dex.parents('form');
    if (form.find('.error').length === 0 && form.find('.ok').length > 0) {
        form.find('button').removeClass('error_button');
    } else {
        form.find('button').addClass('error_button');
    }
}

function checked(item) {
    item.removeClass('error');
    item.removeClass('ok');
    var type = item.attr('type');
    var val = item.val();
    switch (type) {
        case "tel":
            if (val.length === 0) return false;
            return (val.indexOf("_") < 0);
        case "hidden":
            return true;
        case "email":
            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            return reg.test(val);
        case "checkbox":
            return item.prop('checked');
        default:
            val = tgtrimm(val);
            item.val(val);
            return (val.length > 3);

    }
}

function tgtrimm(str) {
    return str.replace(/[^a-zA-ZА-Яа-яЁё]/gi, '').replace(/\s+/gi, ', ');
}


$(document).on('submit', "form", function (event) {
    event.preventDefault();
    var items = $(this).find('input');
    items.removeClass('error');
    items.removeClass('ok');
    items.each(function (i, item) {
        disable_button($(this));
    });
    if ($(this).find('.error').length === 0 && $(this).find('.ok').length > 0) {
        var this_form = $(this);
        var post_url = this_form.attr("action");
        var request_method = this_form.attr("method");
        var form_data = this_form.serialize();
        $.ajax({
            url: post_url,
            type: request_method,
            data: form_data
        }).done(function (response) {
            console.log(response);
            this_form.trigger("reset")
        });
    }
});
$(document).ready(function () {
    $("input[type=tel]").mask("+7(999)999-99-99");
});