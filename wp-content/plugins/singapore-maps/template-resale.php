<?php
/*
 * Template for "Singapore Maps" - Resale Post Type Wordpress plugin 
 */

$style = $class = '';
$settings = get_option('sm_settings');

// Get alignment class for maps
if (!empty($settings['alignment']) && $settings['alignment'] != 'left') {
    $class = sprintf(" class='maps_%s'", $settings['alignment']);
}

// Scale width and height of maps
if (!empty($settings['mapsize']) && $settings['mapsize'] != '100') {
    $mapsize = intval($settings['mapsize']);
    if ($mapsize > 0) {
        $height = round($mapsize / 100 * SM_DEFAULT_HEIGHT);
        $width = round($mapsize / 100 * SM_DEFAULT_WIDTH);
        $style = sprintf("height: %dpx; width: %dpx;", $height, ($width + 225));
    }
}

// Get custom CSS
if (!empty($settings['css'])) {
    $style = sprintf("%s %s", $style, $settings['css']);
}

// Add style prefix
if (!empty($style)) {
    $style = sprintf(" style='%s'", $style);
}
$listings = sm_get_resale_listings();
$north = empty($listings['North']) ? '0' : count($listings['North']);
$northeast = empty($listings['North East']) ? '0' : count($listings['North East']);
$east = empty($listings['East']) ? '0' : count($listings['East']);
$west = empty($listings['West']) ? '0' : count($listings['West']);
$central = empty($listings['Central']) ? '0' : count($listings['Central']);
//print_r($listings);
// Create tooltips
?>
<!--[if IE]>
<style>
.list .category .child-content li {
border-top: 1px solid #3D3D3D;


}
#sidebar-map-singapo ul.list .category{
border-bottom: 1px solid #3d3d3d;}
</style>
<![endif]-->
<style type="text/css">
    .google_map{
        width: <?php echo $width ?>px;
        float: left;
    }
