class php_extension::install::pecl_stem {
  include php_extension::params
  include php_extension::params::pecl_stem

  $buildDirectory     = "${php_extension::params::buildDirectory}"
  $extensionDirectory = "${buildDirectory}/pecl_stem"

  Exec {
    path => "${::path}"
  }

#  file { ["${php_extension::params::buildDirectory}/pecl_stem", "${buildDirectory}" ]:
#    ensure => directory,
#  } ->
  exec { "php_extension::pecl_stem::download":
    command => "wget -O pecl_stem.tgz -c ${php_extension::params::pecl_stem::repository}",
    cwd     => "${buildDirectory}",
    creates => "${buildDirectory}/pecl_stem.tgz",
  } ->
  exec { "php_extension::pecl_stem::extract":
    command => "tar -zxf pecl_stem.tgz && mv stem-* pecl_stem",
    cwd     => "${buildDirectory}",
    creates => "${buildDirectory}/pecl_stem/stem.c",
  } ->
  exec { "php_extension::pecl_stem::phpize":
    command => "${php_extension::params::phpize}",
    cwd     => "${extensionDirectory}",
    creates => "${extensionDirectory}/configure",
  } ->
  exec { "php_extension::pecl_stem::configure":
    command   => "${extensionDirectory}/configure",
    cwd       => "${extensionDirectory}",
    creates   => "${extensionDirectory}/config.status",
    logoutput => "on_failure",
  } ->
  exec { "php_extension::pecl_stem::make":
    command => "make",
    cwd     => "${extensionDirectory}",
    creates => "${extensionDirectory}/modules/stem.so",
  } ->
  exec { "php_extension::pecl_stem::make_install":
    command => "make install",
    cwd     => "${extensionDirectory}",
    unless  => "test -f \"`php-config --extension-dir`/stem.so\"",
  } ->
  file { "${php_extension::params::iniDirectory}/${php_extension::params::pecl_stem::iniTarget}":
    source => "${php_extension::params::pecl_stem::iniSource}",
    ensure => present,
    notify => Class["php::service"],
  }
}
