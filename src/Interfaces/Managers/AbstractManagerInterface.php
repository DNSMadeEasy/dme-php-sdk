<?php

declare(strict_types=1);

namespace DnsMadeEasy\Interfaces\Managers;

use DnsMadeEasy\Exceptions\Client\Http\HttpException;
use DnsMadeEasy\Interfaces\Models\AbstractModelInterface;

/**
 * Defines the interface of a Manager for a particular resource in the DNS Made Easy API.
 *
 * The manager is the way that resources are fetched, queried and updated in the SDK. There should be one for every
 * resource that can be manipulated.
 *
 * @package DnsMadeEasy\Interfaces
 */
interface AbstractManagerInterface
{

    /**
     * Updates the API with changes made to the specified object. If the object is new, it will be created.
     * @param AbstractModelInterface $object
     * @throws HttpException
     * @internal
     */
    public function save(AbstractModelInterface $object): void;

    /**
     * Uses the API to delete the specified object. If the object is new, then no action is taken on the API.
     * @param AbstractModelInterface $object
     * @throws HttpException
     * @internal
     */
    public function delete(AbstractModelInterface $object): void;
}