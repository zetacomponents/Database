class postgresql {
  anchor { "postgresql::begin": } ->
  anchor { "postgresql::installed": } ->
  anchor { "postgresql::end": }

  case $operatingsystem {
    /^(Debian|Ubuntu)$/: { 
      Anchor["postgresql::begin"] ->
        class { "postgresql::debian": } ->
      Anchor["postgresql::installed"]
    }
  }

  Anchor["postgresql::installed"] ->
    class { "postgresql::config": } ->
  Anchor["postgresql::end"]
}
