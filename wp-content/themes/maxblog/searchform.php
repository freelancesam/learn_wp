<div class="search-form">
    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <fieldset>
        <input class="search-form-text field" type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Search this Site..','tn'); ?>">
        <input type="submit" class="search-submit" value="<?php _e('Search','tn') ?>" />
    </fieldset>
</form>
</div><!--#search form-->