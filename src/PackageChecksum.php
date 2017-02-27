<?php

namespace Inachis\Component\PackageIntegrityChecker;

/**
 * Class FileHash
 * @package Inachis\Component\PackageIntegrityChecker
 */
class PackageChecksum
{
    /**
     * @var array
     */
    protected $hashCollection = [];
    /**
     * @var array
     */
    protected $failures = [];
    /**
     * @var int
     */
    protected $numFilesChecked = 0;
    /**
     * @var string
     */
    protected $hashAlgorithm = 'sha256';
    /**
     * @var
     */
    protected $source;
    /**
     * FileHash constructor
     */
    public function __construct()
    {

    }
    /**
     * @param $filename
     * @return string
     */
    public function createHashForFile($filename)
    {
        return file_exists($filename) && hash_file($this->hashAlgorithm, $filename);
    }

    /**
     * @param string $path
     * @throws \Exception
     */
    public function createHashesForPath($path = '.')
    {
        if (!is_dir($path)) {
            throw new \Exception(sprintf('\'%s\' does not exist', $path));
        }
        if (empty($this->source)) {
            $this->source = $path;
        }
        $filenames = scandir($path);
        foreach ($filenames as $filename) {
            if (in_array($filename, array('.', '..', '.git'))) {
                continue;
            }
            if (is_dir($path . '/' . $filename)) {
                $this->createHashesForPath($path . '/' . $filename);
                continue;
            }
            $this->numFilesChecked++;
            $this->hashCollection[] = array(
                'filename' => str_replace($this->source, '', $path) . '/' . $filename,
                'checksum' => $this->createHashForFile($path . '/' . $filename)
            );
        }
    }

    /**
     * @param string $filename
     */
    public function writeToFile($filename = 'checksum')
    {
        file_put_contents($filename . '.json', json_encode($this->hashCollection, JSON_PRETTY_PRINT));
    }

    public function verifyHash($hash1, $hash2)
    {
        return $hash1 === $hash2;
    }

    /**
     * @param string $path
     * @param string $checksums
     * @return bool
     */
    public function verifyHashesForPath($path = '.', $checksums = 'checksum')
    {
        $this->hashCollection = json_decode(file_get_contents($checksums . '.json'));
        foreach ($this->hashCollection as $hash) {
            $this->numFilesChecked++;
            if (!$this->verifyHash($hash->checksum, $this->createHashForFile($path . $hash->filename))) {
                $this->failures[] = $hash->filename;
            }
        }
        return empty($this->failures);
    }

    /**
     * @return int
     */
    public function getNumFailures()
    {
        return sizeof($this->failures);
    }

    /**
     * @return int
     */
    public function getNumFilesChecked()
    {
        return $this->numFilesChecked;
    }

    public function getFailures()
    {
        return $this->failures;
    }
}
