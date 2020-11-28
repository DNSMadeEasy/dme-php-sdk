<?php
declare(strict_types=1);

namespace DnsMadeEasy\Interfaces\Models;

use DnsMadeEasy\Interfaces\Models\Common\CommonManagedDomainInterface;
use DnsMadeEasy\Models\TransferAcl;

/**
 * Represents a Managed Domain resource
 *
 * @package DnsMadeEasy
 *
 * @property bool $gtdEnabled
 * @property-read int $soaID
 * @property VanityNameServerInterface $vanity
 * @property int $vanityId
 * @property TransferAcl $transferAcl
 * @property int $transferAclId
 * @property FolderInterface $folder
 * @property int $folderId
 * @property TemplateInterface $template
 * @property int $templateId
 *
 */
interface ManagedDomainInterface extends CommonManagedDomainInterface
{
}