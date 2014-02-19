WebPowerCLI is a webpage which allow you to run PowerCLI commands. A major advantage of running PowerCLI through a webpage is that you can write and run scripts virtually from any platform having a web browser.


How to use it:

First cmdlet of your scripts must always be Connect-VIServer (...) in order to establish connection to the vCenter Server or directly to an ESXi host.



Installation steps:

1)Install Windows 2012 and configure networking according to your vSphere environment (Windows server must be able to communicate with vCenter Server/ESXi hosts).

2)Install IIS Web Server

3)Install PHP5

4)Open PowerShell and run "Set-ExecutionPolicy Unrestricted" command.

5)Install VMware PowerCLI

6)Copy all files from webpowercli archive to your www directory. You could either create a new website from IIS or replace the default one.