class dns_issue_hack::debian {
  file { "/etc/resolvconf/resolv.conf.d/head":
    owner   => root,
    group   => root,
    mode    => 644,
    ensure  => present,
    source  => "puppet:///modules/dns_issue_hack/debian/resolv.conf.head",
    notify  => Exec["dns_issue_hack::resolvconf-update"],
    alias   => "dns_issue_hack::resolve-conf-head",
  }

  exec { "dns_issue_hack::resolvconf-update":
    refreshonly => true,
    command     =>"resolvconf -u",
    path        => "$::path",
    logoutput   => "on_failure",
  }
}
