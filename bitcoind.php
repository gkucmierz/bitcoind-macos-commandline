#!/usr/bin/php
<?php

include('bitcoin-php/src/bitcoin.inc');

$login_data = getLoginData('~/Library/Application\ Support/Bitcoin/bitcoin.conf');

$bc = new BitcoinClient(
  'http',
  $login_data['user'],
  $login_data['pass']
);

echo call_user_func_array(array($bc, 'query'), array_splice($argv, 1)) . "\n";



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
