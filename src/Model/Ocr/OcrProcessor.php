<?php
declare(strict_types=1);

namespace App\Model\Ocr;

use App\Model\Entity\Document;
use Cake\Utility\Text;

class OcrProcessor
{
    public function __construct(
        protected Document $document,
        protected ?string $batchId = null,
        protected ?string $extractedText = null,
    )
    {}

    public function ocr(): void
    {
        if (!file_exists(TMP . $this->document->get('relative_file_path'))) {
            throw new \OutOfBoundsException('File not found');
        }

        $batchId = $this->document->get('id') . '-' . Text::uuid();        
        $this
            ->setBatchId($batchId)
            ->convertPdfToPng()
            ->extractTextFromPng()
            ->buildIndex();
    }

    protected function setBatchId(string $batchId): OcrProcessor
    {
        $this->batchId = $batchId;

        return $this;
    }

    protected function convertPdfToPng(): OcrProcessor
    {
        if (!file_exists(TMP . $this->batchId)) {
            mkdir(TMP . $this->batchId);
        }
        $command = sprintf(
            'pdftoppm -png %s %s',
            escapeshellarg(TMP . $this->document->get('relative_file_path')),
            escapeshellarg(TMP . $this->batchId . '/png')
        );
        exec($command, $output, $resultCode);
        \Cake\Log\Log::info(json_encode([$command => [$resultCode, $output]]));

        return $this;
    }

    protected function extractTextFromPng(): OcrProcessor
    {
        if (!file_exists(TMP . $this->batchId)) {
            throw new \OutOfBoundsException("$this->batchId batch not found");
        }
        $command = sprintf(
            'tesseract %s %s',
            escapeshellarg(TMP . $this->batchId . '/png-1.png'),
            escapeshellarg(TMP . $this->batchId . '/extracted')
        );
        exec($command, $output, $resultCode);
        $this->extractedText = file_get_contents(TMP . $this->batchId . '/extracted.txt');
        \Cake\Log\Log::info(json_encode([$command => [$resultCode, $output, $this->extractedText]]));

        return $this;
    }

    protected function buildIndex(): OcrProcessor
    {
        return $this;
    }
}
