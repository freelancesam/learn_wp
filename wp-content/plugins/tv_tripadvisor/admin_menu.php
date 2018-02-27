<?php
global $options;
$options = get_option('tripadshortcodes');
?>
<form method="post" id="mainform" action="">
    <table class="ciusan-plugin widefat" style="margin-top:50px;">
        <thead>
            <tr>
                <th scope="col" colspan="2">Tripadvisor Shortcode Settings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="titledesc">Tripadvisor active</td>
                <td class="forminp">
                    <input name="tripadvisor_active" <?php echo $options['tripadvisor_active'] ? 'checked=""' : ''; ?>type="checkbox" value="1"/>
                </td>
            </tr>
            <tr>
                <td class="titledesc">Tripadvisor code</td>
                <td class="forminp">
                    <?php // wp_editor($options['tripadvisor_code'], 'tripadvisor_code', $settings = array()); ?> 
                    <textarea name="tripadvisor_js" cols="200" rows="10"><?php echo $options['tripadvisor_js']; ?></textarea>
                    <!--<textarea name="tripadvisor_code" cols="200" rows="10"><?php //echo $options['tripadvisor_code']; ?></textarea>-->
                </td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="action" value="update" />
    <p class="submit"><input type="submit" name="save" id="submit" class="button button-primary" value="Save Changes"/></p>
</form>
</div>

<div class="wrap"><hr /></div>