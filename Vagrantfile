# -- mode: ruby --
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = "lanager"
  config.vm.network "forwarded_port", guest: 80, host: 8080

    config.vm.provider "virtualbox" do |v|
      v.customize ["modifyvm", :id, "--uartmode1", "disconnected" ]
      v.customize ["modifyvm", :id, "--memory", 2048]
    end

  config.vm.provision "shell",
    inline: "/bin/bash /vagrant/install.sh"
end