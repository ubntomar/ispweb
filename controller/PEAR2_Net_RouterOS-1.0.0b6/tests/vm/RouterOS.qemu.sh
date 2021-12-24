#!/usr/bin/env bash
# TODO: Find a way to have the equivalent of VirtualBox's "host-only adapter",
# allowing separate IP addresses, so that the host could access the same ports,
# once on its own IP, and then those ports on the guest's IP.
qemu-system-x86_64 "output-qemu/RouterOS-${ROS_VERSION}.raw" "-name" "RouterOS-${ROS_VERSION}" "-display" "sdl" "-machine" "type=pc,accel=tcg" "-netdev" "user,id=user.0,net=192.168.56.0/24,host=192.168.56.2,dhcpstart=192.168.56.101,hostfwd=tcp::38728-:8728" "-device" "virtio-net,netdev=user.0,mac=DE:AD:BE:EF:00:01" "-netdev" "user,id=user.1" "-device" "virtio-net,netdev=user.1,mac=DE:AD:BE:EF:00:02" "-netdev" "user,id=user.2" "-device" "virtio-net,netdev=user.2,mac=DE:AD:BE:EF:00:03" "-chardev" "null,id=con0" "-device" "virtio-serial" "-device" "virtserialport,chardev=con0" "-m" "128M"
