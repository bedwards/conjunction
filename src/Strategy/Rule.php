<?php

namespace Conjunction\Strategy;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;

class Rule implements RuleInterface
{
    public function __construct(
        private Conjunction $conjunction
    ) {
    }

    #[\Override]
    public function applies(Conjunction $choice): bool
    {
        return $this->conjunction == $choice;
    }

    #[\Override]
    public function getExplanation(): string
    {
        return match($this->conjunction) {
            Conjunction::AND => 'And is for adding things together or showing what happened next!',
            Conjunction::BUT => 'But shows when something is different or surprising!',
            Conjunction::SO => 'So shows what happened because of something!',
        };
    }

    #[\Override]
    public function getConjunction(): Conjunction
    {
        return $this->conjunction;
    }
}
