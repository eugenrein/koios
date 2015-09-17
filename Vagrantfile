# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    # All Vagrant configuration is done here. The most common configuration
    # options are documented and commented below. For a complete reference,
    # please see the online documentation at vagrantup.com.

    # Every Vagrant virtual environment requires a box to build off of.
    config.vm.box = "ubuntu/trusty64"

    # Disable automatic box update checking. If you disable this, then
    # boxes will only be checked for updates when the user runs
    # `vagrant box outdated`. This is not recommended.
    # config.vm.box_check_update = false

    # Create a forwarded port mapping which allows access to a specific port
    # within the machine from a port on the host machine. In the example below,
    # accessing "localhost:8080" will access port 80 on the guest machine.
    # config.vm.network "forwarded_port", guest: 80, host: 8000

    # Create a private network, which allows host-only access to the machine
    # using a specific IP.
    config.vm.network "private_network", ip: "192.168.3.214"

    # Create a public network, which generally matched to bridged network.
    # Bridged networks make the machine appear as another physical device on
    # your network.
    # config.vm.network "public_network"

    # If true, then any SSH connections made will enable agent forwarding.
    # Default value: false
    config.ssh.forward_agent = true

    # Provider-specific configuration so you can fine-tune various
    # backing providers for Vagrant. These expose provider-specific options.
    # Example for VirtualBox:
    #
    config.vm.provider "virtualbox" do |vb|
    #   # Don't boot with headless mode
    #   vb.gui = true
    #
    #   # Use VBoxManage to customize the VM. For example to change memory:
        vb.customize ["modifyvm", :id, "--memory", "2048"]
    end
    #
    # View the documentation for the provider you're using for more
    # information on available options.

    # Enable provisioning with chef solo, specifying a cookbooks path, roles
    # path, and data_bags path (all relative to this Vagrantfile), and adding
    # some recipes and/or roles.
    #
    
    config.vm.provision :shell, :inline => "sudo apt-get update -y"

    config.vm.synced_folder ".", "/var/koios/current/", 
        :type => "nfs", 
        :nfs => { 
            :mount_options => [
                "dmode=777,fmode=777"
            ] 
        }
    config.omnibus.chef_version = :latest
    config.cache.auto_detect = true
    config.cache.synced_folder_opts  = {
        type: :nfs,
        mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }
    config.vm.provision "chef_solo" do |chef|
        chef.file_cache_path = "/var/vagrant/chef/cache"
        chef.cookbooks_path = ["vagrant/cookbooks_vendor", "vagrant/cookbooks"]
        # chef.roles_path = "../my-recipes/roles"
        chef.data_bags_path = "vagrant/data_bags"
        
        chef.add_recipe "apt" 
        chef.add_recipe "build-essential"
        chef.add_recipe "user::data_bag"
        chef.add_recipe "apache2"
        chef.add_recipe "apache2::mod_ssl"
        chef.add_recipe "apache2::mod_php5"
        chef.add_recipe "php"
        chef.add_recipe "timezone-ii"
        chef.add_recipe "mysql::server"
        chef.add_recipe "mysql::client"
        chef.add_recipe "database::mysql"
        chef.add_recipe "koios"
        chef.add_recipe "koios::database"

        #chef.add_role "web"

        # You may also specify custom JSON attributes:

        chef.json = {
            :tz => "Europe/Berlin",

            :apache => { 
                :user => "vagrant", 
                :group => "vagrant",
                :mpm => "prefork",
                :version => '2.4'
            },

            :build_essential => { 
                :compiletime => true 
            },

            :apt => { 
                :periodic_update_min_delay => 120 
            },

            :mysql => { 
                :allow_remote_root => true,
                :bind_address => "0.0.0.0",
                :server_root_password => "root",
                :server_repl_password => "root",
                :server_debian_password => "root",
                :tunable => { 
                    :query_general_log => 1 
                },
                :server => { 
                    :reload_action => "restart" 
                },
                :client => { 
                    :packages => [
                        "mysql-client", 
                        "libmysqlclient-dev",
                        "ruby-mysql"
                    ]
                }
            }
        }

    end

end
