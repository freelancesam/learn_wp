<?php
//display comment
if ( post_password_required() ) {
    return;
}
?>
<div id="comments" class="comments-area">
    <?php if ( have_comments() ) : ?>
        <div class="comment-title widget-title">
            <h3>
                <?php
                printf( _nx( '1 comment', '%1$s comments', get_comments_number(), 'comments title', 'tn' ),
                    number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
                ?>
            </h3>
        </div>
        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
            <nav id="comment-nav-above" class="comment-navigation" role="navigation">
                <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'tn' ); ?></h1>
                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'tn' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'tn' ) ); ?></div>
            </nav><!-- #comment-nav-above -->
        <?php endif; ?>

        <ol class="comment-list clearfix">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' =>70,
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
            <nav id="comment-nav-below" class="comment-navigation" role="navigation">
                <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'tn' ); ?></h1>
                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'tn' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'tn' ) ); ?></div>
            </nav><!-- #comment-nav-below -->
        <?php endif; // check for comment navigation ?>

    <?php endif; // have_comments() ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'tn' ); ?></p>
    <?php endif; ?>
    <?php
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $args = array(
            'title_reply'=> __('Leave a Response', 'tn'),
            'comment_notes_before' => '',
            'comment_notes_after' => '',
            'comment_field' => '<p class="comment-form-comment"><label for="comment" >'.__('Comment', 'tn').'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . _x( 'Message...', 'noun', 'tn' ) . '"></textarea></p>',
            'fields' => apply_filters( 'comment_form_default_fields', array(
                'author' => '<p class="comment-form-author"><label for="author" >'.__('Name', 'tn').'</label><input id="author" name="author" type="text" placeholder="'.__('Name', 'tn').  $aria_req . ' /></p>',
                'email' => '<p class="comment-form-email"><label for="email" >'.__('Email', 'tn').'</label><input id="email" name="email" type="text" placeholder="'.__('Email', 'tn').'..." '. $aria_req .' /></p>')) ,
            'id_submit' => 'comment-submit',
            'label_submit' => __('Post Comment', 'tn'));
    } else {
        $args = array(
            'title_reply'=> __('Leave a Response', 'tn'),
            'comment_notes_before' => '',
            'comment_notes_after' => '',
            'comment_field' => '<p class="comment-form-comment"><label for="comment" >'.__('Comment', 'tn').'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . _x( 'Message', 'noun', 'tn' ) . '"></textarea></p>',
            'fields' => apply_filters( 'comment_form_default_fields', array(
                'author' => '<p class="comment-form-author"><label for="author" >'.__('Name', 'tn').'</label><input id="author" name="author" type="text" placeholder="'.__('Name', 'tn').'..." size="30" ' .  $aria_req . ' /></p>',
                'email' => '<p class="comment-form-email"><label for="email" >'.__('Email', 'tn').'</label><input id="email" name="email" size="30" type="text" placeholder="'.__('Email', 'tn').'..." '. $aria_req .' /></p>')),
            'label_submit' => __('Post Comment', 'tn'));
    };
    ?>
    <?php
    comment_form($args);
    ?>

</div><!-- #comments -->
