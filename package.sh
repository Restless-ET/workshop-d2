#!/bin/sh
mkdir build -p
rsync -avz . build/workshop --exclude build --exclude .git --exclude cache --exclude logs --exclude *.swp --exclude workshop.db
cd build
tar czvf doctrine2-workshop.tar.gz workshop --exclude build --exclude .git --exclude cache --exclude logs --exclude *.swp --exclude workshop.db
zip -r doctrine2-workshop.zip workshop --exclude build --exclude .git --exclude cache --exclude logs --exclude *.swp --exclude workshop.db
