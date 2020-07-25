#!/bin/bash

#install stuff
what=toggleinput
extension=.php
#peut être extension vide

echo "Set executable..."
chmod +x $what$extension
echo "Create Symbolic link to /usr/bin folder"
sudo ln -s "$PWD/$what$extension" /usr/bin/$what

 