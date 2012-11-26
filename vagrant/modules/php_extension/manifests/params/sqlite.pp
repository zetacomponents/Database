class php_extension::params::sqlite {
  $package = $operatingsystem ? { 
    /^(Debian|Ubuntu)$/ => php5-sqlite
  }
}
