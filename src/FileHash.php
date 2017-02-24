<?php

namespace Inachis\Component\FileIntegrityCheck;

/**
 * Class FileHash
 * @package Inachis\Component\FileIntegrityCheck
 */
class FileHash
{
    /**
     * @var array
     */
	protected $hashCollection = [];
    /**
     * @var string
     */
	protected $hashAlgorithm = 'sha256';
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
		return hash_file($this->hashAlgorithm, $filename);
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
        $filenames = scandir($path);
        foreach ($filenames as $filename) {
            if (in_array($filename, array('.', '..', '.git'))) {
                continue;
            }
            if (is_dir($filename)) {
                $this->createHashesForPath($path . '/' . $filename);
                continue;
            }
            $this->hashCollection[] = array(
                'file' => $path . '/' . $filename,
                'hash' => $this->createHashForFile($filename)
            );
        }
        $this->writeToFile();
    }

    /**
     * 
     */
    private function writeToFile()
    {
        file_put_contents('checksum.json', json_encode($this->hashCollection, JSON_PRETTY_PRINT));
    }
}


$hash = new FileHash();
$hash->createHashesForPath('.');