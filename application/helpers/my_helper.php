<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        $ip_address = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check IP from shared internet
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && $ip_address == '') {
            // Check IP from proxy
            $ip_address = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            // Get IP address from remote address
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        // Return the IP address
        return $ip_address;
    }
}

if (!function_exists('visitor_tracking_id')) {
    function visitor_tracking_id($application)
    {
        if (!$application || empty($application->id)) {
            return '';
        }

        $date_source = !empty($application->created_at) ? $application->created_at : $application->visit_date;
        $date_part = date('ymd', strtotime($date_source));
        $date_part = substr($date_part, 0, 2) . (int) substr($date_part, 2, 2) . substr($date_part, 4, 2);
        $seed = $application->id . '|' . $application->phone . '|' . $date_source;
        $code = strtoupper(substr(hash('sha256', $seed), 0, 6));

        return 'TRK-' . $date_part . '-' . $code;
    }
}
