<?php

namespace common\components;

use common\models\Meal;
use Exception;
use Yii;
use yii\helpers\FileHelper;
use yii\httpclient\Client;

class GeminiApiComponent extends \yii\base\Component
{
    public Client $client;
    public string $apiKey;
    public string $baseUrl;
    public string $model;

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

    }

    public function mealInquiry($filePath)
    {
        $data = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        [
                            "text" => "INSERT_INPUT_HERE"
                        ]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        [
                            "inline_data" => [
                                "data" => base64_encode(file_get_contents($filePath)),
                                "mimeType" => FileHelper::getMimeType($filePath)
                            ]
                        ]
                    ]
                ]
            ],
            "systemInstruction" => [
                "role" => "user",
                "parts" => [
                    [
                        "text" => "Provide a caloric and macro estimate for pictures I provide to you. Try to be as accurate as possible and always calculate the everything you see in the picture. Proivde a 3 or 4 word `food_name`"
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 1,
                "topK" => 40,
                "topP" => 0.95,
                "maxOutputTokens" => 1000,
                "responseMimeType" => "application/json",
                "responseSchema" => [
                    "type" => "object",
                    "properties" => [
                        "food_name" => [
                            "type" => "string"
                        ],
                        "calories" => [
                            "type" => "integer"
                        ],
                        "protein" => [
                            "type" => "integer"
                        ],
                        "fat" => [
                            "type" => "integer"
                        ],
                        "carbohydrates" => [
                            "type" => "integer"
                        ],
                        "fiber" => [
                            "type" => "integer"
                        ]
                    ],
                    "required" => [
                        "food_name",
                        "calories",
                        "protein",
                        "fat",
                        "carbohydrates",
                        "fiber"
                    ]
                ]
            ]
        ];

        $response = $this->client
            ->post([$this->model, 'key' => $this->apiKey])
            ->setData($data)
            ->send();

        if ($response->statusCode != 200) {
            throw new Exception('There was an issue with the AI side of things - sorry! It is a MVP after all :/');
        }

        $meal = new Meal();
        $gemini = json_decode($response->getContent(), true);
        $geminiMeal = json_decode($gemini['candidates'][0]['content']['parts'][0]['text'], true);
        $meal->protein = $geminiMeal['protein'];
        $meal->calories = $geminiMeal['calories'];
        $meal->carbohydrates = $geminiMeal['carbohydrates'];
        $meal->fat = $geminiMeal['fat'];
        $meal->fiber = $geminiMeal['fiber'];
        $meal->food_name = $geminiMeal['food_name'];
        $meal->user_id = Yii::$app->user->id;
        $meal->file_name = $filePath;
        Yii::debug($meal);
        $meal->save();
        // @TODO catch unidentified pictures?

        return $meal->id;
    }

}
