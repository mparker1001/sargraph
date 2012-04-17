Name:           sargraph
Version:        1.0
Release:        1%{?dist}
Summary:        Sargraph is a utility which displays memory, cpu, and load graphs from sar data.

Group:		Applications/Internet
BuildArch: 	noarch
License:        None
Source0:        sargraph-1.0.tar.gz
BuildRoot:      %{_tmppath}/%{name}-%{version}-%{release}-root-%(%{__id_u} -n)

Requires:       sysstat
Requires:	httpd
Requires:	php
Requires:	php-gd

%description
Sargraph is a utility which displays memory, cpu, and load graphs from sar data.

%prep
%setup -q


%build


%install
rm -rf $RPM_BUILD_ROOT
mkdir -p $RPM_BUILD_ROOT
mkdir -p $RPM_BUILD_ROOT/etc/cron.d
mkdir -p $RPM_BUILD_ROOT/usr/local/sbin
mkdir -p $RPM_BUILD_ROOT/etc/httpd/conf.d
mkdir -p $RPM_BUILD_ROOT/usr/share/sargraph
mkdir -p $RPM_BUILD_ROOT/usr/share/sargraph/www
mkdir -p $RPM_BUILD_ROOT/usr/share/sargraph/phplot
mkdir -p $RPM_BUILD_ROOT/usr/share/sargraph/phplot/contrib
mv cron/sargraph.cron $RPM_BUILD_ROOT/etc/cron.d
mv bin/parsegraph.sh $RPM_BUILD_ROOT/usr/local/sbin
mv conf/httpd/conf.d/sargraph.conf $RPM_BUILD_ROOT/etc/httpd/conf.d
mv README $RPM_BUILD_ROOT/usr/share/sargraph
mv www/* $RPM_BUILD_ROOT/usr/share/sargraph/www
mv vendor/phplot/* $RPM_BUILD_ROOT/usr/share/sargraph/phplot

%post
echo " "
if [ `php -m | grep gd | wc -l` != 1 ]; then echo "PHP GD Library not detected. You must install GD before sargraph will function properly"; echo " "; fi
echo "Graph data will update nightly at 1 AM server time. Modify /etc/cron.d/sargraph.cron to change this automated update."
echo " "
echo "** You must run /usr/local/sbin/parsegraph.sh and set an allow rule in /etc/httpd/conf.d/sargraph.conf before using sargraph **"
echo " "
echo "Sargraph can be accessed by going to http://<SERVER_IP>/sargraph"
echo " "

%clean
rm -rf $RPM_BUILD_ROOT

%files
%defattr(-,root,root,-)
/usr/share/sargraph/phplot/README.txt
/usr/share/sargraph/phplot/rgb.inc.php
/usr/share/sargraph/phplot/NEWS.txt
/usr/share/sargraph/phplot/phplot.php
/usr/share/sargraph/phplot/NEWS_part1.txt
/usr/share/sargraph/phplot/COPYING
/usr/share/sargraph/phplot/ChangeLog
/usr/share/sargraph/phplot/Makefile
/usr/share/sargraph/phplot/contrib/README.txt
/usr/share/sargraph/phplot/contrib/prune_labels.php
/usr/share/sargraph/phplot/contrib/prune_labels.test.php
/usr/share/sargraph/phplot/contrib/color_range.test1.php
/usr/share/sargraph/phplot/contrib/color_range.php
/usr/share/sargraph/phplot/contrib/color_range.test2.php
/usr/share/sargraph/phplot/contrib/data_table.example2.php
/usr/share/sargraph/phplot/contrib/prune_labels.example.php
/usr/share/sargraph/phplot/contrib/data_table.example1.php
/usr/share/sargraph/phplot/contrib/color_range.example.php
/usr/share/sargraph/phplot/contrib/data_table.example3.php
/usr/share/sargraph/phplot/contrib/data_table.php
/usr/share/sargraph/www/index.html
/usr/share/sargraph/www/graph.php
/usr/share/sargraph/www/day.php
/usr/share/sargraph/www/alltime.php
/usr/share/sargraph/README
/usr/local/sbin/parsegraph.sh
/etc/cron.d/sargraph.cron
/etc/httpd/conf.d/sargraph.conf

%changelog
