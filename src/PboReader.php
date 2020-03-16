<?php

class PboReader
{
    /**
     * @param string $filepath
     * @return PboFile
     */
    public static function getFromFile(string $filepath): PboFile
    {
        return self::getFromString(file_get_contents($filepath));
    }

    /**
     * @param string $pboContent
     * @return PboFile
     */
    public static function getFromString(string $pboContent): PboFile
    {
        return new PboFile($pboContent);
    }

    /**
     * @param string $pboContent
     * @param int $offset
     * @return PboHeaderEntry|null
     */
    public static function getHeaderEntry(string $pboContent, int $offset = 0): ?PboHeaderEntry
    {
        $filename = unpack('Z*', $pboContent, $offset);
        if($filename[1] == "" || !isset($filename[1])) return null;
        list($packingMethod, $originalSize, $reserved, $timestamp, $dataSize) = array_values(unpack('L5', $pboContent, $offset + strlen($filename[1]) + 1));
        return new PboHeaderEntry($filename[1], $packingMethod, $originalSize, $reserved, $timestamp, $dataSize);
    }
}