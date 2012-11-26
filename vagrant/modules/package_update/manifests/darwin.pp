class package_update::darwin {
  exec { "initial-brew-update":
    command => "/usr/local/bin/brew update",
  }
}
