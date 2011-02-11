<?php
/*
Plugin Name: Average CMS Wildcard Admin Redirect
Plugin URI: https://github.com/cramerdev/acms-wildcard-admin-redirect
Description: Redirects the admin pages for a mapped domain to the non-mapped version of the domain. This is required because when FORCE_SSL_ADMIN is enabled, by default the redirect will go the the https version of the URL you're on. If you're using domain mapping, that domain might not have a valid SSL certificate. This plugin redirects to the original, non-mapped domain instead.
Version: 0.0.1
Author: Nathan L Smith
*/

if (force_ssl_admin() && function_exists('get_original_url')) {
    function acms_wildcard_admin_redirect($location, $status = 302) {
        $url = parse_url($location);
        if ($url['scheme'] === 'https' &&
            (preg_match("/\/wp-[login|admin|register]/", $url['path']) > 0)) {
            $orig = parse_url(get_original_url($url['host']));
            if ($orig['host'] && $orig['host'] !== $url['host']) {
                $location = 'https://'.$orig['host'].$url['path'];
            }
        }
        return $location;
    }
    add_filter('wp_redirect', 'acms_wildcard_admin_redirect');
}
?>
