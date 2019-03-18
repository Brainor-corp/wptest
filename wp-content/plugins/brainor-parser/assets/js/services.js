$(document).ready(function () {
    $(document).on('click', '#add-service-btn', function (e) {
        e.preventDefault();
        var lastId = $( '.service-item' ).filter( ':last' ).data('bmcId');
        var nextId = lastId+1;
        var lastPriority = $( '.service-item' ).filter( ':last' ).data('bmcOrderId');
        var nextPriority = lastPriority+1;
        var url = window.location.href;
        var html =
            '<div id="service-wraper-'+nextId+'" class="new-form-add service-item sortable-item" data-bmc-id="'+nextId+'" data-bmc-order-id="'+nextPriority+'">'+
                '<span type="button" class="sortable-item-name" data-toggle="collapse" data-target="#service-'+nextPriority+'">'+
                    'Новый пакет'+
                '</span>'+
                '<div id="service-'+nextPriority+'" class="collapse in">'+
                    '<form id="service-form-'+nextId+'" class="create-service-form" role="form" method="post" action="'+url+'">'+
                        '<input type="hidden" hidden="hidden" id="action" name="action" value="edit">'+
                        '<input type="hidden" hidden="hidden" id="row_id" name="row_id" value="'+nextId+'">'+
                        '<input type="hidden" hidden="hidden" id="row_order_id" name="row_order_id" value="'+nextPriority+'">'+
                        '<div class="form-group">'+
                            '<label for="name">Название</label>'+
                            '<input type="text" class="form-control" id="name"  placeholder="Название">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="description">Описание</label>'+
                            '<textarea class="form-control" id="description" placeholder="Описание"></textarea>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="price">Цена</label>'+
                            '<input type="text" class="form-control" id="price" name="price" placeholder="Цена">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="packages">Пакеты</label>'+
                            '<button type="button" id="add-service-package-btn" class="btn btn-primary">Добавить пакет</button>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="icon_class">Количественное </label>'+
                            '<input type="checkbox" style="margin-top: 0" class="form-control" id="need_quantity" name="need_quantity">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="url">Url на описание</label>'+
                            '<input type="text" class="form-control" id="url"  placeholder="Url на описание">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="icon_class">Класс иконки</label>'+
                            '<input type="text" class="form-control" id="icon_class" name="icon_class" placeholder="Класс иконки">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="icon_path">Url иконки</label>'+
                            '<input type="text" class="form-control" id="icon_path"  placeholder="Название">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="color">Цвет</label>'+
                            '<input type="text" class="form-control" id="color" name="color"  placeholder="Цвет">'+
                        '</div>'+
                        '<button type="submit" class="btn btn-success">Сохранить</button>'+
                    '</form>'+
                '</div>'+
            '</div><br>';
        $(this).before(html);
    });

    $(document).on('click', '#add-service-package-btn', function (e) {
        e.preventDefault();
        var packagesOptions = $( '#packages-options' ).html();
        var lastId = $( '.service-package-item' ).filter( ':last' ).data('bmcId');
        var nextId = lastId+1;
        var html =
            '<div class="row service-package-item service-package-wrapper" data-bmc-id="'+nextId+'">'+
                '<div class="col-xs-5">'+
                    ' <select class="form-control packages-data" id="packages" data-ajax-type="package_id" data-ajax-id="'+nextId+'" name="packages['+nextId+'][package_id]">'+
                        packagesOptions+
                    '</select>'+
                '</div>'+
                '<div class="col-xs-4">'+
                    '<label for="required-'+nextId+'">Обязательное </label> '+
                    '<input style="margin-top: 0" type="checkbox" class="form-control packages-data" id="required" data-ajax-type="required" data-ajax-id="'+nextId+'" name="packages['+nextId+'][required]">'+
                '</div>'+
                '<div class="col-xs-3">' +
                    '<button type="button" class="btn btn-danger" id="service-package-delete-btn">Удалить</button>' +
                '</div>'+
            '<br></div>';
        $(this).before(html);
    });

    $(document).on('submit', '.create-service-form', function (e) {
        e.preventDefault();
        var form_id = $(this).attr('id');
        var row_id = $(this).find('input[id="row_id"]').val();
        var name = $(this).find('input[id="name"]').val();
        var description = $(this).find('textarea[id="description"]').val();
        var form = document.getElementById(form_id),
            packages = form.getElementsByClassName("packages-data"),
            packagesArr = new Object();
        for(var i=0, len=packages.length; i<len; i++){
            var ajaxId = packages[i].getAttribute('data-ajax-id');
            var ajaxType = packages[i].getAttribute('data-ajax-type');
            if(typeof packagesArr[ajaxId] == 'undefined'){
                packagesArr[ajaxId]=new Object();
                packagesArr[ajaxId]['package_id']='';
                packagesArr[ajaxId]['required']=0;
            }
            if(ajaxType == 'required'){
                if (packages[i].checked)
                {
                    var checkedValue = 1;
                }
                else{
                    var checkedValue = 0;
                }
                packagesArr[ajaxId][ajaxType] = checkedValue;
            }
            else{
                packagesArr[ajaxId][ajaxType] = packages[i].value;
            }
        }
        var need_quantity = $(this).find('input[id="need_quantity"]').val();
        var url = $(this).find('input[id="url"]').val();
        var color = $(this).find('input[id="color"]').val();
        var icon_class = $(this).find('input[id="icon_class"]').val();
        var icon_path = $(this).find('input[id="icon_path"]').val();
        var price = $(this).find('input[id="price"]').val();
        var data = {
            action: 'bmc_service_create',
            row_id: row_id,
            name: name,
            description: description,
            packages: packagesArr,
            need_quantity: need_quantity,
            price: price,
            url: url,
            color: color,
            icon_class: icon_class,
            icon_path: icon_path,
        };
        document.getElementById('ajax-loading-gif').style.display = 'block';
        $.post( ajaxurl, data, function(response) {
            $('.new-form-add').remove();
            $('.all-services-wrapper').html(response);
            document.getElementById('ajax-loading-gif').style.display = 'none';
        });
    });

    $(document).on('submit', '.edit-service-form', function (e) {
        e.preventDefault();
        var form_id = $(this).attr('id');
        var row_id = $(this).find('input[id="row_id"]').val();
        var name = $(this).find('input[id="name"]').val();
        var description = $(this).find('textarea[id="description"]').val();
        var form = document.getElementById(form_id),
            packages = form.getElementsByClassName("packages-data"),
            packagesArr = new Object();
        for(var i=0, len=packages.length; i<len; i++){
            var ajaxId = packages[i].getAttribute('data-ajax-id');
            var ajaxType = packages[i].getAttribute('data-ajax-type');
            if(typeof packagesArr[ajaxId] == 'undefined'){
                packagesArr[ajaxId]=new Object();
                packagesArr[ajaxId]['package_id']='';
                packagesArr[ajaxId]['required']=0;
            }
            if(ajaxType == 'required'){
                if (packages[i].checked)
                {
                    var checkedValue = 1;
                }
                else{
                    var checkedValue = 0;
                }
                packagesArr[ajaxId][ajaxType] = checkedValue;
            }
            else{
                packagesArr[ajaxId][ajaxType] = packages[i].value;
            }
        }
        var need_quantity = $(this).find('input[id="need_quantity"]').val();
        var url = $(this).find('input[id="url"]').val();
        var color = $(this).find('input[id="color"]').val();
        var icon_class = $(this).find('input[id="icon_class"]').val();
        var icon_path = $(this).find('input[id="icon_path"]').val();
        var price = $(this).find('input[id="price"]').val();
        var data = {
            action: 'bmc_service_edit',
            row_id: row_id,
            name: name,
            description: description,
            packages: packagesArr,
            need_quantity: need_quantity,
            price: price,
            url: url,
            color: color,
            icon_class: icon_class,
            icon_path: icon_path,
        }
        document.getElementById('ajax-loading-gif').style.display = 'block';
        $.post( ajaxurl, data, function(response) {
            $('.all-services-wrapper').html(response);
            document.getElementById('ajax-loading-gif').style.display = 'none';
        });
    });

    $(document).on('click', '#service-package-delete-btn', function (e) {
        e.preventDefault();
        var service_id = $(this).data('rowId');
        var package_id = $(this).data('packageId');

        $(this).parent().parent().remove();
    });

    $(document).on('click', '#service-delete-btn', function (e) {
        e.preventDefault();
        var row_id = $(this).data('rowId');
        var data = {
            action: 'bmc_service_delete',
            row_id: row_id,
        };
        document.getElementById('ajax-loading-gif').style.display = 'block';
        $.post( ajaxurl, data, function(response) {
            $('.all-services-wrapper').html(response);
            document.getElementById('ajax-loading-gif').style.display = 'none';
        });
    });

    $( function() {
        $( ".sortable" ).sortable({
            connectWith:'.sortable',
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,
            update: function( event, ui ) {//стартует и при изменении уровня списка и при изменении положения в списке
                if (this === ui.item.parent()[0]){//стартуем 1 раз
                    var row_id = ui.item.data("bmcId");
                    var prev_id = $(ui.item).prev('li').data("bmcId");
                    if(typeof prev_id == 'undefined'){prev_id = 0;}
                    var next_id = $(ui.item).next('li').data("bmcId");
                    var row_priority = ui.item.data("bmcPriority");
                    var prev_priority = $(ui.item).prev('li').data("bmcPriority");
                    if(typeof prev_priority == 'undefined'){prev_priority = 0;}
                    var next_priority = $(ui.item).next('li').data("bmcPriority");
                    // alert(row_id +'-'+ prev_id+'-'+ next_id);
                    var data = {
                        action: 'bmc_services_sort',
                        row_id: row_id,
                        prev_id: prev_id,
                        next_id: next_id,
                        row_priority: row_priority,
                        prev_priority: prev_priority,
                        next_priority: next_priority,
                    };
                    $.post( ajaxurl, data, function(response) {
                        $('.all-services-wrapper').html(response);
                    });
                }
            }
        });
        $( ".sortable" ).disableSelection();
    } );
});