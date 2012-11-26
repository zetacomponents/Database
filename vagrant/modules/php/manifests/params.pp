class php::params {
  $serviceName = $operatingsystem ? {
    /^(Debian|Ubuntu)$/ => "php5-fpm"
  }

  $confd = $operatingsystem ? {
    /^(Debian|Ubuntu)$/ => "/etc/php5/conf.d"
  }

  $fpmConfigSource = "puppet:///modules/php/www-fpm-pool.conf"
  $fpmConfigTarget = "/etc/php5/fpm/pool.d/www.conf"

  $timezoneSource = "puppet:///modules/php/timezone.ini"
}
