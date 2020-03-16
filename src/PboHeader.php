<?php

class PboHeader
{
    /**
     * @var array
     */
    private array $headerEntries = [];

    /**
     * @var PboFile
     */
    private PboFile $parent;

    /**
     * @param PboHeaderEntry $headerEntry
     */
    public function addHeaderEntry(PboHeaderEntry $headerEntry)
    {
        $headerEntry->setParent($this);
        $this->headerEntries[] = $headerEntry;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        $length = 0;
        foreach ($this->getHeaderEntries() as $he) $length += $he->getLength();
        return $length;
    }

    /**
     * @return array
     */
    public function getHeaderEntries(): array
    {
        return $this->headerEntries;
    }

    /**
     * @return PboFile
     */
    public function getParent(): PboFile
    {
        return $this->parent;
    }

    /**
     * @param PboFile $file
     */
    public function setParent(PboFile $file)
    {
        $this->parent = $file;
    }
}