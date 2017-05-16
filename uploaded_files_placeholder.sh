#!/bin/bash

SCRIPTDIR=`php -r "echo dirname(realpath('$0'));"`
FIELD_SEPARATOR="@@@"

find "$1" -type f -exec file --separator "$FIELD_SEPARATOR" --mime "{}" ';' | php -f $SCRIPTDIR/uploaded_files_placeholder.php -- --field-separator "$FIELD_SEPARATOR" --source-directory "$1" --destination-directory "$2"
