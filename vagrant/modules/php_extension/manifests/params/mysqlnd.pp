class php_extension::params::mysqlnd {
  $package = $operatingsystem ? { 
    /^(Debian|Ubuntu)$/ => php5-mysqlnd
  }
}
