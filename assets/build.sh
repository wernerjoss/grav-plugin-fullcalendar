#!/bin/bash

echo "[INFO] Building CSS"
dir=$(dirname $-1)
cd $dir
scss  --watch sass/:css/ &

echo "[INFO] Building JS"
npx webpack --display-modules &

