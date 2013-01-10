class optimal_package_sources::debian {
  file { "/opt/provisioning-deps":
    ensure => directory,
  }

  file { "/opt/provisioning-deps/netselect_0.3.ds1-14+squeeze1_amd64.deb":
    owner   => root,
    group   => root,
    mode    => 644,
    ensure  => present,
    source  => "puppet:///modules/optimal_package_sources/debian/netselect_0.3.ds1-14+squeeze1_amd64.deb",
    require => File[ "/opt/provisioning-deps" ],
    alias   => "netselect-package",
  }

  package { "netselect":
    provider => dpkg,
    ensure   => installed,
    source   => "/opt/provisioning-deps/netselect_0.3.ds1-14+squeeze1_amd64.deb",
    require  => File[ "netselect-package" ],
  }

  file { "/usr/local/bin":
    ensure => directory,
  }

  file { "/usr/local/bin/netselect-mirror.sh":
    owner   => root,
    group   => root,
    mode    => 755,
    ensure  => present,
    source  => "puppet:///modules/optimal_package_sources/debian/netselect-mirror.sh",
    require => File[ "/usr/local/bin" ],
    alias   => "netselect-mirror",
  }

  exec { "netselect-mirror":
    command => "/usr/local/bin/netselect-mirror.sh",
    require => [ Package["netselect"], File["netselect-mirror"] ],
    unless => "/bin/grep -q '# PUPPET: MIRROR AUTOSELECTED DUE TO FASTEST NETSTAT' /etc/apt/sources.list",
  }

  exec { "optimal_package_sources_apt_update":
    schedule  => daily,
    command   =>"/usr/bin/apt-get update",
    logoutput => 'on_failure',
    require   => Exec["netselect-mirror"],
    onlyif    => "/bin/ping -W1 -c1 -q google.com",
  }

}
