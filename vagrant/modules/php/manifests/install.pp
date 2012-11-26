class php::install {
  case $operatingsystem {
    /^(Debian|Ubuntu)$/: { 
      anchor { "php::install::begin": }
        -> class { "php::install::debian": }
      -> anchor { "php::install::end": }
    }
  }
}
