class php_extension::install::pecl_apc {
  include php_extension::params
  include php_extension::params::pecl_apc

  $buildDirectory     = "${php_extension::params::buildDirectory}"
  $extensionDirectory = "${buildDirectory}/pecl_apc"

  Exec {
    path => "${::path}"
  }

  exec { "php_extension::pecl_apc::download":
    command => "wget -O pecl_apc.tgz -c ${php_extension::params::pecl_apc::repository}",
    cwd     => "${buildDirectory}",
    creates => "${buildDirectory}/pecl_apc.tgz",
  } ->
  exec { "php_extension::pecl_apc::extract":
    command => "tar -zxf pecl_apc.tgz && mv APC-* pecl_apc",
    cwd     => "${buildDirectory}",
    creates => "${buildDirectory}/pecl_apc",
  } ->
  exec { "php_extension::pecl_apc::phpize":
    command => "${php_extension::params::phpize}",
    cwd     => "${extensionDirectory}",
    creates => "${extensionDirectory}/configure",
  } ->
  exec { "php_extension::pecl_apc::configure":
    command   => "${extensionDirectory}/configure",
    cwd       => "${extensionDirectory}",
    creates   => "${extensionDirectory}/config.status",
    logoutput => "on_failure",
  } ->
  exec { "php_extension::pecl_apc::make":
    command => "make",
    cwd     => "${extensionDirectory}",
    creates => "${extensionDirectory}/modules/apc.so",
  } ->
  exec { "php_extension::pecl_apc::make_install":
    command => "make install",
    cwd     => "${extensionDirectory}",
    unless  => "test -f \"`php-config --extension-dir`/apc.so\"",
  } ->
  file { "${php_extension::params::iniDirectory}/${php_extension::params::pecl_apc::iniTarget}":
    source => "${php_extension::params::pecl_apc::iniSource}",
    ensure => present,
    notify => Class["php::service"],
  }
}
