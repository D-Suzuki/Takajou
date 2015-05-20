#!/bin/sh

# 対象モデル入力
echo -n 'モデル作成対象のテーブル名を入力してください > '
read table_name

model_name=`echo ${table_name} | sed -r "s/(^|_)(.)/\U\2\E/g"`
echo $model_name

rm application/models/Frame/Layout/$model_name.php
phalcon model $table_name gsdb_trun --output=application/models/Frame/Layout --get-set --namespace=Frame\\Layout --force --extends=\\Frame\\Base --doc
