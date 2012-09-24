<?php

Yii::import('ext.common.RestApiLog.models.RestApiLog');

class OtpApiLog extends RestApiLog
{
    function tableName()
    {
        return 'otp_api_log';
    }
}

?>
