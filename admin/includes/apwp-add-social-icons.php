<?php

/**
 * Description: Display the manual product sharing page
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Sunday, Jun-23-2019 at 12:27:10
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     2.0.0
 */
/**
 * Include
 */
require_once APWP_INCLUDES_PATH . 'class-apwp-hashtags.php';
/**
 * Add social media share icons to the last Tweeted section.
 *
 * @since 2.0.0
 *
 * @param  string $last_id Product ID.
 * @param  string $image   Image url.
 * @return string
 */
function apwp_add_social_icons( $last_id, $image )
{
    $label = new Apwp_Labels();
    $hash = new Apwp_Hashtags();
    $inventory = get_transient( 'apwp_prod_list_data' );
    $prod = [];
    $url = get_permalink( $last_id );
    $hashtg = $hash->apwp_my_hashtags( $last_id );
    $caption = '';
    foreach ( $inventory as $product ) {
        
        if ( $product['id'] === $last_id ) {
            $prod = $product;
            break;
        }
    
    }
    $tum_hashtgs = $prod['hashtags'];
    $desc = $prod['short_desc'];
    $_twit_tags = ltrim( $hashtg, '#' );
    // Twitter adds the '#' to the first one.
    $sh_url = $prod['bitly_link'];
    $title = $prod['title'];
    if ( '' === $sh_url ) {
        $sh_url = $url;
    }
    // If on sale add on sale to title.
    $check_disc_setting = get_option( 'apwp_discount' );
    $sale = $prod['sale_price'];
    $reg = $prod['regular_price'];
    
    if ( $sale && 'checked' === $check_disc_setting ) {
        $percent_off = apwp_get_percent_off( $reg, $sale );
        $on_sale = $label->schedule_labels['on-sale'] . ' ' . $percent_off . '% ' . $label->schedule_labels['discount'];
        $desc1 = $on_sale . ' - ' . $desc;
        $desc = $desc1;
        $caption = urlencode_deep( $on_sale );
    }
    
    $data = [
        'short_url'   => $sh_url,
        'url'         => $url,
        'last_id'     => $last_id,
        'desc'        => $desc,
        'image'       => $image,
        '_twit_tags'  => $_twit_tags,
        'tum_hashtgs' => $tum_hashtgs,
        'title'       => $title,
        'caption'     => $caption,
        'hashtg'      => $hashtg,
    ];
    $links = apwp_get_social_links( $data );
    $twit_url = $links['twitter'];
    $fb_url = $links['facebook'];
    $tumblr = $links['tumblr'];
    $is_local = apwp_check_local_host();
    $is_ssl = is_ssl();
    
    if ( !$is_local && $is_ssl ) {
        $repost = '</span><table style="width: 75%; margin-left: 18%;"><tr><td>' . $twit_url . '</td><td>&nbsp;</td><td>' . $fb_url . '</td></tr><tr><td>' . $tumblr . '</td></tr></table>';
    } elseif ( $is_local ) {
        $repost = '<div class="apwp-errormessages2"><span class="share-tab-error">' . $label->settings_labels['localhost-error-sharing'] . '</span></div><br/>';
    }
    
    if ( !$is_ssl ) {
        $repost .= '<div class="apwp-errormessages2"><span class="share-tab-error">' . $label->settings_labels['need-ssl'] . '</span></div>';
    }
    $st = '<span style="font-weight: 600; font-size: 0.9em; text-align: left;">';
    return $st . $repost . '</span>';
}

/**
 * Create the links for each social media site with product data
 *
 * @since  2.1.3.2
 *
 * @param  array $data Product data.
 * @return array
 */
function apwp_get_social_links( $data )
{
    $sh_url = urlencode_deep( $data['short_url'] );
    $url = $data['url'];
    $last_id = $data['last_id'];
    $desc = urlencode_deep( $data['desc'] );
    $image = $data['image'];
    $_twit_tags = $data['_twit_tags'];
    $tum_hashtgs = $data['tum_hashtgs'];
    $title = $data['title'];
    $caption = $data['caption'];
    $hashtg = $data['hashtg'];
    $val = apwp_get_option_key();
    $pin_url = '';
    // Facebook SDK javascript.
    ?>
		<div id="fb-root"></div>
		<script>
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id))
					return;
				js = d.createElement(s);
				js.id = id;
				js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=<?php 
    echo  $val ;
    ?>&autoLogAppEvents=1';
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>

		<script id="tumblr-js" async src="https://assets.tumblr.com/share-button.js"></script>
		<script	type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
		<script>
			window.twttr = (function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0],
			t = window.twttr || {};
			if (d.getElementById(id)) return t;
			js = d.createElement(s);
			js.id = id;
			js.src = "https://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);

			t._e = [];
			t.ready = function(f) {
			t._e.push(f);
			};

			return t;
		}(document, "script", "twitter-wjs"));
		</script>

		<?php 
    $fb_url = '<div class="fb-share-button" data-href="' . $url . '" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode_deep( $sh_url ) . '&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>';
    // twitter.
    $twit_url = "<a href='https://twitter.com/share?ref_src=twsrc%5Etfw&text={$desc}&url={$sh_url}&hashtags={$_twit_tags}' class='twitter-share-button' data-show-count='false'>Tweet</a><script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script>";
    // Tumblr.
    $tumblr = "<a class='tumblr-share-button' data-color='blue' data-notes='none' href='https://www.tumblr.com/widgets/share/tool?canonicalUrl={$url}&tags={$tum_hashtgs}&title={$title}&posttype=link&content={$sh_url}&caption={$caption}'></a>";
    ?>
				<script>
					var idShare = <?php 
    echo  $last_id ;
    ?>;
				</script>
				<script id="tumblr-js"></script>
				<?php 
    return [
        'pin'      => $pin_url,
        'facebook' => $fb_url,
        'twitter'  => $twit_url,
        'tumblr'   => $tumblr,
    ];
}
