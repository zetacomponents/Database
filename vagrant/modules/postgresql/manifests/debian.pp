class postgresql::debian {
  include "package_update"
  
  package { "postgresql":
    ensure  => "latest",
    require => Class["package_update"],
  } ->
  service { "postgresql":
    ensure  => "running",
    enable  => true,
  }
}
