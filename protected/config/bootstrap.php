<?php
Yii::setAlias('@webroot', dirname(dirname(__DIR__)));
Yii::setAlias('@app', dirname(dirname(__DIR__)) . '/protected');
Yii::setAlias('@themes', dirname(dirname(__DIR__)) . '/themes');
Yii::setAlias('@public', dirname(dirname(__DIR__)) . '/public');
Yii::setAlias('@mail', dirname(dirname(__DIR__)) . '/mail');
Yii::setAlias('@modules', '@app/modules');