</style>
<div id='singapore_maps' class="singapore_maps_resale">
    <div class="fgcmapimage">
        <div id='regional_map'>
            <img src='<?php echo plugins_url("images/singapore.gif", __FILE__); ?>' alt='' usemap='#singapore' />
            <map name='singapore'>
                <area shape='poly' alt='north_map' href='#resale_north_map' coords='175,54,179,64,187,56,191,58,194,78,198,93,200,115,218,116,225,108,231,121,249,118,249,127,253,131,245,147,245,156,277,161,288,152,296,153,298,157,303,157,305,151,322,141,335,129,325,114,326,105,334,102,340,101,358,76,352,72,363,61,362,55,357,55,357,50,351,50,351,38,343,36,343,32,347,32,346,27,328,20,327,15,321,15,319,19,316,18,316,12,311,9,307,15,302,12,296,13,297,6,276,6,263,10,256,17,248,25,231,37,224,37,222,46,214,46,214,53,184,53,181,49' />
                <area shape='poly' alt='northeast_map' href='#resale_northeast_map' coords='358,76,340,101,335,101,326,105,325,114,335,129,321,142,319,151,320,163,340,168,349,174,354,184,358,192,358,199,392,201,392,209,401,208,404,215,408,215,416,207,419,196,429,187,441,188,450,196,449,167,456,167,461,164,455,161,450,153,449,145,443,144,442,127,447,118,447,104,439,94,438,79,432,79,424,88,424,98,416,103,407,107,403,103,407,98,415,97,414,93,404,92,397,87,392,87,392,93,389,93,388,86,376,75' />			
                <area shape='poly' alt='east_map' href='#resale_east_map' coords='443,133,443,144,449,144,450,154,454,160,461,164,456,167,449,166,450,195,442,210,432,220,432,225,444,220,450,224,451,232,464,229,465,233,470,232,470,240,476,254,493,250,514,245,527,238,532,230,536,230,534,240,543,240,553,230,564,200,578,163,577,147,572,135,566,128,553,129,546,124,533,127,533,133,527,133,522,141,509,142,498,141,478,132,463,126,449,127' />			
                <area shape='poly' alt='west_map'  href='#resale_west_map' coords='175,54,167,42,159,36,139,33,120,47,109,60,81,69,76,82,75,95,63,100,61,129,51,141,40,155,31,173,27,173,23,191,16,201,15,212,11,224,8,244,6,275,11,280,35,280,33,276,15,266,19,253,12,242,13,233,19,233,30,242,39,236,45,236,44,242,37,248,40,253,49,245,52,248,43,259,52,270,58,268,67,279,78,271,69,260,72,252,77,253,80,264,87,267,88,249,90,249,91,264,104,262,104,252,112,251,112,257,123,258,132,248,143,250,148,257,164,225,166,226,157,246,166,264,200,268,198,255,201,255,220,278,225,261,226,241,243,233,196,214,189,215,187,199,204,207,210,203,209,191,200,177,197,161,203,147,207,147,217,164,232,168,245,179,245,147,253,131,249,126,249,118,231,121,225,108,217,116,200,115,198,91,194,78,191,58,186,57,179,64,179,63' />
                <area shape='poly' alt='central_map' href='#resale_central_map' coords='220,277,229,284,225,296,205,288,197,288,195,294,209,304,224,311,240,290,245,294,245,299,256,309,266,308,277,320,285,317,298,317,310,321,319,314,332,316,337,320,349,319,346,310,351,306,346,299,352,292,352,286,364,285,369,280,368,266,375,261,382,261,386,268,376,268,372,273,374,281,385,294,406,280,425,270,432,270,451,261,470,259,476,254,469,239,470,232,464,233,463,229,451,231,449,223,444,220,432,225,432,219,443,209,449,196,440,187,429,187,419,196,416,207,407,215,403,215,401,208,392,209,392,201,358,199,357,192,349,173,339,168,319,163,319,149,321,142,305,151,303,156,297,157,296,153,288,152,276,161,245,156,245,179,231,167,216,164,207,147,203,147,197,160,200,179,210,191,209,203,204,207,187,199,189,215,197,214,243,233,226,241,225,261' />			
            </map> 
            <div class="west_tooltip fgctooltip">
                <a class="map-dot" href="resale_west_map"></a>
                <span class="map-caret"></span>
                <a class="map-label" href="resale_west_map"><b>West Region</b><br>     
                    <?php echo $west ?> Properties
                    <span class="arrow"></span></a>

            </div>
            <div class="north_tooltip fgctooltip">
                <a class="map-dot" href="resale_north_map"></a>
                <span class="map-caret"></span>
                <a class="map-label" href="resale_north_map"> <b>North Region</b><br>
                    <?php echo $north ?> Properties
                    <span class="arrow"></span></a>

            </div>
            <div class="central_tooltip fgctooltip">
                <a class="map-dot" href="resale_central_map"></a>
                <span class="map-caret"></span>
                <a class="map-label" href="resale_central_map"><b>Central Region</b><br>
                    <?php echo $central ?>  Properties
                    <span class="arrow"></span></a>

            </div>
            <div class="northeast_tooltip fgctooltip">
                <a class="map-dot" href="resale_northeast_map"></a>
                <span class="map-caret"></span>
                <a class="map-label" href="resale_northeast_map"> <b>North-East Region</b><br>
                    <?php echo $northeast ?> Properties
                    <span class="arrow"></span></a>

            </div>
            <div class="east_tooltip fgctooltip">
                <a class="map-dot" href="resale_east_map"></a>
                <span class="map-caret"></span>
                <a class="map-label" href="resale_east_map">
                    <b>East Region</b><br>
                    <?php echo $east ?> Properties
                    <span class="arrow"></span></a>

            </div>
        </div>
    </div>

    <div id='maps_container'<?php echo $style; ?><?php echo $class; ?>>
        <div id='resale_north_map' class='google_map'>North</div>
        <div id='resale_northeast_map' class='google_map'>North-East</div>
        <div id='resale_east_map' class='google_map'>East</div>
        <div id='resale_west_map' class='google_map'>West</div>
        <div id='resale_central_map' class='google_map'>Central</div>
        <?php
        if (empty($settings['hiddenmap']) && sm_check_mobile() == false) {
            ?>
            <div id="sidebar-map-singapo"  style="display: none">
                <ul id="list" class="list">
                    <li class="category" >
                        <div class="category_item">
                            <div id="filter_hackerspace"  class="category_toggle" typeregion="north_map"></div>
                            <!--<a class="category_info" href="north_map"  onclick="toggleList('north_map');">-->
                            <a class="category_info" href="north_map">
                                <img src="<?php echo $settings['north_icon']; ?>" alt="north">North<span>(<?php echo $north; ?>)</span>
                            </a>
                        </div>
                        <ul class="child-content north_map">

                        </ul>
                    </li>
                    <li class="category">
                        <div class="category_item">
                            <div id="filter_hackerspace"  class="category_toggle" typeregion="northeast_map"></div>
                            <!--<a class="category_info" href="northeast_map"  onclick="toggleList('northeast_map');" >-->
                            <a class="category_info" href="northeast_map">
                                <img src="<?php echo $settings['north_east_icon']; ?>" alt="north-east">North-East<span>(<?php echo $northeast; ?>)</span>
                            </a>
                        </div>
                        <ul  class="child-content northeast_map">

                        </ul>
                    </li>
                    <li class="category">
                        <div class="category_item">
                            <div id="filter_hackerspace"  class="category_toggle" typeregion="east_map"></div>
                            <!--<a class="category_info" href="east_map" onclick="toggleList('east_map');" >-->
                            <a class="category_info" href="east_map">
                                <img src="<?php echo $settings['east_icon']; ?>" alt="east">East<span>(<?php echo $east; ?>)</span></a>
                        </div>
                        <ul  class="child-content east_map">

                        </ul>
                    </li>
                    <li class="category">
                        <div class="category_item">
                            <div id="filter_hackerspace"  class="category_toggle" typeregion="west_map"></div>
                            <!--<a class="category_info" href="west_map" onclick="toggleList('west_map');">-->
                            <a class="category_info" href="west_map">
                                <img src="<?php echo $settings['west_icon']; ?>" alt="west">West <span>(<?php echo $west; ?>)</span></a>
                        </div>
                        <ul  class="child-content west_map">

                        </ul>
                    </li>
                    <li class="category">

                        <div class="category_item"> 
                            <div id="filter_hackerspace"  class="category_toggle" typeregion="central_map"></div>
                            <!--<a class="category_info" href="central_map" onclick="toggleList('central_map');">-->
                            <a class="category_info" href="central_map">
                                <img src="<?php echo $settings['central_icon']; ?>" alt="central">Central<span>(<?php echo $central; ?>)</span>
                            </a>
                        </div>
                        <ul  class="child-content central_map">

                        </ul>
                    </li>
                </ul>
            </div>
        <?php } ?>    
        <div class="button-back-map"> <button class="fgcback">Back to previous map</button></div>

    </div>

    <!-- end singapore_maps -->
</div>