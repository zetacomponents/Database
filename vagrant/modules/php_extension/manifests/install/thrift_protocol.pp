class php_extension::install::thrift_protocol {
  include php_extension::params
  include php_extension::params::thrift_protocol

  $buildDirectory     = "${php_extension::params::buildDirectory}/thrift_protocol"
  $extensionDirectory = "${buildDirectory}/repository/${php_extension::params::thrift_protocol::extensionDirectory}"

  Exec {
    path => "${::path}"
  }

  file { ["${php_extension::params::buildDirectory}", "${buildDirectory}" ]:
    ensure => directory,
  } ->
  exec { "php_extension::thrift_protocol::git_clone":
    command => "${php_extension::params::git} clone ${php_extension::params::thrift_protocol::repository} repository",
    cwd     => "${buildDirectory}",
    creates => "${buildDirectory}/repository",
  } ->
  exec { "php_extension::thrift_protocol::phpize":
    command => "${php_extension::params::phpize}",
    cwd     => "${extensionDirectory}",
    creates => "${extensionDirectory}/configure",
  } ->
  exec { "php_extension::thrift_protocol::configure":
    command   => "${extensionDirectory}/configure",
    cwd       => "${extensionDirectory}",
    creates   => "${extensionDirectory}/config.status",
    logoutput => "on_failure",
  } ->
  exec { "php_extension::thrift_protocol::make":
    command => "make",
    cwd     => "${extensionDirectory}",
    creates => "${extensionDirectory}/modules/thrift_protocol.so",
  } ->
  exec { "php_extension::thrift_protocol::make_install":
    command => "make install",
    cwd     => "${extensionDirectory}",
    unless  => "test -f \"`php-config --extension-dir`/thrift_protocol.so\"",
  } ->
  file { "${php_extension::params::iniDirectory}/${php_extension::params::thrift_protocol::iniTarget}":
    source => "${php_extension::params::thrift_protocol::iniSource}",
    ensure => present,
    notify => Class["php::service"],
  }
}
