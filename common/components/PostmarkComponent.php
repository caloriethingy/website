<?php

namespace common\components;

use Postmark\PostmarkClient;
use yii\base\Component;

class PostmarkComponent extends Component
{
    /** @var PostmarkClient  */
    public PostmarkClient $client;
    public mixed $serverToken;

    public function init()
    {
        parent::init();
        $this->client = new PostmarkClient($this->serverToken);
    }
}