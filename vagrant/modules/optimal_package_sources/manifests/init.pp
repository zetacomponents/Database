class optimal_package_sources {
  case $operatingsystem {
    /^(Debian|Ubuntu)$/: {
      anchor { "optimal_package_sources::begin": }
        -> class { "optimal_package_sources::debian": }
      -> anchor { "optimal_package_sources::end": }
    }
    "Darwin": {
      anchor { "optimal_package_sources::begin": }
        -> class { "optimal_package_sources::darwin": }
      -> anchor { "optimal_package_sources::end": }
    }
  }
}
