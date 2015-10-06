Bacula Deployment with Ansible
==============================

Read more about the open source tool [Bacula]().

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

Alternately, one at a time:

```
vagrant up baculadir1.dev.lan
```

This creates several hosts, by default:

```
baculadir1.dev.lan - A Bacula director, Bacula-web app, Bconsole, Percona Xtrabackup
baculafd1.dev.lan - A Bacula backup client
baculasd1.dev.lan - A bacula storage client
```

Essentially, vagrant runs the following ansible command (from the root playbook directory where the ./site.yml playbook and ./hosts inventory file reside. The vars files in the playbook are relatively linked):

```
ansible-playbook ./site.yml -i ./hosts -u vagrant -k
```
If you created an encrypted ansible-vault from the vars/ files you would ask for the password when running the playbook:

```
ansible-playbook ./site.yml -i ./hosts -u vagrant -k --ask-vault-pass
```

These roles by default install the intended packages and leaves the system in an unconfigured state. In other words, the default variables defined in the roles/*/default files use the package defaults upon installation. You can override them in your vars files, as I've done here. All configuration is handled in one location, ./vars/ directory. I rather like this structure because you get to reuse the default roles in different combinations and scenarios, and your variables are isolated a bit more. You can also add these variables within your inventory/ dirs for individual host declared variables. I didn't because they have, at this point, been group wide. So technically, I could add these to the group_vars/ Ansible specific files.

#Network configuration

For the Bacula cluster deployment to work on your local machine, you need to have domain name resolution handled.  You can accomplish this several ways. A simply dirty way is to write the hosts into your local /etc/hosts file. The first play in the Ansible ./site.yml file accomplishes this on the VMs private network, but not on your local host machine. You'll have to add the entries to resolve the names to IPs (or just use the IPs). For example, when navigating to the bacula-web app at baculadir1.dev.lan, without the /etc/hosts entries, you'll never get it resolved.

The production approach is using a DNS server.

###Bacula Director

To use the Director, log into the baculadir1.dev.lan VM:
```
vagrant ssh baculadir1.dev.lan
```

Become root:

```
sudo su
```

Run bconsole to create a backup job on a remote host:

```
bconsole
```

```
1000 OK: baculadir1.dev.lan-dir Version: 5.2.6 (21 February 2012)
Enter a period to cancel a command.

```
Then type status
```
*status

Status available for:
     1: Director
     2: Storage
     3: Client
     4: All
Select daemon type for status (1-4): 3
The defined Client resources are:
     1: baculadir1.dev.lan-fd
     2: baculafd1.dev.lan-fd
     3: baculasd1.dev.lan-fd
Select Client (File daemon) resource (1-3): 3
Connecting to Client baculasd1.dev.lan-fd at baculasd1.dev.lan:9102

baculasd1.dev.lan-fd Version: 5.2.6 (21 February 2012)  x86_64-pc-linux-gnu ubuntu 14.04
Daemon started 06-Oct-15 01:41. Jobs: run=0 running=0.
 Heap: heap=270,336 smbytes=15,851 max_bytes=15,998 bufs=48 max_bufs=49
 Sizeof: boffset_t=8 size_t=8 debug=0 trace=0 
Running Jobs:
Director connected at: 06-Oct-15 01:53
No Jobs running.
====

Terminated Jobs:
====
*run
Automatically selected Catalog: MyCatalog
Using Catalog "MyCatalog"
A job name must be specified.
The defined Job resources are:
     1: BackupLocalFiles
     2: BackupCatalog
     3: RestoreLocalFiles
     4: Backupbaculafd1
     5: Backupbaculasd1
Select Job resource (1-5): 5
Run Backup job
JobName:  Backupbaculasd1
Level:    Incremental
Client:   baculasd1.dev.lan-fd
FileSet:  Home and Etc
Pool:     RemoteFile (From Job resource)
Storage:  File (From Job resource)
When:     2015-10-06 01:53:18
Priority: 10
OK to run? (yes/mod/no): yes
Job queued. JobId=1
You have messages.
*messages
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: No prior Full backup Job record found.
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: No prior or suitable Full backup found in catalog. Doing FULL backup.
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: Start Backup JobId 1, Job=Backupbaculasd1.2015-10-06_01.53.22_04
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: Created new Volume "Remote-0001" in catalog.
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: Using Device "FileStorage"
06-Oct 01:53 baculadir1.dev.lan-sd JobId 1: Labeled new Volume "Remote-0001" on device "FileStorage" (/bacula/backup).
06-Oct 01:53 baculadir1.dev.lan-sd JobId 1: Wrote label to prelabeled Volume "Remote-0001" on device "FileStorage" (/bacula/backup)
06-Oct 01:53 baculadir1.dev.lan-sd JobId 1: Job write elapsed time = 00:00:01, Transfer rate = 866.6 K Bytes/second
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: Bacula baculadir1.dev.lan-dir 5.2.6 (21Feb12):
  Build OS:               x86_64-pc-linux-gnu ubuntu 14.04
  JobId:                  1
  Job:                    Backupbaculasd1.2015-10-06_01.53.22_04
  Backup Level:           Full (upgraded from Incremental)
  Client:                 "baculasd1.dev.lan-fd" 5.2.6 (21Feb12) x86_64-pc-linux-gnu,ubuntu,14.04
  FileSet:                "Home and Etc" 2015-10-06 01:53:22
  Pool:                   "RemoteFile" (From Job resource)
  Catalog:                "MyCatalog" (From Client resource)
  Storage:                "File" (From Job resource)
  Scheduled time:         06-Oct-2015 01:53:18
  Start time:             06-Oct-2015 01:53:24
  End time:               06-Oct-2015 01:53:25
  Elapsed time:           1 sec
  Priority:               10
  FD Files Written:       1,794
  SD Files Written:       1,794
  FD Bytes Written:       655,309 (655.3 KB)
  SD Bytes Written:       866,612 (866.6 KB)
  Rate:                   655.3 KB/s
  Software Compression:   62.3 %
  VSS:                    no
  Encryption:             no
  Accurate:               no
  Volume name(s):         Remote-0001
  Volume Session Id:      1
  Volume Session Time:    1444095689
  Last Volume Bytes:      908,459 (908.4 KB)
  Non-fatal FD errors:    0
  SD Errors:              0
  FD termination status:  OK
  SD termination status:  OK
  Termination:            Backup OK

06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: Begin pruning Jobs older than 6 months .
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: No Jobs found to prune.
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: Begin pruning Files.
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: No Files found to prune.
06-Oct 01:53 baculadir1.dev.lan-dir JobId 1: End auto prune.

*
```
Or to label and mount storage:

```
*label
The defined Storage resources are:
     1: File
     2: RemoteFile
Select Storage resource (1-2): 2
Enter new Volume name: RemoteVolume
Defined Pools:
     1: Default
     2: File
     3: Scratch
     4: RemoteFile
Select the Pool (1-4): 4
Connecting to Storage daemon RemoteFile at baculasd1.dev.lan:9103 ...
Sending label command for Volume "RemoteVolume" Slot 0 ...
3000 OK label. VolBytes=224 DVD=0 Volume="RemoteVolume" Device="FileStorage" (/bacula/backup)
Catalog record for Volume "RemoteVolume", Slot 0  successfully created.
Requesting to mount FileStorage ...
3906 File device ""FileStorage" (/bacula/backup)" is always mounted.
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



