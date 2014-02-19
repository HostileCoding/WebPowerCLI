Connect-VIServer -Server <vcenter_ip_address> -User <username> -Password <password>
$vswitch_name = "<vswitch_name>"
$esxcli = Get-EsxCli -VMHost (Get-VMHost -Name <host_ip_address>)
$esxcli.network.vswitch.standard.list($vswitch_name)