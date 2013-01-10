class php_extension::install::pgsql {
  include php_extension::params::pgsql

  package { "${php_extension::params::pgsql::package}":
    ensure => latest,
    notify => Class["php::service"],
  }
}
