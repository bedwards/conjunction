<?php

namespace Conjunction\Entity;

/**
 * Value Object representing the verdict of a choice
 * SOLID Principles:
 * - SRP: Only represents verdict data and computes color
 * - Immutability: Value object pattern - no setters
 */
enum VerdictType: string
{
    case CORRECT = 'correct';
    case OKAY = 'okay';
    case WRONG = 'wrong';
}
