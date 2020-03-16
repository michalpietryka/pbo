<?php

class PboFile
{
    /**
     * @var string
     */
    private $fileContent;

    /**
     * @var PboHeader
     */
    private PboHeader $header;

    /**
     * PboFile constructor.
     * @param string $fileContent
     */
    public function __construct(string $fileContent)
    {
        $this->fileContent = $fileContent;
        $this->parseHeader();
    }

    /**
     * @return PboHeader
     */
    public function getHeader(): PboHeader
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->fileContent;
    }

    /**
     * @param string $path
     */
    public function extract(string $path)
    {
        $this->createDirectoryStructure($path);
        $this->createFiles($path);
    }

    /**
     * @param $path
     */
    private function createFiles($path)
    {
        foreach($this->header->getHeaderEntries() as $headerEntry) {
            file_put_contents($path.DIRECTORY_SEPARATOR.$headerEntry->getFilename(), $headerEntry->getData());
        }
    }

    /**
     * @param string $path
     */
    private function createDirectoryStructure(string $path)
    {
        foreach($this->header->getHeaderEntries() as $headerEntry) {
            $dirname = $headerEntry->getDirectory();
            if (!file_exists($path.DIRECTORY_SEPARATOR.$dirname)) {
                mkdir($path.DIRECTORY_SEPARATOR.$dirname, 0777, true);
            }
        }
    }

    private function parseHeader()
    {
        $this->header = new PboHeader();
        $this->header->setParent($this);
        while($headerEntry = PboReader::getHeaderEntry($this->fileContent, $this->header->getLength())) {
            $this->header->addHeaderEntry($headerEntry);
        }
    }
}