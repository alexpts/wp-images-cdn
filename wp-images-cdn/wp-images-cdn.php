<?php
/**
 * Plugin Name: CDN Images
 * Description: Replace image path to CDN
 * Author: Alexpts
 * Version: 1.0.0
 */

class PtsWpImageReplacerCDN
{

    public function upadeteCdnHost($cdnHost, $autoload = 'yes')
    {
        update_option('pts-cdn-host', $cdnHost, $autoload);
    }

    public function activatePlugin()
    {
        $cdnHost = $this->getCdnHostOption();
        $this->upadeteCdnHost($cdnHost);
    }

    public function deactivatePlugin()
    {
        delete_option('pts-cdn-host');
    }

    /**
     * @return string
     */
    public function getCdnHostOption()
    {
        $cdnHost = get_option('pts-cdn-host', null);
        return $cdnHost ?: get_option('home', null);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function replaceFilter($content)
    {
       $host = $this->getCdnHostOption();

        if ($host) {
            $relPathRegExp = '~(<img.*src=")(\/.+?)(".+?)>~Um';
            $content = preg_replace($relPathRegExp, "$1$host$2$3>", $content);
        }

        return $content;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function removeHostFilter($content)
    {
        $regExp = '~ src="http(?:s)?:\/\/.*\/~Um';
        return preg_replace($regExp, ' src="/', $content);
    }
}

$plugin = new PtsWpImageReplacerCDN;

register_deactivation_hook(__FILE__, [$plugin, 'deactivatePlugin']);
register_activation_hook(__FILE__, [$plugin, 'activatePlugin']);
add_filter('the_content', [$plugin, 'removeHostFilter']);
add_filter('the_content', [$plugin, 'replaceFilter']);
add_filter('post_thumbnail_html', [$plugin, 'removeHostFilter']);
add_filter('post_thumbnail_html', [$plugin, 'replaceFilter']);

add_action('admin_menu', function() {
    add_options_page('CDN Images', 'PTS CDN Image', 10, 'wp-images-cdn/settings-page.php');
});
