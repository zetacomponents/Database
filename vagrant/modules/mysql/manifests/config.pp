class mysql::config {
  mysql::user { "ezcdb":
    password => "8sjhdASHDas1",
  } ->
  exec { "mysql::user::root::allinterfaces":
    command => "mysql -uroot -e \"GRANT ALL PRIVILEGES ON *.* TO root@'%' IDENTIFIED BY '';\"",
    require => Service["mysql"],
    path    => "${::path}",
  }
}
