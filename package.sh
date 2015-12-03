#!/bin/bash

if ! git diff --exit-code >/dev/null; then
    echo "Working copy not clean. Please commit first!"
    exit 1
fi

if ! bash -c "cd ../common/; git diff --exit-code >/dev/null" ; then
    echo "Working copy of common not clean. Please commit first!"
    exit 1
fi
if ! bash -c "cd ../fww-kiid-patterns/; git diff --exit-code >/dev/null" ; then
    echo "Working copy of fww-kiid-patterns not clean. Please commit first!"
    exit 1
fi

DIR=kiid-extract-package_`date +%F-%H%M%S`

echo "Creating package in $DIR ..."
mkdir $DIR

xargs -I '{}' --arg-file=files.txt cp -pr {} $DIR

cd $DIR

# Revision: this repo, common, kiid-patterns
echo `bash -c "cd ../../common/; git rev-parse HEAD; git rev-parse --abbrev-ref HEAD; pwd"` >revisions.txt
echo `bash -c "cd ../../fww-kiid-patterns/; git rev-parse HEAD; git rev-parse --abbrev-ref HEAD; pwd"` >>revisions.txt
echo `bash -c "cd ../; git rev-parse HEAD; git rev-parse --abbrev-ref HEAD; pwd"` >>revisions.txt

echo "Creating ZIP archive $DIR.zip..."

zip -v -X -r ../$DIR.zip *

echo "Done."
