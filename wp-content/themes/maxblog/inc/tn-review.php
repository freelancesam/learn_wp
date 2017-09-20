<?php
//check review
if (!function_exists('tn_check_review')) {
    function tn_check_reviews($post_id)
    {
        $check = false;
        $tn_as = get_post_meta($post_id, 'tn_as', true);
        $tn_enable_review = get_post_meta($post_id, 'tn_enable_review', true);
        if (!empty($tn_as) && !empty($tn_enable_review))
            $check = true;
        return $check;
    }
}

//render single review
if (!function_exists('tn_render_single_review')) {
    function tn_render_single_review($post_id)
    {
        $str = '';
        $tn_review_summary = get_post_meta($post_id, 'tn_review_summary', true);
        $tn_as = get_post_meta($post_id, 'tn_as', true);
        $tn_review_data = array(
            array(
                'tn_cd' => get_post_meta($post_id, 'tn_cd1', true),
                'tn_cs' => get_post_meta($post_id, 'tn_cs1', true),
            ),
            array(
                'tn_cd' => get_post_meta($post_id, 'tn_cd2', true),
                'tn_cs' => get_post_meta($post_id, 'tn_cs2', true),
            ),
            array(
                'tn_cd' => get_post_meta($post_id, 'tn_cd3', true),
                'tn_cs' => get_post_meta($post_id, 'tn_cs3', true),
            ),
            array(
                'tn_cd' => get_post_meta($post_id, 'tn_cd4', true),
                'tn_cs' => get_post_meta($post_id, 'tn_cs4', true),
            ),
            array(
                'tn_cd' => get_post_meta($post_id, 'tn_cd5', true),
                'tn_cs' => get_post_meta($post_id, 'tn_cs5', true),
            ),
            array(
                'tn_cd' => get_post_meta($post_id, 'tn_cd6', true),
                'tn_cs' => get_post_meta($post_id, 'tn_cs6', true),
            ),

        );
        $str .= '<div class="single-review-wrap" itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating">';
        $str .= '<meta itemprop="worstRating" content="1">';
        $str .= '<meta itemprop="bestRating" content="10">';
        $str .= '<div class="single-review-title widget-title"><h3>' . __('Review overview', 'tn') . '</h3></div>';
        $str .= '<div class="single-review-content-wrap">';
        foreach ($tn_review_data as $data) {
            if (!empty($data['tn_cd'])) {
                $str .= '<div class="single-review-element"><span class="single-review-description">' . esc_attr($data['tn_cd']) . '</span>';
                $str .= '<span class="single-review-score">' . esc_attr($data['tn_cs']) . '</span></div>';
                $str .= '<div class="single-score-bar-wrap">';
                $str .= '<div class="score-bar" style="width:' . esc_attr($data['tn_cs'] * 10) . '%"></div>';
                $str .= '</div>';
            }
        }
        $str .= '<div class="single-review-summary-wrap clearfix">';
        $str .= '<div class="single-review-summary"><h3>' . __('Summary', 'tn') . '</h3>';
        $str .= '<p itemprop="description">' . esc_attr($tn_review_summary) . '</p></div>';
        $str .= '<div itemprop="ratingValue" class="single-review-as">' . esc_attr($tn_as) . '</div>';
        $str .= '</div></div></div>';

        return $str;
    }
}
