class php::install::debian {
  include "package_update"

  Package {
    require => Class["package_update"],
  }

  package { "php5-common": ensure  => "latest" }
  
  package { "php5-cli": 
    ensure  => "latest",
    alias   => "php::cli",
  }
  package { "php5-fpm": 
    ensure  => "latest",
    alias   => "php::fpm",
  }
  package { "php5-xdebug": 
    ensure  => "latest",
    alias   => "php::xdebug",
  }

  package { "php5-dev": ensure  => "latest" }
}
