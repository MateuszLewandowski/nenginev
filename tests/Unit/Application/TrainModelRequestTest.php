<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application;

use App\ComputationalIntelligence\Model\Application\TrainModelRequest;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use App\ComputationalIntelligence\Model\EvaluationFunction\MeanSquaredError;
use App\ComputationalIntelligence\Model\Optimizer\Adam;
use App\ComputationalIntelligence\Model\Network\Continuous;
use App\ComputationalIntelligence\Model\Network\Coefficient;
use App\ComputationalIntelligence\Model\Network\Dropout;
use App\ComputationalIntelligence\Model\ActivationFunction\Linear;
use App\ComputationalIntelligence\Model\Network\Sense;
use App\ComputationalIntelligence\Model\Initializer\He;
use App\ComputationalIntelligence\Model\Network\Dense;
use App\ComputationalIntelligence\Model\Network\Hidden;
use App\ComputationalIntelligence\Model\Network\Stream;
use App\ComputationalIntelligence\Network;

final class TrainModelRequestTest extends TestCase
{
    #[DataProvider('httpRequestPayloadProvider')]
    public function testCreateRequest(Request $request, array $expected): void
    {
        $trainModelRequest = TrainModelRequest::fromHttpRequest($request);

        $this->assertSame($expected, $trainModelRequest->jsonSerialize());
    }

    public static function httpRequestPayloadProvider(): Generator
    {
        $payload = [
            'config.batches' => 100,
            'config.batchSize' => 7,
            'config.alpha' => 1e-4,
            'config.epochs' => 1000,
            'config.minimumChange' => 1e-3,
            'config.window' => 5,
            'config.holdOut' => .2,

            'stream.length' => 14,

            'continuous.lossFunction' => 'meanSquaredError',

            'optimizer' => 'adam',

            'hiddens' => [
                [
                    'dense.neurons' => 32,
                    'dense.alpha' => 1e-4,
                    'dense.initializer' => 'he',
                    'sense.activationFunction' => 'relu',
                    'dropout.coefficient' => .2,
                ]
            ],

            'costFunction' => 'meanSquaredError',
        ];

        yield [
            'request' => Request::create('/', Request::METHOD_POST, $payload),
            'expected' => [
                'type' => TrainModelRequest::class,
                'args' => [
                    'config' => [
                        'batches' => [
                            'value' => '100'
                        ],
                        'batchSize' => [
                            'value' => '7'
                        ],
                        'alpha' => [
                            'value' => '0.0001'
                        ],
                        'epochs' => [
                            'value' => '1000'
                        ],
                        'minimumChange' => [
                            'value' => '0.001'
                        ],
                        'window' => [
                            'value' => '5'
                        ],
                        'holdOut' => [
                            'value' => '0.2'
                        ]
                    ],
                    'network' => [
                        'type' => Network::class,
                        'args' => [
                            'stream' => [
                                'type' => Stream::class,
                                'args' => [
                                    'neurons' => 14.0
                                ]
                            ],
                            'hiddens' => [
                                [
                                    'type' => Hidden::class,
                                    'args' => [
                                        'dense' => [
                                            'type' => Dense::class,
                                            'args' => [
                                                'neurons' => 32.0,
                                                'alpha' => 0.0001,
                                                'initializer' => [
                                                    'type' => He::class
                                                ]
                                            ]
                                        ],
                                        'sense' => [
                                            'type' => Sense::class,
                                            'args' => [
                                                'activation function' => [
                                                    'type' => Linear::class
                                                ]
                                            ]
                                        ],
                                        'dropout' => [
                                            'type' => Dropout::class,
                                            'args' => [
                                                'coefficient' => [
                                                    'type' => Coefficient::class,
                                                    'args' => [
                                                        'value' => 0.2,
                                                        'ratio' => 0.2,
                                                        'scale' => 1.25
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'continuous' => [
                                'type' => Continuous::class,
                                'args' => [
                                    'neurons' => 1,
                                    'lossFunction' => [
                                        'type' => MeanSquaredError::class
                                    ]
                                ]
                            ],
                            'optimizer' => [
                                'type' => Adam::class,
                                'args' => [
                                    'learningRate' => 0.0001,
                                    'momentum' => 0.1,
                                    'decay' => 0.001
                                ]
                            ]
                        ]
                    ],
                    'costFunction' => [
                        'type' => MeanSquaredError::class
                    ]
                ]
            ],
        ];
    }
}