class php_extension::prepare {
  include php_extension::params
  
  Package { 
    require => Class["package_update"]
  }

  case $operatingsystem {
    /^(Debian|Ubuntu)$/: { 
      package { "build-essential":
        ensure => "latest",
      }
    }
  }
}
