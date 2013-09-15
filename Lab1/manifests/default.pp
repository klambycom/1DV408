class { "apache":
  mpm_module    => "prefork",
  default_vhost => false,
}

Exec {
  path => ["/usr/bin", "/usr/local/bin"],
}

exec { "apt-get-update":
  command => "apt-get update",
}

Exec["apt-get-update"] -> Package <| |>

include "apache::mod::php"

apache::vhost { "lab-site":
  port    => 80,
  docroot => "/vagrant",
}
