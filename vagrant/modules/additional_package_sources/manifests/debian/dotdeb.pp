class additional_package_sources::debian::dotdeb {
  include "apt"

  file { "/etc/apt/sources.list.d/dotdeb.list":
    ensure  => "present",
    owner   => "root",
    group   => "root",
    mode    => "0600",
    source  => "puppet:///modules/additional_package_sources/debian/dotdeb/dotdeb.list",
  }

  exec { "dotdeb-apt-key":
    path    => "/bin:/usr/bin",
    cwd     => "/tmp",
    command => "wget http://www.dotdeb.org/dotdeb.gpg -O dotdeb.gpg &&
                cat dotdeb.gpg | apt-key add -",
    unless  => "apt-key list | grep dotdeb",
    require => File["/etc/apt/sources.list.d/dotdeb.list"],
    notify  => Class["apt::update"];
  }
}
