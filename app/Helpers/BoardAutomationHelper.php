<?php

namespace App\Helpers;

use App\Models\Status;

class BoardAutomationHelper {
    public const TRIGGERS_TYPE = [
        ['id' => 'card_move', 'label' => 'Card Move', 'icon' => 'bx bx-move'],
        ['id' => 'card_changes', 'label' => 'Card Changes', 'icon' => 'bx bx-pencil'],
        ['id' => 'dates', 'label' => 'Card Changes', 'icon' => 'bx bx-calendar'],
        ['id' => 'card_content', 'label' => 'Card Content', 'icon' => 'bx bx-chat'],
        ['id' => 'timer', 'label' => 'Timer', 'icon' => 'bx bx-time-five']
    ];

    public const TRIGGERS = [
        [
            'type' => 'card_move',
            'name' => 'card_move_to_board',
            'prefix' => 'when a card is',
            'suffix' => '',
            'description' => '"Added" means created, copied, moved into the board or emailed into the board.',
            'options' => [
                ['id' => 1, 'items' => self::OPERATORS['move'], 'value' => self::OPERATORS['move'][0]],
                ['id' => 2, 'items' => self::OPERATORS['where'], 'value' => self::OPERATORS['where'][0]],
                ['id' => 3, 'items' => self::OPERATORS['who'], 'value' => self::OPERATORS['who'][0]]
            ],
        ],
        [
            'type' => 'card_move',
            'name' => 'card_move_to_board',
            'prefix' => 'when a card is',
            'suffix' => '',
            'description' => '"Added" means created, copied, emailed or moved into the list.',
            'options' => [
                ['id' => 1, 'items' => self::OPERATORS['move'], 'value' => self::OPERATORS['move'][0]],
                ['id' => 2, 'items' => self::OPERATORS['lists'], 'value' => ''],
                ['id' => 3, 'items' => self::OPERATORS['who'], 'value' => self::OPERATORS['who'][0]]
            ],
        ],
        [
            'type' => 'card_move',
            'name' => 'card_archive',
            'prefix' => 'when a card is',
            'suffix' => '',
            'description' => '',
            'options' => [
                ['id' => 1, 'items' => self::OPERATORS['archive'], 'value' => self::OPERATORS['archive'][0]],
                ['id' => 2, 'items' => self::OPERATORS['who'], 'value' => self::OPERATORS['who'][0]]
            ],
        ],
        [
            'type' => 'card_move',
            'name' => 'list_status',
            'prefix' => 'when a list is',
            'suffix' => '',
            'description' => '',
            'options' => [
                ['id' => 1, 'items' => self::OPERATORS['changes'], 'value' => self::OPERATORS['changes'][0]],
                ['id' => 2, 'items' => self::OPERATORS['who'], 'value' => self::OPERATORS['who'][0]]
            ],
        ],
        [
            'type' => 'card_changes',
            'name' => 'Card Changes',
            'prefix' => 'when a card is',
            'suffix' => '',
            'description' => '"Updated" means the card details were changed.',
            'options' => [
                ['id' => 1, 'items' => ['updated', 'deleted'], 'value' => 'updated'],
                ['id' => 2, 'items' => ['by me', 'by anyone'], 'value' => 'by me']
            ],
        ]
    ];

    public const ACTIONS_TYPE = [
        ['id' => 'move', 'label' => 'Card Move', 'icon' => 'bx bx-move'],
        ['id' => 'card_changes', 'label' => 'Card Changes', 'icon' => 'bx bx-pencil']
    ];

    public const ACTIONS = [
        [
            'type' => 'card_move',
            'name' => 'Card Move',
            'prefix' => 'when a card is',
            'suffix' => '',
            'description' => '"Added" means created, copied, moved into the board or emailed into the board.',
            'options' => [
                ['id' => 1, 'items' => ['added to', 'removed from'], 'value' => 'added to'],
                ['id' => 2, 'items' => ['the board', 'a list'], 'value' => 'the board'],
                ['id' => 3, 'items' => ['by me', 'by anyone'], 'value' => 'by me']
            ],

        ],
        [
            'type' => 'card_move',
            'name' => 'Card Move',
            'prefix' => 'when a card is',
            'suffix' => '',
            'description' => '"Added" means created, copied, moved into the board or emailed into the board.',
            'options' => [
                ['id' => 1, 'items' => ['added to', 'removed from'], 'value' => 'added to'],
                ['id' => 2, 'items' => ['the board', 'a list'], 'value' => 'the board'],
                ['id' => 3, 'items' => ['by me', 'by anyone'], 'value' => 'by me']
            ],

        ],
        [
            'type' => 'card_changes',
            'name' => 'Card Changes',
            'prefix' => 'when a card is',
            'suffix' => '',
            'description' => '"Updated" means the card details were changed.',
            'options' => [
                ['id' => 1, 'items' => ['updated', 'deleted'], 'value' => 'updated'],
                ['id' => 2, 'items' => ['by me', 'by anyone'], 'value' => 'by me']
            ],

        ]
    ];

    public const OPERATORS = [
        'move' => [
            'added_to',
            'copied_into',
            'moved_into',
            'created_in',
            'moved_out_of'
        ],
        'changes' => [
            'created',
            'updated',
            'renamed',
            'deleted'
        ],
        'archive' => [
            'archived',
            'unarchived'
        ],
        'where' => [
            'the_board',
            'a_list'
        ],
        'who' => [
            'by_me',
            'by_anyone'
        ],
        'lists' => [
            'list1',
            'list2',
            'list3'
        ]
    ];


}
