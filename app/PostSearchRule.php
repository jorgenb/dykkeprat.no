<?php

namespace App;

use ScoutElastic\SearchRule;

class PostSearchRule extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'plain'
                ],
                'parsedpagetext' => [
                    'type' => 'plain'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        return [
            'must' => [
                'multi_match' => [
                    'query' => $this->builder->query,
                    'fields' => [
                        'title^3',
                        'parsedpagetext'
                    ],
                ]
            ],
        ];
    }
}
