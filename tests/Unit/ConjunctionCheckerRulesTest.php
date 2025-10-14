<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Service\ConjunctionChecker;
use Conjunction\Service\FeedbackGenerator;
use Conjunction\Entity\Conjunction;
use Conjunction\Entity\GameSession;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;
use Conjunction\Repository\GameSessionRepositoryInterface;
use Conjunction\Strategy\Rule;
use Conjunction\Strategy\RuleInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests that verify ConjunctionChecker actually uses its injected rules
 * 
 * NOTE: Currently the $rules property is unused! 
 * The findRule() method needs to be implemented and called.
 */
class ConjunctionCheckerRulesTest extends TestCase
{
    private ConjunctionChecker $checker;
    private FeedbackGenerator|MockObject $mockFeedbackGenerator;
    private GameSessionRepositoryInterface|MockObject $mockSessionRepo;
    private array $rules;

    #[\Override]
    protected function setUp(): void
    {
        $this->mockFeedbackGenerator = $this->createMock(FeedbackGenerator::class);
        $this->mockSessionRepo = $this->createMock(GameSessionRepositoryInterface::class);

        $this->rules = [
            new Rule(Conjunction::AND),
            new Rule(Conjunction::BUT),
            new Rule(Conjunction::SO),
        ];

        $this->checker = new ConjunctionChecker(
            $this->mockFeedbackGenerator,
            $this->mockSessionRepo,
            $this->rules
        );
    }

    /**
     * Test that findRule() can find the AND rule
     * @group work
     */
    public function testFindRuleReturnsAndRule(): void
    {
        // Use reflection to test the private findRule method
        $reflection = new \ReflectionClass($this->checker);
        $method = $reflection->getMethod('findRule');

        $rule = $method->invoke($this->checker, Conjunction::AND);

        $this->assertInstanceOf(RuleInterface::class, $rule);
        $this->assertEquals(Conjunction::AND, $rule->getConjunction());
    }

    /**
     * Test that findRule() can find the BUT rule
     * @group work
     */
    public function testFindRuleReturnsButRule(): void
    {
        $reflection = new \ReflectionClass($this->checker);
        $method = $reflection->getMethod('findRule');

        $rule = $method->invoke($this->checker, Conjunction::BUT);

        $this->assertInstanceOf(RuleInterface::class, $rule);
        $this->assertEquals(Conjunction::BUT, $rule->getConjunction());
    }

    /**
     * Test that findRule() can find the SO rule
     * @group work
     */
    public function testFindRuleReturnsSoRule(): void
    {
        $reflection = new \ReflectionClass($this->checker);
        $method = $reflection->getMethod('findRule');

        $rule = $method->invoke($this->checker, Conjunction::SO);

        $this->assertInstanceOf(RuleInterface::class, $rule);
        $this->assertEquals(Conjunction::SO, $rule->getConjunction());
    }

    /**
     * Test that findRule() returns null for non-existent rule
     * @group work
     */
    public function testFindRuleReturnsNullForMissingRule(): void
    {
        // Create checker with empty rules array
        $checker = new ConjunctionChecker(
            $this->mockFeedbackGenerator,
            $this->mockSessionRepo,
            [] // Empty rules
        );

        $reflection = new \ReflectionClass($checker);
        $method = $reflection->getMethod('findRule');

        $rule = $method->invoke($checker, Conjunction::AND);

        $this->assertNull($rule);
    }

    /**
     * Test that checker works with custom rule implementations
     * @group work
     */
    public function testCheckerWorksWithCustomRules(): void
    {
        // Create a mock rule
        $mockRule = $this->createMock(RuleInterface::class);
        $mockRule->method('getConjunction')->willReturn(Conjunction::AND);
        $mockRule->method('applies')->willReturn(true);
        $mockRule->method('getExplanation')->willReturn('Custom explanation');

        $checker = new ConjunctionChecker(
            $this->mockFeedbackGenerator,
            $this->mockSessionRepo,
            [$mockRule]
        );

        $reflection = new \ReflectionClass($checker);
        $method = $reflection->getMethod('findRule');

        $foundRule = $method->invoke($checker, Conjunction::AND);

        $this->assertSame($mockRule, $foundRule);
    }

    /**
     * Test that rules array is used (not the parameter)
     * @group work
     */
    public function testFindRuleUsesInjectedRulesArray(): void
    {
        $customAndRule = new Rule(Conjunction::AND);
        $customButRule = new Rule(Conjunction::BUT);

        $checker = new ConjunctionChecker(
            $this->mockFeedbackGenerator,
            $this->mockSessionRepo,
            [$customAndRule, $customButRule]
        );

        $reflection = new \ReflectionClass($checker);
        $method = $reflection->getMethod('findRule');

        // Should find AND
        $foundAnd = $method->invoke($checker, Conjunction::AND);
        $this->assertNotNull($foundAnd);
        $this->assertEquals(Conjunction::AND, $foundAnd->getConjunction());

        // Should find BUT
        $foundBut = $method->invoke($checker, Conjunction::BUT);
        $this->assertNotNull($foundBut);
        $this->assertEquals(Conjunction::BUT, $foundBut->getConjunction());

        // Should NOT find SO (not in rules array)
        $foundSo = $method->invoke($checker, Conjunction::SO);
        $this->assertNull($foundSo);
    }

    /**
     * Test that findRule handles rules in any order
     * @group work
     */
    public function testFindRuleWorksRegardlessOfRuleOrder(): void
    {
        // Create rules in reverse order
        $checker = new ConjunctionChecker(
            $this->mockFeedbackGenerator,
            $this->mockSessionRepo,
            [
                new Rule(Conjunction::SO),
                new Rule(Conjunction::BUT),
                new Rule(Conjunction::AND),
            ]
        );

        $reflection = new \ReflectionClass($checker);
        $method = $reflection->getMethod('findRule');

        // All should still be findable
        $this->assertNotNull($method->invoke($checker, Conjunction::AND));
        $this->assertNotNull($method->invoke($checker, Conjunction::BUT));
        $this->assertNotNull($method->invoke($checker, Conjunction::SO));
    }
}
