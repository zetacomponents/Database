class package_update::debian {
  Class["apt::update"]
  -> anchor { "package_update::debian::end": }
}
