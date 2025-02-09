<?php

declare(strict_types=1);

namespace DnsMadeEasy\Models;

use DnsMadeEasy\Interfaces\Models\FolderInterface;
use DnsMadeEasy\Models\Common\CommonFolder;

/**
 * Represents a Folder resource.
 * @package DnsMadeEasy\Models
 *
 * @property string $name
 * @property-read int[] $domains
 * @property-read int[] $secondaries
 * @property-read object $folderPermissions
 * @property bool $defaultFolder
 */
class Folder extends CommonFolder implements FolderInterface
{
    protected array $props = [
        'name' => null,
        'domains' => [],
        'secondaries' => [],
        'folderPermissions' => [],
        'defaultFolder' => null,
    ];

    protected array $editable = [
        'name',
        'defaultFolder',
    ];
}