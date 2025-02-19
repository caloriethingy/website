<?php

namespace common\components;

use common\jobs\EmailJob;
use common\models\Account;
use common\models\InvoiceItem;
use common\models\SalesAgent;
use common\models\Service;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;

class SonarApiComponent extends \yii\base\Component
{
    public Client $client;
    public string $baseUrl;
    public string $bearerToken;

    public function init()
    {
        parent::init();
        $this->client = new Client([
            'baseUrl' => $this->baseUrl,
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);

        // Setup event for auth before each send
        $this->client->on(Request::EVENT_BEFORE_SEND, function (RequestEvent $event) {
            $event->request->addHeaders(['Authorization' => 'Bearer ' . $this->bearerToken]);
        });
    }

    public function getAccounts(int $page = 1, int $limit = 100)
    {
        $data = [
            'form_params' => [
                'query' => 'query accountsWithSalesAgents(
                          $paginator: Paginator,
                          $search: [Search],
                          $sorter: [Sorter]
                        ) {
                                accounts(
                                    paginator: $paginator
                            search: $search
                            sorter: $sorter
                            reverse_relation_filters: {
                                    relation: "custom_field_data",
                              search: {
                                        integer_fields: {
                                            attribute: "custom_field_id",
                                  search_value: 12,
                                  operator: EQ
                                }
                                    }
                            }
                            general_search_mode: ROOT_PLUS_RELATIONS
                            account_status_id: 1
                          ) {
                                    entities {
                                        id
                              name
                              account_status {
                                            id
                                name
                              }
                              account_services {
                                    entities {
                                  id
                                  quantity
                                  name_override
                                  price_override
                                  price_override_reason
                                  service {
                                    id
                                    name
                                    amount
                                    enabled
                                    application
                                    type
                                  }
                                }
                                page_info {
                                                page
                                  records_per_page
                                  total_count
                                  total_pages
                                }
                              }
                              custom_field_data(custom_field_id:12) {
                                            entities {
                                                id
                                  custom_field_id
                                  value
                                }
                              }
                            }
                            page_info {
                                        page
                              records_per_page
                              total_count
                              total_pages
                            }
                          }
                        }',
                'variables' => [
                    'paginator' => [
                        'page' => $page,
                        'records_per_page' => $limit
                    ],
                    'search' => [],
                    'sorter' => [
                        [
                            'attribute' => 'updated_at',
                            'direction' => 'ASC',
                        ]
                    ],
                ]
            ]
        ];

        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setData($data)
            ->send();

        $account = json_decode($response->getContent(), true);

        return $account['form_params']['data'];
    }

    public function getInvoices(string $startDate, string $endDate)
    {
        $page = 1;
        $limit = 100;
        $invoices = [];
        do {
            $data = [
                'form_params' => [
                    'query' => 'query accountInvoice($paginator: Paginator, $search: [Search], $sorter: [Sorter]) {
                      invoices(
                        paginator: $paginator
                        search: $search
                        sorter: $sorter
                        general_search_mode: ROOT_PLUS_RELATIONS
                      ) {
                        entities {
                          id
                          account_id
                          total_debits
                          void
                          remaining_due
                          date
                          due_date
                          end_date
                          delinquent
                          debits {
                            entities {
                              id
                              quantity
                              service_id
                              service_name
                              amount
                            }
                          }
                          credits {
                            entities {
                              amount
                            }
                          }
                        }
                        page_info {
                          page
                          records_per_page
                          total_count
                          total_pages
                        }
                      }
                    }',
                    'variables' => [
                        'paginator' => [
                            'page' => $page,
                            'records_per_page' => $limit
                        ],
                        'search' => [
                            [
                                'date_fields' => [
                                    ['attribute' => 'date', 'search_value' => $startDate, 'operator' => 'GTE'],
                                    ['attribute' => 'date', 'search_value' => $endDate, 'operator' => 'LTE'],
                                ]
                            ]
                        ]

                    ],
                    'sorter' => [
                        [
                            'attribute' => 'updated_at',
                            'direction' => 'ASC',
                        ]
                    ],
                ]
            ];

            $response = $this->client->createRequest()
                ->setMethod('POST')
                ->setData($data)
                ->send();


            $responseData = json_decode($response->getContent(), true);
            $invoices = array_merge($invoices, $responseData['form_params']['data']['invoices']['entities']);
            $page++;
        } while ($page < ($responseData['form_params']['data']['invoices']['page_info']['total_pages'] + 1));

