<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Job\OcrJob;
use App\Model\Entity\Document;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use Cake\Queue\QueueManager;

/**
 * Documents Model
 *
 * @method \App\Model\Entity\Document newEmptyEntity()
 * @method \App\Model\Entity\Document newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Document[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Document get($primaryKey, $options = [])
 * @method \App\Model\Entity\Document findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Document patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Document[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Document|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Document saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Document[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Document[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Document[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Document[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocumentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('documents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('original_name')
            ->allowEmptyString('original_name');

        $validator
            ->scalar('relative_file_path')
            ->allowEmptyFile('relative_file_path');

        $validator
            ->scalar('status')
            ->maxLength('status', 255)
            ->allowEmptyString('status');

        return $validator;
    }

    public function beforeSave(EventInterface $event, Document $document, \ArrayObject $options)
    {
        $document->relative_file_path = $this->handleUpload($document);        
    }

    public function afterSave(EventInterface $event, Document $document, \ArrayObject $options)
    {
        if ($document->get('relative_file_path')) {
            $this->pushJob($document);
        }
    }

    protected function handleUpload(Document $document): ?string
    {
        /**
         * @var \Psr\Http\Message\UploadedFileInterface $upload
         */
        if (!$upload = $document->get('file')) {
            return null;
        }        
        if ($error = $upload->getError()) {
            $document->setError('file', $error);
            return null;
        }
        // move the file to files
        $targetName = 'upload-' . Text::uuid();
        $uploadFullPath = TMP . $targetName;
        $upload->moveTo($uploadFullPath);

        return $targetName;
    }

    protected function pushJob(Document $document): void
    {
        QueueManager::push(OcrJob::class, [
            'documentId' => $document->get('id'),
        ]);
    }


    public function ocr(): void
    {
        // does something
    }
}
