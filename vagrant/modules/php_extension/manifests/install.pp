class php_extension::install {
  anchor{ "php_extension::install::initialize": } ->
  anchor{ "php_extension::install::begin": }


  Anchor[ "php_extension::install::initialize" ] ->
    file { "${php_extension::params::buildDirectory}":
      ensure => directory,
    }
  
#  Anchor[ "php_extension::install::begin" ] ->
#    class { "php_extension::install::thrift_protocol": }

  Anchor[ "php_extension::install::begin" ] ->
    class { "php_extension::install::mysqlnd": }

  Anchor[ "php_extension::install::begin" ] ->
    class { "php_extension::install::pgsql": }

  Anchor[ "php_extension::install::begin" ] ->
    class { "php_extension::install::sqlite": }

#  Anchor[ "php_extension::install::begin" ] ->
#    class { "php_extension::install::pecl_stem": }

#  Anchor[ "php_extension::install::begin" ] ->
#    class { "php_extension::install::pecl_apc": }
}
