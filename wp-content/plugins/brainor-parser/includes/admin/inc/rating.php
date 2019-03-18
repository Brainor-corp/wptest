<?php

/**
 * Добавляем рейтинг и имя автора комментария
 */
function add_comment_rating( $comment_ID, $comment_approved ) {
	if ( isset( $_POST['rating_review'] ) && ! empty( $_POST['rating_review'] ) ) {
		add_metadata( 'comment', $comment_ID, 'rating', sanitize_text_field( $_POST['rating_review'] ), true );
	}
	if ( isset( $_POST['nic'] ) && ! empty( $_POST['nic'] ) ) {
		add_metadata( 'comment', $comment_ID, 'nic', sanitize_text_field( $_POST['nic'] ), true );
	}
}
add_action( 'comment_post', 'add_comment_rating' );

/**
 * Если пользователь не ввел имя, то вернем ошибку
 */
add_filter( 'preprocess_comment', 'priprava_isset_comment_username' );
function priprava_isset_comment_username( $commentdata ) {
	if ( empty( $_POST['nic'] ) ) {
		wp_die( '<p><strong>ОШИБКА</strong>: пожалуйста, введите имя.</p><p><a href="javascript:history.back()">← Назад</a></p>' );
	}
	return $commentdata;
}

/**
 * Блок рейтинга
 */
function priprava_rating_block() {
	ob_start(); ?>
	<div class="set_rating">
		Оцените рецепт:
		<div class="rating_review">
			<input type="radio" name="rating_review" id="rating_1" value="1" required=""><label for="rating_1" class="non_active_rating"></label>
			<input type="radio" name="rating_review" id="rating_2" value="2" required=""><label for="rating_2" class="non_active_rating"></label>
			<input type="radio" name="rating_review" id="rating_3" value="3" required=""><label for="rating_3" class="non_active_rating"></label>
			<input type="radio" name="rating_review" id="rating_4" value="4" required=""><label for="rating_4" class="non_active_rating"></label>
			<input type="radio" name="rating_review" id="rating_5" value="5" required=""><label for="rating_5" class="non_active_rating"></label>
		</div>
	</div> <?php
	return ob_get_clean();
}

/**
 * Дополнительные поля комментирования (Имя и рейтинг)
 */
add_action( 'comment_form_top', 'priprava_dop_comments_fields' );
function priprava_dop_comments_fields(){
	echo priprava_rating_block();
	if ( is_user_logged_in() ) {
		$name = get_userdata( get_current_user_id() )->display_name; ?>
		<input type="hidden" name="nic" value="<?php echo $name; ?>"><?php
	} else { ?>
		<label class="comment_username">Имя: <input type="text" name="nic"></label><?php
	}
}

function priprava_get_user_rating( $post_author, $type = false ) {
	$ret = new stdClass;

	if ( empty( $post_author ) ) {
		$post_author = get_current_user_id();
	}

	$user_like = get_the_author_meta( 'user_like', $post_author );
	$user_dish = get_the_author_meta( 'user_dish', $post_author );

	$ret->like = $user_like;
	$ret->dish = $user_dish;

	if ( $type == 'like' ) {
		return $ret->like;
	}

	if ( $type == 'dish' ) {
		return $ret->dish;
	}

	return $ret;
}