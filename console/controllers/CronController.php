<?php

namespace console\controllers;

use yii\console\{Controller, ExitCode};

// To create/edit crontab file: crontab -e
// To list: crontab -l
// // m h  dom mon dow   command
// */5 * * * * /var/www/html/yii cron/frequent
// */15 * * * * /var/www/html/yii cron/quarter
// */30 * * * * /var/www/html/yii cron/halfhourly
// 0 * * * * /var/www/html/yii cron/hourly
// 15 1 * * * /var/www/html/yii cron/overnight
// 15 3 * * 5 /var/www/html/yii cron/weekly

/**
 * Class CronController
 *
 * @package console\controllers
 */
class CronController extends Controller
{

    /**
     * @var boolean whether to run the command interactively.
     */
    public $interactive = false;

    /**
     * Action Index
     * @return int exit code
     */
    public function actionIndex()
    {
        $this->stdout("Yes, service cron is running\n");
        return ExitCode::OK;
    }

    /**
     * Action Frequent
     * Called every five minutes
     * @return int exit code
     */
    public function actionFrequent()
    {
         return ExitCode::OK;
    }

    /**
     * Action Quarter
     * Called every fifteen minutes
     *
     * @return int exit code
     */
    public function actionQuarter()
    {
        //

        return ExitCode::OK;
    }

    /**
     * Action Half Hourly
     * Called every 30 minutes
     *
     * @return int exit code
     */
    public function actionHalfhourly()
    {

        return ExitCode::OK;
    }

    /**
     * Action Hourly
     * @return int exit code
     */
    public function actionHourly()
    {
        return ExitCode::OK;
    }

    /**
     * Action Overnight
     * Called every night
     *
     * @return int exit code
     */
    public function actionOvernight()
    {

        return ExitCode::OK;
    }

}
