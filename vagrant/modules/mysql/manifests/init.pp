class mysql {
  anchor { "mysql::begin": } ->
  anchor { "mysql::installed": } ->
  anchor { "mysql::end": }

  case $operatingsystem {
    /^(Debian|Ubuntu)$/: { 
      Anchor["mysql::begin"] ->
        class { "mysql::debian": } ->
      Anchor["mysql::installed"]
    }
  }

  Anchor["mysql::installed"] ->
    class { "mysql::config": } ->
  Anchor["mysql::end"]
}
