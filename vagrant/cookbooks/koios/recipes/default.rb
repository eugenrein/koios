#
# Cookbook Name:: koios
# Recipe:: default
#
# Copyright (C) 2014 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#

directory "/var/koios/" do
  owner "root"
  group "root"
  mode 0755
  action :create
end

template "/etc/apache2/sites-enabled/koios.conf" do
  source "apache2.sites-enabled.conf.erb"
  mode 0644
  owner "root"
  group "root"

  notifies :reload, resources(:service => "apache2")
end

pkg_list = [
    "php5-mysql",
    "php5-curl",
    "php5-imagick",
    "php5-gd",
    "php5-cgi",
    "php5-intl",
    "mcrypt",
    "libapache2-mod-php5",
    "apache2-mpm-prefork"
]
pkg_list.each do | pkg |
  package pkg do
      action [:install]
  end
end

pkg = value_for_platform_family(
    [ 'rhel', 'fedora' ] => 'php-mcrypt',
    'debian' => 'php5-mcrypt'
)

package pkg do
  action :install
  notifies(:run, "execute[/usr/sbin/php5enmod mcrypt]", :immediately) if platform?('ubuntu') && node['platform_version'].to_f >= 12.04
end

execute '/usr/sbin/php5enmod mcrypt' do
  action :nothing
  only_if { platform?('ubuntu') && node['platform_version'].to_f >= 12.04 && ::File.exists?('/usr/sbin/php5enmod') }
end