<?php

namespace Paysera\CommissionTask\Service;

class CsvReader
{
    private string $filePath;
    private string $delimiter;
    private string $enclosure;
    private bool $hasHeader;
    private ?array $header = null;
    private bool $fileOpened = false;
    private $fileHandle = null;

    /**
     * @param string $filePath The path to the CSV file.
     * @param bool $hasHeader True if the first row is a header, false otherwise.
     * @param string $delimiter The CSV field delimiter (e.g., ',').
     * @param string $enclosure The CSV field enclosure (e.g., '"').
     */
    public function __construct(
        string $filePath,
        bool $hasHeader = true,
        string $delimiter = ',',
        string $enclosure = '"'
    ) {
        $this->filePath = $filePath;
        $this->hasHeader = $hasHeader;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    /**
     * Opens the CSV file and reads the header if applicable.
     *
     * @throws Exception If the file cannot be opened or is not readable.
     */
    private function openFile(): void
    {
        if ($this->fileOpened) {
            return; // Already open
        }

        if (!file_exists($this->filePath) || !is_readable($this->filePath)) {
            throw new Exception("CSV Error: File not found or not readable: " . $this->filePath);
        }

        $this->fileHandle = fopen($this->filePath, 'r');
        if ($this->fileHandle === false) {
            throw new Exception("CSV Error: Could not open file: " . $this->filePath);
        }

        $this->fileOpened = true;

        if ($this->hasHeader) {
            $this->header = fgetcsv($this->fileHandle, 0, $this->delimiter, $this->enclosure);
            if ($this->header === false) {
                // Handle case where file is empty or header read fails
                $this->header = [];
            }
        }
    }

    /**
     * Closes the CSV file handle.
     */
    private function closeFile(): void
    {
        if ($this->fileOpened && is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
            $this->fileOpened = false;
        }
    }

    /**
     * Reads all rows from the CSV file.
     *
     * @return array An array of arrays, where each inner array is a row.
     * @throws Exception If there's an issue opening the file.
     */
    public function readAll(): array
    {
        $this->openFile();
        $data = [];

        // Rewind to the beginning of the file after header (if any)
        if ($this->hasHeader) {
            rewind($this->fileHandle); // Go back to start
            fgetcsv($this->fileHandle, 0, $this->delimiter, $this->enclosure); // Skip header again
        } else {
            rewind($this->fileHandle); // Ensure we are at the start
        }


        while (($row = fgetcsv($this->fileHandle, 0, $this->delimiter, $this->enclosure)) !== false) {
            // Skip empty rows that fgetcsv might return as [null] or []
            if (empty(array_filter($row, fn($value) => !is_null($value) && $value !== ''))) {
                continue;
            }

            if ($this->hasHeader && $this->header !== null && count($this->header) === count($row)) {
                $data[] = array_combine($this->header, $row);
            } else {
                $data[] = $row;
            }
        }

        $this->closeFile();
        return $data;
    }

    // Ensure the file is closed if the object is destroyed unexpectedly
    public function __destruct()
    {
        $this->closeFile();
    }
}
