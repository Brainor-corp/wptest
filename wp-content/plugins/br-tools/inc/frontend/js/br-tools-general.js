$(document).ready(function () {

    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    $('.br-tools').on('click', '.tablinks', function (e) {
        openTab(e, $(this).data('tab-id'));
    });

    $('.br-tools').on('change', '.br-mark-radio', function () {
        let mark = $(this).val();

        $.ajax({
            type: 'POST',
            url: $('.br-tools input[name="br-wp-admin-ajax-url"]').val(),
            data: {
                action: 'br_tools_get_car_models',
                mark: mark
            },
            dataType: 'html',
            beforeSend: function() {
                $('.br-tools-input').attr('disabled', true);
                $(".br-tools .br-products").hide(200);
            },
            success: function(data){
                $(".br-tools .br-models").hide(200, function() {
                    $(this).html(data).show(200);
                });
                $('.br-tools-input').attr('disabled', false);
            },
            error: function (data, error, error2) {
                $('.br-tools-input').attr('disabled', false);
                console.log('error');
                console.log(data);
                console.log(error);
                console.log(error2);
            }
        });
    });

    $('.br-tools').on('change', '.br-model-checkbox', function () {
        let models = [];

        $(".br-model-checkbox:checked").each(function() {
            models.push($(this).val());
        });

        let mark = $('input[name=br-mark]').val();

        console.log(models);

        $.ajax({
            type: 'POST',
            url: $('.br-tools input[name="br-wp-admin-ajax-url"]').val(),
            data: {
                action: 'br_tools_get_products',
                models: models,
                mark: mark
            },
            dataType: 'html',
            beforeSend: function() {
                $('.br-tools-input').attr('disabled', true);
            },
            success: function(data){
                $(".br-tools .br-products").hide(200, function() {
                    $(this).html(data).show(200);
                });
                $('.br-tools-input').attr('disabled', false);
            },
            error: function (data, error, error2) {
                $('.br-tools-input').attr('disabled', false);
                console.log('error');
                console.log(data);
                console.log(error);
                console.log(error2);
            }
        });
    });

    $(document).on('submit', '#br-search-form', function (e) {
        e.preventDefault();

        let form = $(this);

        $.ajax({
            type: 'POST',
            url: $('.br-tools input[name="br-wp-admin-ajax-url"]').val(),
            data: {
                action: 'br_tools_search_products',
                params: form.serialize()
            },
            dataType: 'html',
            beforeSend: function() {
                $('.br-tools-input').attr('disabled', true);
            },
            success: function(data){
                $(".br-tools .br-products").hide(200, function() {
                    $(this).html(data).show(200);
                });
                $('.br-tools-input').attr('disabled', false);
            },
            error: function (data, error, error2) {
                $('.br-tools-input').attr('disabled', false);
                console.log('error');
                console.log(data);
                console.log(error);
                console.log(error2);
            }
        });
    })
});