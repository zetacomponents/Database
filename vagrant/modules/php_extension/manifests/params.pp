class php_extension::params {
  $buildDirectory = "/opt/php_extensions_build"
  
  $git = "/usr/bin/git"
  $phpize = $operatingsystem ? {
    /^(Debian|Ubuntu)$/ => "phpize5" 
  }

  $iniDirectory = $operatingsystem ? {
    /^(Debian|Ubuntu)$/ => "/etc/php5/conf.d" 
  }
}
