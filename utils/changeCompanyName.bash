#!/bin/bash

echo "Important!: This script must be excecuted  by bash nn.bash  "
echo "SET Cmpany name to=> DevXm "
grep -Er 'Wisdev' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Wisdev/DevXm/g' % |grep DevXm
grep -Er 'Isp Experts' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Isp Experts/DevXm/g' % |grep DevXm
grep -Er 'Isp Experts-Administrador' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Isp Experts-Administrador/DevXm/g' % |grep DevXm
grep -Er 'Netmx' ../* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Netmx/DevXm/g' % |grep DevXm
