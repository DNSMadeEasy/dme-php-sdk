<?php
declare(strict_types=1);

namespace DnsMadeEasy\Interfaces\Models;

/**
 * Represents a Record
 *
 * @package DnsMadeEasy\Interfaces
 *
 * @property-read int $templateId
 * @property-read TemplateInterface $template
 */
interface TemplateRecordInterface extends RecordInterface
{
    /**
     * Set the template for the record.
     * @param TemplateInterface $template
     * @return $this
     */
    public function setTemplate(TemplateInterface $template): self;
}