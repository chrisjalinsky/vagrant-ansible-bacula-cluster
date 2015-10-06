VAGRANTFILE_API_VERSION = "2"

base_dir = File.expand_path(File.dirname(__FILE__))

# Cluster, VM and network settings
NETWORK_SUBNET = "10.0.10"
NETWORK_DOMAIN_NAME = "dev.lan"
NUM_BACULA_FDS = 1
NUM_BACULA_SDS = 1
NUM_BACULA_DIRS = 1
BACULA_FD_IPS_START = 10
BACULA_SD_IPS_START = 20
BACULA_DIR_IPS_START = 30
BACULA_FD_MEMORY = 1024
BACULA_SD_MEMORY = 1024
BACULA_DIR_MEMORY = 1024
BACULA_FD_CPU = 1
BACULA_SD_CPU = 1
BACULA_DIR_CPU = 1
CLUSTER = {}

# Build the cluster
(1..NUM_BACULA_FDS).each do |i|
  CLUSTER["baculafd#{i}.#{NETWORK_DOMAIN_NAME}"] = {:ip => "#{NETWORK_SUBNET}.#{BACULA_FD_IPS_START + i}",  :cpus => "#{BACULA_FD_CPU}", :mem => "#{BACULA_FD_MEMORY}", :short => "baculafd#{i}"}
end

(1..NUM_BACULA_SDS).each do |i|
  CLUSTER["baculasd#{i}.#{NETWORK_DOMAIN_NAME}"] = {:ip => "#{NETWORK_SUBNET}.#{BACULA_SD_IPS_START + i}",  :cpus => "#{BACULA_SD_CPU}", :mem => "#{BACULA_SD_MEMORY}", :short => "baculasd#{i}"}
end

(1..NUM_BACULA_DIRS).each do |i|
  CLUSTER["baculadir#{i}.#{NETWORK_DOMAIN_NAME}"] = {:ip => "#{NETWORK_SUBNET}.#{BACULA_DIR_IPS_START + i}",  :cpus => "#{BACULA_DIR_CPU}", :mem => "#{BACULA_DIR_MEMORY}", :short => "baculadir#{i}"}
end

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :machine
    config.cache.enable :apt
  end

  CLUSTER.each do |hostname, info|

    config.vm.define hostname do |cfg|

      cfg.vm.provider :virtualbox do |vb, override|
        override.vm.box = "ubuntu/trusty64"
        override.vm.network :private_network, ip: "#{info[:ip]}"
        override.vm.hostname = hostname

        vb.name = 'bacula-' + hostname
        vb.customize ["modifyvm", :id, "--memory", info[:mem], "--cpus", info[:cpus], "--hwvirtex", "on" ]
      end

      # provision nodes with ansible
      cfg.vm.provision :ansible do |ansible|
        ansible.verbose = "v"
        ansible.inventory_path = base_dir + "/hosts"
        ansible.playbook = base_dir + "/site.yml"
        #ansible.limit = "#{info[:ip]}" # Ansible hosts are identified by ip
        ansible.limit = "#{hostname}"
      end

    end

  end

end