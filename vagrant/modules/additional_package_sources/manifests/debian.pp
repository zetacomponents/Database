class additional_package_sources::debian {
  anchor{ "additional_package_sources::debian::begin": }
  -> class{ "additional_package_sources::debian::dotdeb": }
 # End does not need anchoring, as the whole class is anchored inside the
 # module
}
