<?php
global $options;
$options = get_option('wooshortcodes');
?>
<form method="post" id="mainform" action="">
    <table class="ciusan-plugin widefat" style="margin-top:50px;">
        <thead>
            <tr>
                <th scope="col" colspan="2">Shortcode Category Settings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="titledesc">Url category page</td>
                <td class="forminp">
                    <input name="url_cate" id="url_cate" style="width:250px;" value="<?php echo $options['url_cate']; ?>" type="text" class="required">
                </td>
            </tr>
            <tr>
                <td class="titledesc">Category id</td>
                <td class="forminp">
                    <?php
                    $taxonomy = 'product_cat';
                    $orderby = 'name';
                    $show_count = 0;      // 1 for yes, 0 for no
                    $pad_counts = 0;      // 1 for yes, 0 for no
                    $hierarchical = 1;      // 1 for yes, 0 for no  
                    $title = '';
                    $empty = 0;

                    $args = array(
                        'taxonomy' => $taxonomy,
                        'orderby' => $orderby,
                        'show_count' => $show_count,
                        'pad_counts' => $pad_counts,
                        'hierarchical' => $hierarchical,
                        'title_li' => $title,
                        'hide_empty' => $empty
                    );
                    $arrCats = get_categories($args);
                    $arrcateId = explode(',', $options['cateId']);
                    foreach ($arrCats as $category) {
                        $checked = in_array($category->cat_ID, $arrcateId) ? 'checked=""' : '';
                        ?>
                        <div style="padding-bottom: 5px;">
                            <input name="cateId[]" id="cateId" value="<?php echo $category->cat_ID ?>" <?php echo $checked;?> type="checkbox" class="required"> <label style="padding-left: 20px"><?php echo $category->name ?></label>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="action" value="update" />
    <p class="submit"><input type="submit" name="save" id="submit" class="button button-primary" value="Save Changes"/></p>
</form>
</div>

<div class="wrap"><hr /></div>