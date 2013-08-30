#!/usr/bin/php
<?php


$login = getLoginData('~/Library/Application\ Support/Bitcoin/bitcoin.conf');

print_r($login);





function getLoginData ($conf_url) {
  $conf_str = shell_exec('cat ' . $conf_url);

  if (preg_match('/rpcuser\s*=\s*(\w*)/', $conf_str, $mu)) {
    $user = $mu[1];
    if (preg_match('/rpcpassword\s*=\s*(\w*)/', $conf_str, $mp)) {
      $pass = $mp[1];
      return array(
        'user' => $user,
        'pass' => $pass
      );
    }
  }
  return false;
}
