<?php

class PboHeaderEntry
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var int
     */
    private int $packingMethod;

    /**
     * @var int
     */
    private int $originalSize;

    /**
     * @var int
     */
    private int $reserved;

    /**
     * @var int
     */
    private int $timestamp;

    /**
     * @var int
     */
    private int $dataSize;

    /**
     * @var PboHeader
     */
    private PboHeader $parent;

    /**
     * PboHeaderEntry constructor.
     * @param string $filename
     * @param int $packingMethod
     * @param int $originalSize
     * @param int $reserved
     * @param int $timestamp
     * @param int $dataSize
     */
    public function __construct(
        string $filename,
        int $packingMethod,
        int $originalSize,
        int $reserved,
        int $timestamp,
        int $dataSize
    )
    {
        $this->filename = $filename;
        $this->packingMethod = $packingMethod;
        $this->originalSize = $originalSize;
        $this->reserved = $reserved;
        $this->timestamp = $timestamp;
        $this->dataSize = $dataSize;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return strlen($this->filename) + 1 + 20;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getDataSize(): int
    {
        return $this->dataSize;
    }

    /**
     * @return string|null
     */
    public function getDirectory(): ?string
    {
        return dirname($this->filename);
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return substr($this->parent->getParent()->getContent(), $this->getDataOffset(), $this->dataSize);
    }

    /**
     * @return int
     */
    private function getDataOffset(): int
    {
        $prevHeaderEntriesDataSize = 0;
        $prev = $this;
        while($prev = $prev->getPrevHeaderEntry()) {
            $prevHeaderEntriesDataSize += $prev->getDataSize();
        }
        return $this->parent->getLength() + 21 + $prevHeaderEntriesDataSize; // +21 because of ending header entry
    }

    /**
     * @return int
     */
    private function getIndex(): int
    {
        $index = 0;
        foreach ($this->parent->getHeaderEntries() as $he) {
            if($he === $this) return $index;
            $index++;
        }
    }

    /**
     * @return PboHeaderEntry|null
     */
    public function getNextHeaderEntry(): ?PboHeaderEntry
    {
        return $this->parent->getHeaderEntries()[$this->getIndex() + 1] ?? null;
    }

    /**
     * @return PboHeaderEntry|null
     */
    public function getPrevHeaderEntry(): ?PboHeaderEntry
    {
        return $this->parent->getHeaderEntries()[$this->getIndex() - 1] ?? null;
    }

    /**
     * @param PboHeader $pboHeader
     */
    public function setParent(PboHeader $pboHeader)
    {
        $this->parent = $pboHeader;
    }
}