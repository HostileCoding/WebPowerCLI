Connect-VIServer -Server <vcenter_ip_address> -User <username> -Password <password>
$hosts_ip_address = @("<host1_ip_address>","<host2_ip_address>","<host3_ip_address>")
Get-VirtualSwitch -VMHost (Get-VMHost -Name $hosts_ip_address)