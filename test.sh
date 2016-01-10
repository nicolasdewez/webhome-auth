#!/usr/bin/expect

spawn app/console doctrine:generate:entity
expect "The Entity shortcut name"
send "AppBundle:Post\r"
interact