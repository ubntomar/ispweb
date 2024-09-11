#!/bin/bash

echo "Important!: This script must be excecuted  by bash nn.bash  "
echo "SET Cmpany name to=> FastNet "
grep -Er 'Wisdev' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Wisdev/FastNet/g' % |grep FastNet
grep -Er 'Isp Experts' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Isp Experts/FastNet/g' % |grep FastNet
grep -Er 'Isp Experts-Administrador' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Isp Experts-Administrador/FastNet/g' % |grep FastNet
grep -Er 'Netmx' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Netmx/FastNet/g' % |grep FastNet
