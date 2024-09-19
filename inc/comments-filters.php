<?php
/**
 * Comments filters
 */

/**
 * Modify comment form fields
 *
 * @param string $default arguments object.
 *
 * @return array arguments object
 */
function modify_comment_field( $default ) {

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );

	$fields = array(
		'author' =>
			'<input name="author" class="comment__name" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="' . __( 'Imię', 'text-domain' ) . ( $req ? ' (Required)' : '' ) . '"/>',
	);
	$args   = array(
		'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn comment__button" value="%4$s" />',
		'title_reply'          => '',
		'title_reply_to'       => /* translators: %s: titile_replay_to */ __( 'Odpowiedz %s', 'text-domain' ),
		'cancel_reply_link'    => __( 'Anuluj', 'text-domain' ),
		'label_submit'         => __( 'Dodaj', 'text-domain' ),
		'format'               => 'xhtml',
		'comment_field'        => '<textarea id="comment" class="comment__textarea" name="comment" placeholder="' . __( 'Twój komentarz', 'text-domain' ) . '" cols="45" rows="8" aria-required="true"></textarea>',
		'comment_notes_before' => '',
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
	);
	return array_merge( $default, $args );
}
add_filter( 'comment_form_defaults', 'modify_comment_field' );

add_action(
	'set_comment_cookies',
	function( $comment, $user ) {
		setcookie( 'ta_comment_wait_approval', '1' );
	},
	10,
	2
);

add_action(
	'init',
	function() {
		if ( isset( $_COOKIE['ta_comment_wait_approval'] ) && '1' === $_COOKIE['ta_comment_wait_approval'] ) {
			setcookie( 'ta_comment_wait_approval', null, time() - 3600, '/' );
			add_action(
				'comment_form_before',
				function() {
					echo "<span id='wait_approval' class='comment-send'>Twój komentarz został wysłany. Będzie widoczny po zatwierdzeniu przez moderatora.</span>";
				}
			);
		}
	}
);

/**
 * Redirect location after submit form
 *
 * @return array location array
 */
add_filter(
	'comment_post_redirect',
	function( $location, $comment ) {
		$location = get_permalink( $comment->comment_post_ID ) . '#wait_approval';
		return $location;
	},
	10,
	2
);

add_filter( 'comment_form_logged_in', '__return_empty_string' );
