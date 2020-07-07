#!/bin/bash

BASEDIR=$(dirname "$0")
cd $BASEDIR
php -S localhost:80 -t apps