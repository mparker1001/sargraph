----
About
----

Author: Matt Parker
Date: 2012/04/06


----
Desciption
----

Sargraph is a utility which displays memory, cpu, and load graphs from sar data. 


----
Installation
----

File: bin/parsegraph.sh
Description: This bash script parses the sar data into a format that can be read by the sargraph PHP code. By default, the parsed data is stored in /var/cache/sargraph/. This script
can be run manually as root to update the sargraph data. Note that this script may be resource intensive.
Suggested location: /usr/local/sbin/

File: cron/sargraph.cron
Description: This file contains the crontab configuration that calls parsegraph.sh. Since parsegraph.sh is rather resource intensive, the cron is configured to run at midnight by default.
Suggested Location: /etc/cron.d/

File: conf/httpd/conf.d/sargraph.conf
Description: This configuration file creates a new virtual directory in Apache to access the sargraph images. By default, all hosts are blocked so the Allow directives will need to be configured
to allow access.
Suggested location: /etc/httpd/conf.d/

Files: www/*
Description: These are the sargraph php and html files that display, as a web page, the graphs generated from the phplot libraries.
Suggested location: /usr/share/sargraph/www/

Files: vendor/phplot/*
Description: These are the phplot libraries (http://sourceforge.net/projects/phplot) that generate graph images from the parsed data created by parsegraph.sh. 
Suggested location: /usr/share/sargraph/phplot/


----
Build
----

Files: rpmbuild/SPECS/*
Description: Spec files used for creating an RPM package of sargraph

Files: rpmbuild/RPMS/*
Description: RPM packages for installing sargraph on RedHat based distros


----
Dependencies
----

Packages not provided, but needed by sargraph:
- Apache
- PHP
- PHP GD Library
- Sysstat

