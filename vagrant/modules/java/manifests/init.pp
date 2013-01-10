class java {
  case $operatingsystem {
    /^(Debian|Ubuntu)$/: { 
      anchor { "java::begin": }
        -> class { "java::debian": }
      -> anchor { "java::end": }
    }
  }
}
