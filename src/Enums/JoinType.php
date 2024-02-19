<?php

namespace FlamePHPDev\FlameQuery\Enums;

enum JoinType: string {
    case INNER = 'inner';
    case LEFT = 'left';
    case RIGHT = 'right';
    case CROSS = 'cross';
}