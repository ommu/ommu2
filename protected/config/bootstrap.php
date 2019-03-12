<?php
Yii::setAlias('@webroot', dirname(dirname(__DIR__)));
Yii::setAlias('@app', '@webroot/protected');
Yii::setAlias('@themes', '@webroot/themes');
Yii::setAlias('@public', '@webroot/public');
Yii::setAlias('@modules', '@app/modules');