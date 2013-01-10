class php_extension {
  include php::install

  anchor { "php_extension::begin": } ->
    class { "php_extension::prepare": }
  
  anchor { "php_extension::end": } ->
    class { "php_extension::install": }

  Class["php::install"] -> Anchor["php_extension::begin"] -> Anchor["php_extension::end"]
}
