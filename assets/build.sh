#!/bin/bash

sass --watch sass/:css/ &
npx webpack --display-modules &
