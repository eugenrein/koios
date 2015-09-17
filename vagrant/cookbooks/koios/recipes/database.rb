#
# Cookbook Name:: koios
# Recipe:: database
#
# Copyright (C) 2014 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#
mysql_database 'koios' do
  connection(
    :host     => '0.0.0.0',
    :username => 'root',
    :password => node['mysql']['server_root_password']
  )
  action :create
end

service "mysql" do
    action :restart
end