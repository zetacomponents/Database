class php_extension::install::sqlite {
  include php_extension::params::sqlite

  package { "${php_extension::params::sqlite::package}":
    ensure => latest,
    notify => Class["php::service"],
  }
}