        return $invoices;
    }

    public function getInvoice(int $invoiceId = 1)
    {
        $page = 1;
        $limit = 100;
        $data = [
            'form_params' => [
                'query' => 'query accountInvoice($paginator: Paginator, $search: [Search], $sorter: [Sorter]) {
                      invoices(
                        id: ' . $invoiceId . '
                        paginator: $paginator
                        search: $search
                        sorter: $sorter
                        general_search_mode: ROOT_PLUS_RELATIONS
                      ) {
                        entities {
                          id
                          account_id
                          total_debits
                          void
                          remaining_due
                          date
                          due_date
                          end_date
                          delinquent
                          debits {
                            entities {
                              id
                              quantity
                              service_id
                              service_name
                              amount
                            }
                          }
                          credits {
                            entities {
                              amount
                            }
                          }
                        }
                        page_info {
                          page
                          records_per_page
                          total_count
                          total_pages
                        }
                      }
                    }',
                'variables' => [
                    'paginator' => [
                        'page' => $page,
                        'records_per_page' => $limit
                    ],
                    'search' => [],
                    'sorter' => [
                        [
                            'attribute' => 'updated_at',
                            'direction' => 'ASC',
                        ]
                    ],
                ]
            ]
        ];

        $response = $this->client->createRequest()
            ->setMethod('POST')
            ->setData($data)
            ->send();

        $invoice = json_decode($response->getContent(), true);

        return $invoice['form_params']['data']['invoices']['entities'][0];
    }

    public function storeInvoices($invoices)
    {
        foreach ($invoices as $invoice) {
            $this->storeInvoice($invoice);
        }
    }

    public function storeInvoice($invoice)
    {
        \Yii::debug($invoice);
        // $remainingDue is the Entire Invoice remaining to be paid amount, 0 = everything paid
        $remainingDue = $invoice['remaining_due'];
        // debits = charges on the account
        // credits = payments on the account

        foreach ($invoice['debits']['entities'] as $i => $rawItem) {
            $invoiceItem = InvoiceItem::find()->where(['sonar_id' => (int)$rawItem['id']])->one();
            if (null === $invoiceItem) { // create new invoice item

                $account = Account::findOne(['sonar_id' => (int)$invoice['account_id']]);
                $service = Service::findOne(['sonar_id' => (int)$rawItem['service_id']]);
                \Yii::debug($rawItem);
                if ($service && $account) {
                    \Yii::debug($invoice);
                    $payment = (isset($invoice['credits']['entities'][$i]['amount'])) ? $invoice['credits']['entities'][$i]['amount'] : 0;
                    // @todo check payment - i think it is wrong to assume we have the same credits and debits ^ CGS
                    $invoiceItem = new InvoiceItem([
                        'sonar_id' => (int)$rawItem['id'],
                        'account_id' => $account->id,
                        'service_id' => $service->id,
                        'name' => $rawItem['service_name'],
                        'status' => InvoiceItem::STATUS_OPEN,
                        'charge' => $rawItem['amount'],
                        'payment' => $payment,
                        'is_commissionable' => $service->hasCommission(),
                    ]);
                    $invoiceItem->save();
                }
            }

            // is the invoice item paid?
            if ($invoiceItem && $remainingDue == 0) {
                $invoiceItem->status = InvoiceItem::STATUS_PAYMENT_RECEIVED;
                $invoiceItem->save();
            }
        }
    }

    private function mapAccounts($accounts)
    {
        $mapped = [];
        $i = 0;
        $db = \Yii::$app->db;
        foreach ($accounts as $account) {
            $mapped[$i]['sonar_id'] = (int)$account['id'];
            $mapped[$i]['name'] = $account['name'];

            /**
             * [
             * 'id' => '132'
             * 'quantity' => 1
             * 'name_override' => 'Bradley'
             * 'price_override' => 0
             * 'price_override_reason' => 'testing'
             * 'service' => [
             * 'id' => '10'
             * 'name' => 'Business Giga Speed Internet'
             * 'amount' => 18000
             * 'enabled' => true
             * 'application' => 'DEBIT'
             * 'type' => 'DATA'
             * ]
             * ]
             */
            $mapped[$i]['services'] = []; // init empty array

            foreach ($account['account_services']['entities'] as $key => $account_service) {
                $mapped[$i]['services'][$key]['sonar_id'] = (int)$account_service['service']['id'];
                $mapped[$i]['services'][$key]['name'] = (!empty($account_service['name_override'])) ? $account_service['name_override'] : $account_service['service']['name'];
                $mapped[$i]['services'][$key]['price'] = (!empty($account_service['price_override'])) ? $account_service['price_override'] : $account_service['service']['amount'];
                if ($account_service['service']['application'] === 'CREDIT') {
                    $mapped[$i]['services'][$key]['price'] = -1 * $mapped[$i]['services'][$key]['price'];// store as a negative
                }

                // set to 0 if null after credit
                if (null === $mapped[$i]['services'][$key]['price']) {
                    $mapped[$i]['services'][$key]['price'] = 0;
                }
            }

            $name = $account['custom_field_data']['entities'][0]['value'];
            $salesAgent = SalesAgent::findOne(['name' => $name]);
            if (null === $salesAgent) {
                $salesAgent = new SalesAgent(['name' => $name]);
                $salesAgent->save();
            }

            $mapped[$i]['sales_agent_id'] = $salesAgent->id;

            $i++;
        }
        return $mapped;
    }

    public function storeAccounts()
    {
        $page = 1;
        do {
            $batch = $this->getAccounts($page, 100);
            $accounts = $this->mapAccounts($batch['accounts']['entities']);

            foreach ($accounts as $account) {
                $accountModel = Account::findOne(['sonar_id' => $account['sonar_id']]);
                if (null === $accountModel) {
                    $accountModel = new Account([
                        'sonar_id' => $account['sonar_id'],
                        'name' => $account['name'],
                        'sales_agent_id' => $account['sales_agent_id'],
                    ]);
                    $accountModel->save();
                } else {
                    //$accountModel->sonar_id = $account['sonar_id'];
                    $accountModel->name = $account['name'];
                    $accountModel->sales_agent_id = $account['sales_agent_id'];
                    $accountModel->save();
                }

                foreach ($account['services'] as $rawServiceData) {
                    $serviceModel = Service::findOne(['sonar_id' => (int)$rawServiceData['sonar_id']]);
                    if (null === $serviceModel) {
                        $serviceModel = new Service([
                            'sonar_id' => $rawServiceData['sonar_id'],
                            'name' => $rawServiceData['name'],
                            'price' => $rawServiceData['price'],
                            'account_id' => $accountModel->id,
                            'active' => 1, // @todo pull active state in from sonar api
                        ]);
                        $serviceModel->save();
                    } else {
                        $serviceModel->commission = $serviceModel->getFormattedDollar($serviceModel->commission, false);
                        $serviceModel->sonar_id = $rawServiceData['sonar_id'];
                        $serviceModel->name = $rawServiceData['name'];
                        $serviceModel->price = $rawServiceData['price'];
                        $serviceModel->account_id = $accountModel->id;
                        if (!empty($serviceModel->dirtyAttributes)) {
                            if (isset($serviceModel->dirtyAttributes['price'])) {
                                //Yii::$app->queue->push(new EmailJob([
                                //    'templateAlias' => EmailJob::PRICE_CHANGE,
                                //    "email" => Yii::$app->params['adminEmail'],
                                //    'templateModel' => [
                                //        "action_edit_url" => Yii::$app->urlManager->createAbsoluteUrl(
                                //            ['service/update', 'id' => $serviceModel->id]
                                //        ),
                                //    ]
                                //]));
                            }
                        }
                        $serviceModel->save();
                    }
                }
            }

            $page++;
        } while ($page < ($batch['accounts']['page_info']['total_pages'] + 1));
    }

    public function processInvoices(int $invoiceId)
    {
        dump($this->getInvoice($invoiceId));
    }

}