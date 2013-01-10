class dns_issue_hack {
  case $operatingsystem {
    /^(Debian|Ubuntu)$/: {
      anchor { "dns_issue_hack::begin": }
        -> class { "dns_issue_hack::debian": }
      -> anchor { "dns_issue_hack::end": }
    }
    "Darwin": {
      anchor { "dns_issue_hack::begin": }
        -> class { "dns_issue_hack::darwin": }
      -> anchor { "dns_issue_hack::end": }
    }
  }
}
