<?php

if (!function_exists('old')) {

    function old($field_name) {

        return isset($_REQUEST[$field_name]) ? $_REQUEST[$field_name] : '';
    }

}

if (!function_exists('csrf_token')) {

    function csrf_token() {

        $token = sha1(rand(1, 1000) . date('Y.m.d.H.i.s') . 'The_Poject_Name');
        $_SESSION['token'] = $token;
        return $token;
    }

}

function users_verification() {

    $permit = FALSE;
    if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR']) {

        if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] == $_SERVER['HTTP_USER_AGENT']) {

            if (isset($_SESSION['id'])) {

                $permit = TRUE;
            }
        }
    }
    return $permit;
}

function compare_email($log, $email) {

    $taken = FALSE;

    $sql = "SELECT email FROM users WHERE email = '$email'";
    $match = mysqli_query($log, $sql);
    if ($match && mysqli_num_rows($match) > 0) {
        $taken = TRUE;
    }

    return $taken;
}
