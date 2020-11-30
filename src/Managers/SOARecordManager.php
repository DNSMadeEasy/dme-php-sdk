<?php
declare(strict_types=1);

namespace DnsMadeEasy\Managers;

use DnsMadeEasy\Interfaces\Managers\SOARecordManagerInterface;
use DnsMadeEasy\Interfaces\Models\SOARecordInterface;
use DnsMadeEasy\Interfaces\Traits\ListableManagerInterface;
use DnsMadeEasy\Traits\ListableManager;

/**
 * Manager for SOA Record resources.
 * @package DnsMadeEasy\Managers
 */
class SOARecordManager extends AbstractManager implements SOARecordManagerInterface, ListableManagerInterface
{
    use ListableManager;

    /**
     * Base URI for SOA records.
     * @var string
     */
    protected string $baseUri = '/dns/soa';

    public function create(): SOARecordInterface
    {
        return $this->createObject();
    }

    public function get(int $id): SOARecordInterface
    {
        return $this->getObject($id);
    }
}