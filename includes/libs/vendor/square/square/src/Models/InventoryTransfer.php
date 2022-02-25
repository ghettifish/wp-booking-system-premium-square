<?php

declare(strict_types=1);

namespace Square\Models;

use stdClass;

/**
 * Represents the transfer of a quantity of product inventory at a
 * particular time from one location to another.
 */
class InventoryTransfer implements \JsonSerializable
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $referenceId;

    /**
     * @var string|null
     */
    private $state;

    /**
     * @var string|null
     */
    private $fromLocationId;

    /**
     * @var string|null
     */
    private $toLocationId;

    /**
     * @var string|null
     */
    private $catalogObjectId;

    /**
     * @var string|null
     */
    private $catalogObjectType;

    /**
     * @var string|null
     */
    private $quantity;

    /**
     * @var string|null
     */
    private $occurredAt;

    /**
     * @var string|null
     */
    private $createdAt;

    /**
     * @var SourceApplication|null
     */
    private $source;

    /**
     * @var string|null
     */
    private $employeeId;

    /**
     * @var string|null
     */
    private $teamMemberId;

    /**
     * Returns Id.
     *
     * A unique ID generated by Square for the
     * `InventoryTransfer`.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets Id.
     *
     * A unique ID generated by Square for the
     * `InventoryTransfer`.
     *
     * @maps id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * Returns Reference Id.
     *
     * An optional ID provided by the application to tie the
     * `InventoryTransfer` to an external system.
     */
    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    /**
     * Sets Reference Id.
     *
     * An optional ID provided by the application to tie the
     * `InventoryTransfer` to an external system.
     *
     * @maps reference_id
     */
    public function setReferenceId(?string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    /**
     * Returns State.
     *
     * Indicates the state of a tracked item quantity in the lifecycle of goods.
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Sets State.
     *
     * Indicates the state of a tracked item quantity in the lifecycle of goods.
     *
     * @maps state
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    /**
     * Returns From Location Id.
     *
     * The Square-generated ID of the [Location]($m/Location) where the related
     * quantity of items was tracked before the transfer.
     */
    public function getFromLocationId(): ?string
    {
        return $this->fromLocationId;
    }

    /**
     * Sets From Location Id.
     *
     * The Square-generated ID of the [Location]($m/Location) where the related
     * quantity of items was tracked before the transfer.
     *
     * @maps from_location_id
     */
    public function setFromLocationId(?string $fromLocationId): void
    {
        $this->fromLocationId = $fromLocationId;
    }

    /**
     * Returns To Location Id.
     *
     * The Square-generated ID of the [Location]($m/Location) where the related
     * quantity of items was tracked after the transfer.
     */
    public function getToLocationId(): ?string
    {
        return $this->toLocationId;
    }

    /**
     * Sets To Location Id.
     *
     * The Square-generated ID of the [Location]($m/Location) where the related
     * quantity of items was tracked after the transfer.
     *
     * @maps to_location_id
     */
    public function setToLocationId(?string $toLocationId): void
    {
        $this->toLocationId = $toLocationId;
    }

    /**
     * Returns Catalog Object Id.
     *
     * The Square-generated ID of the
     * [CatalogObject]($m/CatalogObject) being tracked.
     */
    public function getCatalogObjectId(): ?string
    {
        return $this->catalogObjectId;
    }

    /**
     * Sets Catalog Object Id.
     *
     * The Square-generated ID of the
     * [CatalogObject]($m/CatalogObject) being tracked.
     *
     * @maps catalog_object_id
     */
    public function setCatalogObjectId(?string $catalogObjectId): void
    {
        $this->catalogObjectId = $catalogObjectId;
    }

    /**
     * Returns Catalog Object Type.
     *
     * The [type]($m/CatalogObjectType) of the
     * [CatalogObject]($m/CatalogObject) being tracked.Tracking is only
     * supported for the `ITEM_VARIATION` type.
     */
    public function getCatalogObjectType(): ?string
    {
        return $this->catalogObjectType;
    }

    /**
     * Sets Catalog Object Type.
     *
     * The [type]($m/CatalogObjectType) of the
     * [CatalogObject]($m/CatalogObject) being tracked.Tracking is only
     * supported for the `ITEM_VARIATION` type.
     *
     * @maps catalog_object_type
     */
    public function setCatalogObjectType(?string $catalogObjectType): void
    {
        $this->catalogObjectType = $catalogObjectType;
    }

    /**
     * Returns Quantity.
     *
     * The number of items affected by the transfer as a decimal string.
     * Can support up to 5 digits after the decimal point.
     */
    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    /**
     * Sets Quantity.
     *
     * The number of items affected by the transfer as a decimal string.
     * Can support up to 5 digits after the decimal point.
     *
     * @maps quantity
     */
    public function setQuantity(?string $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * Returns Occurred At.
     *
     * A client-generated RFC 3339-formatted timestamp that indicates when
     * the transfer took place. For write actions, the `occurred_at` timestamp
     * cannot be older than 24 hours or in the future relative to the time of the
     * request.
     */
    public function getOccurredAt(): ?string
    {
        return $this->occurredAt;
    }

    /**
     * Sets Occurred At.
     *
     * A client-generated RFC 3339-formatted timestamp that indicates when
     * the transfer took place. For write actions, the `occurred_at` timestamp
     * cannot be older than 24 hours or in the future relative to the time of the
     * request.
     *
     * @maps occurred_at
     */
    public function setOccurredAt(?string $occurredAt): void
    {
        $this->occurredAt = $occurredAt;
    }

    /**
     * Returns Created At.
     *
     * An RFC 3339-formatted timestamp that indicates when Square
     * received the transfer request.
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * Sets Created At.
     *
     * An RFC 3339-formatted timestamp that indicates when Square
     * received the transfer request.
     *
     * @maps created_at
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns Source.
     *
     * Provides information about the application used to generate a change.
     */
    public function getSource(): ?SourceApplication
    {
        return $this->source;
    }

    /**
     * Sets Source.
     *
     * Provides information about the application used to generate a change.
     *
     * @maps source
     */
    public function setSource(?SourceApplication $source): void
    {
        $this->source = $source;
    }

    /**
     * Returns Employee Id.
     *
     * The Square-generated ID of the [Employee]($m/Employee) responsible for the
     * inventory transfer.
     */
    public function getEmployeeId(): ?string
    {
        return $this->employeeId;
    }

    /**
     * Sets Employee Id.
     *
     * The Square-generated ID of the [Employee]($m/Employee) responsible for the
     * inventory transfer.
     *
     * @maps employee_id
     */
    public function setEmployeeId(?string $employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    /**
     * Returns Team Member Id.
     *
     * The Square-generated ID of the [Team Member]($m/TeamMember) responsible for the
     * inventory transfer.
     */
    public function getTeamMemberId(): ?string
    {
        return $this->teamMemberId;
    }

    /**
     * Sets Team Member Id.
     *
     * The Square-generated ID of the [Team Member]($m/TeamMember) responsible for the
     * inventory transfer.
     *
     * @maps team_member_id
     */
    public function setTeamMemberId(?string $teamMemberId): void
    {
        $this->teamMemberId = $teamMemberId;
    }

    /**
     * Encode this object to JSON
     *
     * @param bool $asArrayWhenEmpty Whether to serialize this model as an array whenever no fields
     *        are set. (default: false)
     *
     * @return mixed
     */
    public function jsonSerialize(bool $asArrayWhenEmpty = false)
    {
        $json = [];
        if (isset($this->id)) {
            $json['id']                  = $this->id;
        }
        if (isset($this->referenceId)) {
            $json['reference_id']        = $this->referenceId;
        }
        if (isset($this->state)) {
            $json['state']               = $this->state;
        }
        if (isset($this->fromLocationId)) {
            $json['from_location_id']    = $this->fromLocationId;
        }
        if (isset($this->toLocationId)) {
            $json['to_location_id']      = $this->toLocationId;
        }
        if (isset($this->catalogObjectId)) {
            $json['catalog_object_id']   = $this->catalogObjectId;
        }
        if (isset($this->catalogObjectType)) {
            $json['catalog_object_type'] = $this->catalogObjectType;
        }
        if (isset($this->quantity)) {
            $json['quantity']            = $this->quantity;
        }
        if (isset($this->occurredAt)) {
            $json['occurred_at']         = $this->occurredAt;
        }
        if (isset($this->createdAt)) {
            $json['created_at']          = $this->createdAt;
        }
        if (isset($this->source)) {
            $json['source']              = $this->source;
        }
        if (isset($this->employeeId)) {
            $json['employee_id']         = $this->employeeId;
        }
        if (isset($this->teamMemberId)) {
            $json['team_member_id']      = $this->teamMemberId;
        }
        $json = array_filter($json, function ($val) {
            return $val !== null;
        });

        return (!$asArrayWhenEmpty && empty($json)) ? new stdClass() : $json;
    }
}
