<?php
// By James Hyun

// Get path to root directory of this website. Will probably just be '/' in most cases, but we need a way to make sure
// this stuff will works in the event our website is placed in a subdirectory of htdocs.
$realpath = realpath(dirname(__FILE__));
$rootPath = substr($realpath, strpos($realpath, 'htdocs')); // cut out everything prior to htdocs since it doesn't matter for the client
$rootPath = strpbrk($rootPath, '\\/'); // cut out htdocs because that also doesn't matter for the client; cross-platform
$rootPath = '/' . substr($rootPath, 1) . '/'; // replace \ with / in case of windows, append / at the end
?>