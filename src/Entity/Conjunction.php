<?php

namespace Conjunction\Entity;

/**
 * Enum representing the three conjunction types
 * SOLID: Single Responsibility - Only represents conjunction types
 */
enum Conjunction: string
{
    case AND = 'and';
    case BUT = 'but';
    case SO = 'so';
}
