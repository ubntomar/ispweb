#!/bin/bash

echo "SET NAME TO COMPANY"
grep -Er 'Wisdev' ../ispweb/* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Wisdev/DevXm/g' % |grep DevXm
grep -Er 'Isp Experts' ../ispweb/* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Isp Experts/DevXm/g' % |grep DevXm
grep -Er 'Isp Experts-Administrador' ../ispweb/* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Isp Experts-Administrador/DevXm/g' % |grep DevXm
grep -Er 'Netmx' ../ispweb/* |sed 's/\.\/x://g' |awk '{print $1}' |sed 's/://g' | xargs -I % sed -i 's/Netmx/DevXm/g' % |grep DevXm
