<?php
declare(strict_types=1);

namespace App\Job;

use Cake\Queue\Job\JobInterface;
use Cake\Queue\Job\Message;
use Interop\Queue\Processor;

/**
 * Ocr job
 */
class OcrJob implements JobInterface
{
    use \Cake\ORM\Locator\LocatorAwareTrait;

    /**
     * The maximum number of times the job may be attempted.
     * 
     * @var int|null
     */
    public static $maxAttempts = 3;

    /**
     * Executes logic for OcrJob
     *
     * @param \Cake\Queue\Job\Message $message job message
     * @return string|null
     */
    public function execute(Message $message): ?string
    {
        try {
            $this->fetchTable('Documents')->ocr($message->getArgument('documentId'));
        } catch (\Exception $ex) {
            \Cake\Log\Log::error($ex->getMessage());
            
            return Processor::REJECT;
        }

        return Processor::ACK;
    }
}
