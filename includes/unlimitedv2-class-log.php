<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
function _log($msg, $stdout = true)
{
    if ($stdout) {
        $prefix = "WP - " . date("c") . "\n\n";

        $log = print_r($msg, true);

        $out = fopen('php://stdout', 'w');
        fputs($out, $prefix);

        fputs($out, $log);

        fputs($out, "\n\n");

        fclose($out);
    } else {
        print_r($msg);
    }
}