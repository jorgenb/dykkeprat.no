<?php

namespace App;

use ScoutElastic\SearchRule;

class ThreadSearchRule extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        return [
            'must' => [
                'multi_match' => [
                    'query' => $this->builder->query,
                    'type' => 'bool_prefix',
                    'fields' => [
                        'title',
                        'title._2gram',
                        'title._3gram'
                    ]
                ]
            ]
        ];
    }
}
