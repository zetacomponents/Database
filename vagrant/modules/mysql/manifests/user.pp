define mysql::user( $password = "" ) {
  exec { "mysql::user::${name}":
    unless  => "mysql -u${name} -p${password}",
    command => "mysql -uroot -e \"CREATE USER '${name}'@'localhost' IDENTIFIED BY '${password}'; GRANT ALL PRIVILEGES ON *.* TO '${name}'@'localhost' WITH GRANT OPTION; CREATE USER '${name}'@'%' IDENTIFIED BY '${password}'; GRANT ALL PRIVILEGES ON *.* TO '${name}'@'%' WITH GRANT OPTION;\"",
    require => Service["mysql"],
    path    => "${::path}",
  }
}
