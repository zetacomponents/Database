class package_update {
  case $operatingsystem {
    /^(Debian|Ubuntu)$/: { 
      anchor { "package_update::begin": }
        -> class { "package_update::debian": }
      -> anchor { "package_update::end": }
    }
    'Darwin': { 
      anchor { "package_update::begin": }
        -> class { "package_update::darwin": }
      -> anchor { "package_update::end": }
    }
  }
}
