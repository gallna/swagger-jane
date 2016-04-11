#!/bin/bash

rm -r -f generated/

swagger-codegen generate -i swagger.yml -o generated -l php
swagger-codegen generate -i swagger.yml -o generated -l slim

# generate json from yml
swagger-codegen generate -i swagger.yml -o generated -l swagger

# jane-openapi [schema-path] [namespace] [destination]
vendor/bin/jane-openapi generate generated/swagger.json Kemer\\Resource generated/Jane

mv generated/SwaggerClient-php generated/Client

mkdir -p generated/SwaggerServer/lib/Models
mv generated/SwaggerServer generated/Server
mv generated/SwaggerServer*/* generated/Server/lib/Models/
rm -r generated/SwaggerServer*
