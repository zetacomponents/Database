class php_extension::install::mysqlnd {
  include php_extension::params::mysqlnd

  package { "${php_extension::params::mysqlnd::package}":
    ensure => latest,
    notify => Class["php::service"],
  }
}
