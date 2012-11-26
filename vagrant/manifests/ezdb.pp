stage {
  "init": before => Stage["main"];
}

class {
  "dns_issue_hack":
    stage => init;
} ->
class {
  "optimal_package_sources":
    stage => init;
}

include "additional_package_sources"

include "java"
include "php"
include "php_extension"
include "mysql"
include "postgresql"

package { 
  "ant": 
    ensure  => "latest",
    require => Class["java", "package_update"];
  "git":
    ensure  => latest,
    require => Class["package_update"];
  "vim":
    ensure  => latest,
    require => Class["package_update"];
}
