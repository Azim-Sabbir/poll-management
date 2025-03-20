<?php
if (!function_exists('getUserAgent')) {
    function getUserAgent($request)
    {
        $user_agent = $request->header('User-Agent');

        $bname = 'Unknown';
        $platform = 'Unknown';

        if (preg_match('/linux/i', $user_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = 'windows';
        }

        // Store platform info in an array instead of echoing it
        $browserData = [
            'platform' => $platform,
            'browser' => 'Unknown'
        ];

        if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
            $browserData['browser'] = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            $browserData['browser'] = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            $browserData['browser'] = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $user_agent)) {
            $browserData['browser'] = 'Apple Safari';
        } elseif (preg_match('/Opera/i', $user_agent)) {
            $browserData['browser'] = 'Opera';
        } elseif (preg_match('/Netscape/i', $user_agent)) {
            $browserData['browser'] = 'Netscape';
        }

        return $browserData["browser"];
    }
}
