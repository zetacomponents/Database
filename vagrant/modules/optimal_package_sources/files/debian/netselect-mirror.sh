#!/bin/bash
set -o errexit

STATSFILE="$(tempfile)"
netselect -v -s10 -t20 `wget -q -O- https://launchpad.net/ubuntu/+archivemirrors | grep -P -B8 "statusUP|statusSIX" | grep -o -P "(f|ht)tp.*\"" | tr '"\n' '  '` >"${STATSFILE}"
BESTMIRROR="$(cat "${STATSFILE}" | head -n1 | awk '{print $2}')"
echo "# PUPPET: MIRROR AUTOSELECTED DUE TO FASTEST NETSTAT" >/etc/apt/sources.list.fastest
sed -e "s@http://us.archive.ubuntu.com/ubuntu/@${BESTMIRROR}@g" "/etc/apt/sources.list" >>/etc/apt/sources.list.fastest
mv /etc/apt/sources.list /etc/apt/sources.list.dist
mv /etc/apt/sources.list.fastest /etc/apt/sources.list
