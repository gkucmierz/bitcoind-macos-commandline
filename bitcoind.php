#!/usr/bin/php
<?php

include('bitcoin-php/src/bitcoin.inc');

$login_data = getLoginData('~/Library/Application\ Support/Bitcoin/bitcoin.conf');

$bc = new BitcoinClient(
  'http',
  $login_data['user'],
  $login_data['pass']
);

$data = call_user_func_array(array($bc, 'query'), array_splice($argv, 1));
// echo print_r($data, true) . "\n";

if (gettype($data)=='string') {
  echo $data . "\n";
} else {
  echo prettyPrint(json_encode($data)) . "\n";
}



function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $prev_char = '';
    $in_quotes = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if( $char === '"' && $prev_char != '\\' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "  ", $new_line_level );
        }
        $result .= $char.$post;
        $prev_char = $char;
    }

    return $result;
}

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
