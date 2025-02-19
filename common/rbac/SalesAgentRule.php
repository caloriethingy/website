<?php

namespace common\rbac;

class SalesAgentRule extends \yii\rbac\Rule
{
    public $name = 'assignedToSalesAgent';

    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params)
    {
        return isset($params['post']) && $params['post']->sales_agent_id == $user;
    }
}