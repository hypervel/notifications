<?php

declare(strict_types=1);

namespace LaravelHyperf\Notifications\Slack\BlockKit\Blocks;

use InvalidArgumentException;
use LaravelHyperf\Notifications\Contracts\Slack\BlockContract;

class DividerBlock implements BlockContract
{
    /**
     * A string acting as a unique identifier for a block.
     *
     * If not specified, a block_id will be generated.
     *
     * You can use this block_id when you receive an interaction payload to identify the source of the action.
     */
    protected ?string $blockId = null;

    /**
     * Set the block identifier.
     */
    public function id(string $id): static
    {
        $this->blockId = $id;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if ($this->blockId && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
            'type' => 'divider',
        ], $optionalFields);
    }
}
