class php {
  anchor { "php::begin": } ->
    class { "php::install": } ->
    class { "php::config": } ->

  class { "php::service": } ->
    anchor { "php::end": }

  Anchor["php::begin"] -> Anchor["php::end"]
}
