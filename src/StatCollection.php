<?php

namespace Dch\Covid;

class StatCollection
{
    private $entries = [];
    private $firstCaseEntryIndex = null;
    private $lastCaseEntryIndex = null;

    public function __construct(array $data)
    {
        foreach ($data as $dailyStats) {
            $this->entries[] = new DailyStat($dailyStats);
        }
    }

    public function getFirstCaseEntry(): DailyStat
    {
        return $this->entries[$this->getFirstCaseEntryIndex()];
    }

    public function getByOffset(int $offset): ?DailyStat
    {
        $index = $offset + $this->getFirstCaseEntryIndex();
        return $this->entries[$index] ?? null;
    }

    public function getLastCaseEntryIndex(): int
    {
        return count($this->entries) - 1;
    }

    private function getFirstCaseEntryIndex(): ?int
    {
        if ($this->firstCaseEntryIndex !== null) {
            return $this->firstCaseEntryIndex;
        }

        foreach ($this->entries as $index => $entry) {
            if ($entry->getNewCases() > 0) {
                $this->firstCaseEntryIndex = $index;
                return $index;
            }
        }

        return null;
    }
}
