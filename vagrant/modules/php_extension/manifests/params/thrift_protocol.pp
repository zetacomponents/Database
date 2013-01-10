class php_extension::params::thrift_protocol {
  $repository         = "https://github.com/thobbs/phpcassa.git"
  $extensionDirectory = "lib/thrift/ext/thrift_protocol"

  $iniSource = "puppet:///modules/php_extension/thrift_protocol/php.ini"
  $iniTarget = "10-thrift_protocol.ini"
}
