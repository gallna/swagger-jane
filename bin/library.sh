#!/bin/bash

rm -r -f generated/Library

# swagger-codegen generate -i library.yml -o generated/Library -l php
# generate json from yml
swagger-codegen generate -i http://library.weeb.dev/swagger.yml -o generated/Library -l swagger

# jane-openapi [schema-path] [namespace] [destination]
vendor/bin/jane-openapi generate generated/Library/swagger.json Kemer\\Library generated/Library
