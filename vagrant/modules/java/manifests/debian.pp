class java::debian {
  include "apt"
  
  package { "openjdk-7-jdk":
    ensure  => "latest",
  }
}
