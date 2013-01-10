class php::config {
  include php::params

  file { "${php::params::fpmConfigTarget}":
    alias   => "php::config::fpm_pool", 
    owner   => root,
    group   => root,
    mode    => 644,
    ensure  => present,
    source  => "${php::params::fpmConfigSource}",
    notify  => Class["php::service"],
  }

  file { "${php::params::confd}/30-timezone.ini":
    alias   => "php::config::timezone",
    owner   => root,
    group   => root,
    mode    => 644,
    ensure  => present,
    source  => "${php::params::timezoneSource}",
    notify  => Class["php::service"],
  }
}
