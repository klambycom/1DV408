Exec {
  path => ["/usr/bin", "/usr/local/bin"],
}

exec { "apt-get-update":
  command => "apt-get update",
}

Exec["apt-get-update"] -> Package <| |>

# Module puppetlabs/apache
class { "apache":
  mpm_module    => "prefork",
  default_vhost => false,
}

include "apache::mod::php"

apache::vhost { "lab-site":
  port    => 80,
  docroot => "/vagrant",
}

# Module puppetlabs/mysql
include "mysql"
include "mysql::php"

class { "mysql::server":
  config_hash => { "root_password" => "foobar" }
}

mysql::db { "database":
  user     => "1dv408",
  password => "mypassword",
  host     => "localhost",
  grant    => ["all"],
}
