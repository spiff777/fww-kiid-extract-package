#!/bin/bash
DIR=kiid-extract-package_`date +%F-%H%M%S`
mkdir $DIR
cd $DIR

# Revision: this repo, common, kiid-patterns
echo `bash -c "cd ../../common/; pwd; git rev-parse HEAD"` >revisions.txt
echo >>revisions.txt
echo `bash -c "cd ../../fww-kiid-patterns/; pwd; git rev-parse HEAD"` >>revisions.txt
echo >>revisions.txt
pwd >>revisions.txt
echo `git rev-parse HEAD` >>revisions.txt
