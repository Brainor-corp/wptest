;jQuery(document).ready(function($) {
	$('body').on('click', '#compositions .button_parse', function() {
		var composition = $('#compositions textarea').val();
		var data = {
			'action': 'priprava_admin_table_load',
			'composition': composition
		};
		$.ajax({
			url: 'admin-ajax.php',
			data: data,
			type:'POST',
			beforeSend: function() {
				preloader_show();
			},
			success:function(data) {
				console.log(data);
				if(data) {
					data = JSON.parse(data);
					if(data.success) {
						$('.ajax_response').html(data.success).find('#composition_parsed');
					}
				}
				preloader_hide();
			}
		});
	});

	$('body').on('click', '.button_save', function() {
		var $table = $('#composition_parsed');
		var compositions = [];
		var post_id = $('#post_ID').val();
		if($table.length) {
			/*$table.find('tr').each(function(i, el) {
				compositions[i] = [];
				var children = $(el).children();
				children.each(function(i2, el2) {
					var $input = $(el2).find('input');
					if($input.length) {
						if($input.prop('type') == 'checkbox') {
							compositions[i].push($input.prop('checked'));
						} else {
							compositions[i].push( $input.val() );
						}
					}
				});
			});*/

			$table.find('tr').each(function(i, el) {
				var thname = $(el).find('.ing_name').val();
				if(thname != undefined && thname != "") {
					compositions[i] = {
						"name": $(el).find('.ing_name').val(),
						"notice": $(el).find('.ing_notice').val(),
						"count": $(el).find('.ing_count').val(),
						"units": $(el).find('.ing_units').val(),
						"main": $(el).find('.ing_main').prop('checked')
					}
				}
			});

			// console.log(compositions);
			// return;
			var data = {
				'action': 'priprava_save_compositions_table',
				'compositions': compositions,
				'post_id': post_id
			};

			if($(this).hasClass('pre_comp_save')) {
				data.upgrade = true;
				data.upgrade_data = {};
				if($('.new_ing').length) {
					data.upgrade_data.new_ing_rm = [];
					$('.new_ing').find('tr').each(function(index, el) {
						if( $(el).find('input:checked').data('type') == 'N' ) {
							data.upgrade_data.new_ing_rm.push( $(el).find('.ingredients_name').text() );
						}
					});
				}
				if($('.con_ing').length) {
					data.upgrade_data.con_ing = [];
					$('.con_ing').find('tr').each(function(index, el) {
						if( $(el).find('input:checked').data('type') == 'Y' ) {
							data.upgrade_data.con_ing.push( $(el).find('.ingredients_oldname').text() + '***' + $(el).find('.ingredients_notice').val() );
						}
					});
				}
			}

			$.ajax({
				url: 'admin-ajax.php',
				data: data,
				type:'POST',
				beforeSend: function() {
					preloader_show();
					console.log(data);
				},
				success:function(data){
					if(data) {
						data = JSON.parse(data);

						if(data.error) {
							console.log(data.error);
							// preloader_hide();
							return;
						}

						if(data.success){
							$('#composition_parsed').html(data.success);
						}
						if(data.modal) {
							$('#composition_parsed').append(data.modal);
						}
					} else {
						$('#composition_parsed').html('Состав не найден или не верный формат записи!');
					}
					preloader_hide();
				}
			});
		}
	});

	$('body').on('click', '.button_add_row', function() {
		var row = $('#composition_parsed tr').eq(1);
		if (row.length) {
			row.clone(true).appendTo('#composition_parsed tbody').removeClass('template');
		}
	});

	$('body').on('click', '.button_remove', function() {
		var $th = $(this);
		console.log($th.closest('table').find('tr').length);
		if ($th.closest('table').find('tr').length < 4) {
			$('.ajax_response').html('');
		} else {
			$th.closest('tr').remove();
		}
	});

	$.xhrPool = [];
	$.xhrPool.abortAll = function() {
	    $(this).each(function(idx, jqXHR) {
	        jqXHR.abort();
	    });
	    $.xhrPool.length = 0
	};

	// Предиктивный поиск
	$('body').on('keyup', '#composition_parsed input', function() {
		if ($(this).val().length < 3 || ! $(this).hasClass('ing_name')) {
			return;
		}
		predictive_search_modal_close();
		var $th = $(this);
		var data = {
			'action': 'priprava_predictive_search',
			'search': $th.val()
		};
		$.ajax({
			url: 'admin-ajax.php',
			data: data,
			type:'POST',
			beforeSend: function(jqXHR) {
				$.xhrPool.push(jqXHR);
			},
			success:function(data, jqXHR){
				if (data) {
			        $th.parent().append(data).bind('mouseleave', $(this), function() {
			        	$(this).find('.predictive_search_modal').fadeOut('400', function() {
			        		$(this).remove();
			        	});
			        });
				} else {
					predictive_search_modal_close();
				}

				var index = $.xhrPool.indexOf(jqXHR);
		        if (index > -1) {
		            $.xhrPool.splice(index, 1);
		        }
			}
		});
	});

	$('body').on('click', '.predictive_search_modal div', function() {
		var $th = $(this);
		$th.closest('td').find('input').val($th.text());
		predictive_search_modal_close();
	});

	$('body').on('click', '.compositions-overlay .close', function() {
		$(this).closest('.compositions-overlay').fadeOut('400', function() {
			$(this).remove();
		});
	});

	function predictive_search_modal_close() {
		$('.predictive_search_modal').fadeOut('400', function() {
			$(this).remove();
		});
	}

	fnDelay = (function(){
	    var timer = 0;
	    return function(callback, ms){
	        clearTimeout(timer);
	        timer = setTimeout(callback, ms);
	    };
	})();

	function preloader_show() {
		$('body').addClass('loading');
	}

	function preloader_hide() {
		$('body').removeClass('loading');
	}
	
	var frame,
		$imgwrap = $('.image_block'),
		$imgid   = $('#image_id');

	// добавление
	$('.image_block img').click( function(ev){
		ev.preventDefault();
	
		if( frame ){ frame.open(); return; }
	
		// задаем media frame
		frame = wp.media.frames.questImgAdd = wp.media({
			states: [
				new wp.media.controller.Library({
					title:    'Featured Image',
					library:   wp.media.query({ type: 'image' }),
					multiple: false,
					//date:   false
				})
			],
			button: {
				text: 'Set featured image', // Set the text of the button.
			}
		});
	
		// выбор
		frame.on('select', function(){
			var selected = frame.state().get('selection').first().toJSON();
			if( selected ){
				$imgid.val( selected.id );
				$imgwrap.find('img').attr('src', selected.sizes.thumbnail.url );
				
				updateImage('saveIngredientImage', selected.id);
			}
		} );
	
		// открываем
		frame.on('open', function(){
			if( $imgid.val() ) frame.state().get('selection').add( wp.media.attachment( $imgid.val() ) );
		});
	
		frame.open();
	});
	
	// удаление
	$('.image_remove').click(function(){
		updateImage('removeIngredientImage', function() {
			$imgid.val('');
			$imgwrap.find('img').attr('src','data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQMAAABKLAcXAAAABlBMVEUAAAC7u7s37rVJAAAAAXRSTlMAQObYZgAAACJJREFUOMtjGAV0BvL/G0YMr/4/CDwY0rzBFJ704o0CWgMAvyaRh+c6m54AAAAASUVORK5CYII=');
		});
	});
	
	function updateImage(action, image_id, f) {
		var ingredient_id = $('input[name="ingredient"]').val();
		
		f = f || function() {};
		image_id = image_id || 0;
		
    	if(!ingredient_id) return;
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: action,
				ingredient_id: ingredient_id,
				image_id: image_id
			},
	    	beforeSend: function() {
	    		preloader_show();
	    	},
			success: function(data) {
				console.log(data);
				f();
				preloader_hide();
			}
		});
	}

});