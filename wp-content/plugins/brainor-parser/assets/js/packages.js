$(document).ready(function () {
    $(document).on('click', '#add-package-btn', function (e) {
        e.preventDefault();
        var lastId = $( '.package-item' ).filter( ':last' ).data('bmcId');
        var nextId = lastId+1;
        var lastPriority = $( '.package-item' ).filter( ':last' ).data('bmcPriority');
        var nextPriority = lastPriority+1;
        var url = window.location.href;
        var html =
            '<div id="package-wraper-'+nextId+'" class="new-form-add package-item sortable-item" data-bmc-id="'+nextId+'" data-bmc-priority="'+nextPriority+'">'+
                '<span type="button" class="sortable-item-name" data-toggle="collapse" data-target="#package-'+nextPriority+'">'+
                    'Новый пакет'+
                '</span>'+
                '<div id="package-'+nextPriority+'" class="collapse in">'+
                    '<form class="create-package-form" role="form" method="post" action="'+url+'">'+
                        '<input type="hidden" hidden="hidden" id="action" name="action" value="edit">'+
                        '<input type="hidden" hidden="hidden" id="row_id" name="row_id" value="'+nextId+'">'+
                        '<input type="hidden" hidden="hidden" id="row_priority" name="row_priority" value="'+nextPriority+'">'+
                        '<div class="form-group">'+
                            '<label for="name">Название</label>'+
                            '<input type="text" class="form-control" id="name"  placeholder="Название">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="description">Описание</label>'+
                            '<textarea class="form-control" id="description" placeholder="Описание"></textarea>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="icon_class">Класс иконки</label>'+
                            '<input type="text" class="form-control" id="icon_class"  placeholder="Название">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<label for="icon_path">Url иконки</label>'+
                            '<input type="text" class="form-control" id="icon_path"  placeholder="Название">'+
                        '</div>'+
                        '<button type="submit" class="btn btn-success">Сохранить</button>'+
                    '</form>'+
                '</div>'+
            '</div><br>';
        $(this).before(html);
    });

    $(document).on('submit', '.create-package-form', function (e) {
        e.preventDefault();

        var row_id = $(this).find('input[id="row_id"]').val();
        var name = $(this).find('input[id="name"]').val();
        var description = $(this).find('textarea[id="description"]').val();
        var icon_class = $(this).find('input[id="icon_class"]').val();
        var icon_path = $(this).find('input[id="icon_path"]').val();
        var data = {
            action: 'bmc_project_create',
            row_id: row_id,
            name: name,
            description: description,
            icon_class: icon_class,
            icon_path: icon_path,
        };
        document.getElementById('ajax-loading-gif').style.display = 'block';
        $.post( ajaxurl, data, function(response) {
            $('.new-form-add').remove();
            $('.all-packages-wrapper').html(response);
            document.getElementById('ajax-loading-gif').style.display = 'none';
        });
    });

    $(document).on('submit', '.edit-package-form', function (e) {
        e.preventDefault();

        var row_id = $(this).find('input[id="row_id"]').val();
        var name = $(this).find('input[id="name"]').val();
        var description = $(this).find('textarea[id="description"]').val();
        var icon_class = $(this).find('input[id="icon_class"]').val();
        var icon_path = $(this).find('input[id="icon_path"]').val();
        var data = {
            action: 'bmc_project_edit',
            row_id: row_id,
            name: name,
            description: description,
            icon_class: icon_class,
            icon_path: icon_path,
        };
        document.getElementById('ajax-loading-gif').style.display = 'block';
        $.post( ajaxurl, data, function(response) {
            $('.all-packages-wrapper').html(response);
            document.getElementById('ajax-loading-gif').style.display = 'none';
        });
    });

    $(document).on('click', '#project-delete-btn', function (e) {
        e.preventDefault();

        var row_id = $(this).data('rowId');
        var data = {
            action: 'bmc_project_delete',
            row_id: row_id,
        };
        document.getElementById('ajax-loading-gif').style.display = 'block';
        $.post( ajaxurl, data, function(response) {
            $('.all-packages-wrapper').html(response);
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
                        action: 'bmc_project_sort',
                        row_id: row_id,
                        prev_id: prev_id,
                        next_id: next_id,
                        row_priority: row_priority,
                        prev_priority: prev_priority,
                        next_priority: next_priority,
                    };
                    document.getElementById('ajax-loading-gif').style.display = 'block';
                    $.post( ajaxurl, data, function(response) {
                        $('.all-packages-wrapper').html(response);
                        document.getElementById('ajax-loading-gif').style.display = 'none';
                    });
                }
            }
        });
        $( ".sortable" ).disableSelection();
    } );
});