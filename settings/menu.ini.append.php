<?php /*

[NavigationPart]
Part[ezsysinfonavigationpart]=System Information

[TopAdminMenu]
Tabs[]=sysinfo

[Topmenu_sysinfo]
Name=System Information
Tooltip=Display detailed system information
URL[default]=sysinfo/index
NavigationPartIdentifier=ezsysinfonavigationpart

Enabled[]
Enabled[default]=true
Enabled[browse]=false
Enabled[edit]=false

Shown[]
Shown[default]=true
Shown[browse]=false
Enabled[edit]=true
Shown[navigation]=true

# This line means the tab will not be visible on eZP 4.3 and later unless the
# current user has access to the setup/system_info policy
# You can disable it if you use PolicyOmitList to allow access to the sysinfo module
PolicyList[]=setup/system_info

*/ ?>