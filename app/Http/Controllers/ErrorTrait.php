<?php

namespace App\Traits;

trait ErrorTrait
{
    public function getErrorCode(string $errorCode)
    {
      return  match ($errorCode) {
            '100' => [
                    'message' =>'Successful transaction.',
                    'action' => 'ACCEPTED',
                ],
            '101' => [
                    'message' =>'One or more fields in the request contain invalid data.Request is missing one or more required fields. Examine the response fields',
                    'action' => 'ERROR',
                ],
            '102' => [
                    'message' =>'One or more fields in the request contain invalid data.',
                    'action' => 'ERROR',
                ],
            '104' => [
                    'message' => 'A duplicate transaction was detected. The transaction might have already been processed.',
                    'action' => 'ERROR',
                ],
            '110' => [
                    'message' =>'Only a partial amount was approved.',
                    'action' => 'ACCEPTED',
                ],
            '150' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'ERROR'
                ],
            '151' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '152' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '200' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '201' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '202' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '203' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '204' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '205' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '207' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '208' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '210' => [
                'message' => 'Only a partial amount was approved.',
                'action' => 'DECLINED'
            ],
            '211' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '221' => [
                'message' => 'Only a partial amount was approved.',
                'action' => 'DECLINED'
            ],
            '222' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '230' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '231' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '232' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '233' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '234' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '236' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '240' => [
                'message' => 'Only a partial amount was approved.',
                'action' => 'DECLINED'
            ],
            '475' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '476' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '478' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            '481' => [
                'message' => 'Only a partial amount was approved.',
                'action' => 'DECLINED'
            ],
            '520' => [
                    'message' => 'Only a partial amount was approved.',
                    'action' => 'DECLINED'
                ],
            'default' => null
        };
    }
}