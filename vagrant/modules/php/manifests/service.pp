class php::service {
  include php::params

  service { "${php::params::serviceName}":
    ensure  => running,
    enable  => true,
    alias   => "php::service::fpm",
  }
}
