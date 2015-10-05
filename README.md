Bacula Deployment with Ansible
==============================

This repository a collection of Ansible roles to install and manage Bacula and related services on Ubuntu 14.04, including:

* [Bacula Director](http://www.bacula.org/5.2.x-manuals/en/main/main/Configuring_Director.html) - Application for managing the Bacula jobs and clients
* [Bacula Storage Daemon](http://www.bacula.org/5.2.x-manuals/en/main/main/Storage_Daemon_Configuratio.html) - Application for Bacula storage
* [Bacula File Daemon](http://www.bacula.org/5.2.x-manuals/en/main/main/Client_File_daemon_Configur.html) - Application for Bacula clients
* [Bacula Web](http://www.bacula-web.org/) - Web App alternative to Bconsole
* [Bconsole](http://www.bacula.org/5.2.x-manuals/en/console/console.pdf) - Console for managing Bacula
* [Percona XtraBackup](https://www.percona.com/doc/percona-xtrabackup/2.0/index.html#installation) - for creating database backups with minimal mysql downtime, and in some cases no downtime.
* [mysql](https://github.com/geerlingguy/ansible-role-mysql) - An ansible role created by GeerlingGuy, which has functioned awesomely on Ubuntu 14.04. Much props to him.

The Vagrantfile will create the hosts are run the necessary roles to setup Bacula and additional tools.

Provided you have Virtualbox and Vagrant installed, simply execute the following command from the root folder containing the Vagrantfile:


```
vagrant up
```

This creates several hosts, by default:

```
baculadir1.dev.lan - A Bacula director, Bacula-web app, Bconsole, Percona Xtrabackup
baculafd1.dev.lan - A Bacula backup client
baculasd1.dev.lan - A bacula storage client
```
#Network configuration

For the Bacula cluster deployment to work on your local machine, you need to have domain name resolution handled.  You can accomplish this several ways. A simply dirty way is to write the hosts into your local /etc/hosts file. The first play in the Ansible site.yml file accomplishes this on the VMs, but not on your local host machine. You'll have to add the entries to resolve the names to IPs. For example, when navigating to the bacula-web app at baculadir1.dev.lan, without the /etc/hosts entries, you'll never get it resolved.

The cleaner approach is using a DNS server.

###Bacula Director

To use the Director, log into the baculadir1.dev.lan VM:
```
vagrant ssh baculadir1.dev.lan
```

Become root:

```
sudo su
```

Run bconsole:

```
bconsole
```
###Bacula Web
Alternatively, you can log into the webapp by opening a browser and visiting:

```
http://baculadir1.dev.lan
```

Nginx is the server used, and you'll be prompted for user/pass: This is htpasswd configured with the following testing credentials:

```
user: admin
pass: adminpw
```
###Percona XtraBackup

Percona xtrabackup is installed on the baculadir1.dev.lan VM. You can quickly test it out by creating a hot backup of the Bacula mysql database. See below for a detailed guide on Digital Ocean. It's part 4 of 4.

###Editing the Vagrantfile and Ansible playbook and roles:
This Vagrantfile, Ansible playbook and roles are very open to editing the Bacula cluster. You can add more hosts, host resources, etc. by editing the Vagrantfile and Ansible inventory files. The biggest obstacle is going to be getting the networking right, which if you use DNS is should be as simple as adding a record.

The site.yml file is the base playbook that gets run by a vagrant provision. You can manually trigger it on all hosts with:

```
vagrant provision
```

or individually,

```
vagrant provision baculadir1.dev.lan
```

You'll find the main role configurations in the vars/ folder. I like to keep all my Ansible configurations in one place. This accomplishes that nicely. It is found in vars/makevault.yml .

In a production scenario, you'll want to encrypt this file as it contains sensitive credentials regarding the environment. There are additional role override variables in the playbook ``` site.yml ```


You can turn any variable file into an encrypted vault by issuing the following command:
```
ansible-vault create <filename>
```

More Info:
==========

I followed this 4 page blog writeup on Digital Ocean when writing the Ansible roles. If you want to read more detailed info, check it out below. Thanks to Digital Ocean for their informative articles and tutorials. I tend to read many of their guides since many are geared toward Ubuntu 14.04.

[Digital Ocean Bacula 4 page tutorials](https://www.digitalocean.com/community/tutorials/how-to-install-bacula-server-on-ubuntu-14-04)



