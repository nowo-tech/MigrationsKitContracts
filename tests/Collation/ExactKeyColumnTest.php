<?php

declare(strict_types=1);

namespace Nowo\MigrationsKitContracts\Tests\Collation;

use InvalidArgumentException;
use Nowo\MigrationsKitContracts\Collation\ExactKeyColumn;
use Nowo\MigrationsKitContracts\Collation\ExactKeyColumns;
use Nowo\MigrationsKitContracts\Collation\ExactKeyColumnsInterface;
use Nowo\MigrationsKitContracts\Collation\ExactKeyStatus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ExactKeyColumn::class)]
#[CoversClass(ExactKeyColumns::class)]
#[CoversClass(ExactKeyStatus::class)]
final class ExactKeyColumnTest extends TestCase
{
    public function testKeyIsLowerCasedTableAndColumn(): void
    {
        $column = new ExactKeyColumn('Wf_Instance', 'Owner_Id', 'nowo-tech/workflow-bundle', 'host user id');

        self::assertSame('wf_instance.owner_id', $column->key());
        self::assertSame('Wf_Instance.Owner_Id', $column->describe());
        self::assertNull($column->condition);
        self::assertTrue($column->active);
    }

    public function testAConditionalColumnCarriesTheSettingThatGatesIt(): void
    {
        $column = new ExactKeyColumn('chat_user_reference', 'user_key', 'chatkit', 'user key', 'nowo_chat_kit.user_reference: string', false);

        self::assertSame('nowo_chat_kit.user_reference: string', $column->condition);
        self::assertFalse($column->active);
    }

    /**
     * @return list<array{string, string, string}>
     */
    public static function emptyArguments(): array
    {
        return [['', 'c', 'o'], ['t', '', 'o'], ['t', 'c', ''], ['  ', 'c', 'o']];
    }

    #[DataProvider('emptyArguments')]
    public function testTableColumnAndOwnerCannotBeEmpty(string $table, string $column, string $owner): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ExactKeyColumn($table, $column, $owner);
    }

    public function testExactKeyColumnsIsAFixedList(): void
    {
        $first  = new ExactKeyColumn('a', 'b', 'o');
        $second = new ExactKeyColumn('c', 'd', 'o');
        $list   = new ExactKeyColumns($first, $second);

        self::assertInstanceOf(ExactKeyColumnsInterface::class, $list);
        self::assertSame([$first, $second], $list->exactKeyColumns());
        self::assertSame([], (new ExactKeyColumns())->exactKeyColumns());
    }

    public function testOnlyFailAndMissingAreFailures(): void
    {
        self::assertTrue(ExactKeyStatus::FAIL->isFailure());
        self::assertTrue(ExactKeyStatus::MISSING->isFailure());
        foreach ([ExactKeyStatus::OK, ExactKeyStatus::FALLBACK, ExactKeyStatus::SKIPPED, ExactKeyStatus::IGNORED] as $status) {
            self::assertFalse($status->isFailure(), $status->value . ' must not fail the gate');
        }
    }
}
