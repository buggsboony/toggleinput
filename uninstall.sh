#!/bin/bash

#install stuff
what=toggleinput
extension=.php
#peut être extension vide 
 
echo "killing running instances"
killall $what

echo "Supprimer lien symbolique vers usr bin"
sudo rm /usr/bin/$what

