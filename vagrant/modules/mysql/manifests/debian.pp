class mysql::debian {
  include "package_update"
  
  package { "mysql-server":
    ensure  => "latest",
    require => Class["package_update"],
  } ->
  file { "/etc/mysql/my.cnf":
    owner   => root,
    group   => root,
    mode    => 644,
    ensure  => present,
    source  => "puppet:///modules/mysql/my.cnf",
    notify  => Service['mysql'],
  } ->
  service { "mysql":
    ensure  => "running",
    enable  => true,
  }
}
