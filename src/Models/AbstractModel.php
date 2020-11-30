<?php

declare(strict_types=1);

namespace DnsMadeEasy\Models;

use DnsMadeEasy\Exceptions\Client\ReadOnlyPropertyException;
use DnsMadeEasy\Interfaces\ClientInterface;
use DnsMadeEasy\Interfaces\Managers\AbstractManagerInterface;
use DnsMadeEasy\Interfaces\Models\AbstractModelInterface;
use JsonSerializable;

/**
 * An abstract class for resource models in the Dns Made Easy API.
 *
 * @package DnsMadeEasy\Models
 * @property-read int $id
 */
abstract class AbstractModel implements AbstractModelInterface, JsonSerializable
{
    /**
     * The manager for this object.
     * @var AbstractManagerInterface
     */
    protected AbstractManagerInterface $manager;

    /**
     * The Dns Made Easy API Client
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * The ID of the object.
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * A list of properties that have been modified since the object was last saved.
     * @var array
     */
    protected array $changed = [];

    /**
     * The properties of this object.
     * @var array
     */
    protected array $props = [];

    /**
     * The original properties from when the object was instantiated/last loaded from the API.
     * @var array
     */
    protected array $originalProps = [];

    /**
     * A list of properties that are editable on this model.
     * @var array
     */
    protected array $editable = [];

    /**
     * The original data retrieved from the API.
     * @var object|null
     */
    protected ?object $apiData = null;

    public function save(): void
    {
        if ($this->id && !$this->hasChanged()) {
            return;
        }
        $this->manager->save($this);
        $this->originalProps = $this->props;
        $this->changed = [];
    }

    public function delete(): void
    {
        if (!$this->id) {
            return;
        }
        $this->manager->delete($this);
    }

    /**
     * Creates the model and optionally populates it with data.
     * @param AbstractManagerInterface $manager
     * @param ClientInterface $client
     * @param object|null $data
     * @internal
     */
    public function __construct(AbstractManagerInterface $manager, ClientInterface $client, ?object $data = null)
    {
        $this->manager = $manager;
        $this->client = $client;
        $this->originalProps = $this->props;
        if ($data) {
            $this->populateFromApi($data);
        }
    }

    /**
     * Returns a string representation of the model's class and ID.
     * @return string
     * @throws \ReflectionException
     * @internal
     */
    public function __toString()
    {
        $rClass = new \ReflectionClass($this);
        $modelName = $rClass->getShortName();
        if ($this->id === null) {
            return "{$modelName}:#";
        }
        return "{$modelName}:{$this->id}";
    }

    public function hasChanged(): bool
    {
        return (bool)$this->changed;
    }

    /**
     * @param object $data
     * @internal
     */
    public function populateFromApi(object $data): void
    {
        $this->apiData = $data;
        $this->id = $data->id;
        $this->parseApiData($data);
        $this->originalProps = $this->props;
        $this->changed = [];
    }

    /**
     * Parses the API data and assigns it to properties on this object.
     * @param object $data
     */
    protected function parseApiData(object $data): void
    {
        foreach ($data as $prop => $value) {
            try {
                $this->{$prop} = $value;
            } catch (ReadOnlyPropertyException $ex) {
                $this->props[$prop] = $value;
            }
        }
    }

    /**
     * Generate a representation of the object for sending to the API.
     * @return object
     * @internal
     */
    public function transformForApi(): object
    {
        $obj = $this->jsonSerialize();
        if ($this->id === null) {
            unset($obj->{$this->id});
        }
        // These don't exist
        foreach ($obj as $key => $value) {
            if ($value === null || (is_array($value) && !$value)) {
                unset($obj->$key);
            }
        }
        return $obj;
    }

    /**
     * Returns a JSON serializable representation of the resource.
     * @return mixed|object
     * @internal
     */
    public function jsonSerialize()
    {
        $result = (object)[
            'id' => $this->id,
        ];
        foreach ($this->props as $name => $value) {
            if ($value instanceof \DateTime) {
                $value = $value->format('c');
            }
            $result->{$name} = $value;
        }
        return $result;
    }

    public function refresh(): void
    {
        $this->manager->refresh($this);
    }

    /**
     * Returns the ID of the object. Since ID is a protected property, this is required for fetching it.
     * @return int|null
     */
    protected function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Magic method to fetch properties for the object. If a get{Name} method exists, it will be called  first,
     * otherwise it will try and fetch it from the properties array.
     * @param $name
     * @return mixed
     * @internal
     */
    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        } elseif (array_key_exists($name, $this->props)) {
            return $this->props[$name];
        }
    }

    /**
     * Magic method for setting properties for the object. If a method called set{Name} exists, then it will be called,
     * otherwise if the property is in the props array and is editable, it will be updated.
     *
     * Changes are tracked to allow us to see any changes.
     *
     * @param $name
     * @param $value
     * @throws ReadOnlyPropertyException
     * @internal
     */
    public function __set($name, $value)
    {
        $methodName = 'set' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            $this->{$methodName}($value);
        } elseif (in_array($name, $this->editable)) {
            $this->props[$name] = $value;
            $this->changed[] = $name;
        } elseif (array_key_exists($name, $this->props)) {
            throw new ReadOnlyPropertyException("Unable to set {$name}");
        }
    }
}