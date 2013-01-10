class additional_package_sources {
  case $operatingsystem {
    /^(Debian|Ubuntu)$/: { 
      anchor { "additional_package_sources::begin": }
        -> class { "additional_package_sources::debian": }
      -> anchor { "additional_package_sources::end": }
    }
    'Darwin': { 
      anchor { "additional_package_sources::begin": }
        -> class { "additional_package_sources::darwin": }
      -> anchor { "additional_package_sources::end": }
    }
  }
}
