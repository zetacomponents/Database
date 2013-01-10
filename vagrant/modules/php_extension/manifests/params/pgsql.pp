class php_extension::params::pgsql {
  $package = $operatingsystem ? { 
    /^(Debian|Ubuntu)$/ => php5-pgsql
  }
}
