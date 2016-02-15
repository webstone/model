<?php

namespace Esensi\Model\Contracts;

/**
 * Soft Deleting Model Interface.
 *
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015-2016 Emerson Media LP
 * @license https://github.com/esensi/model/blob/master/license.md MIT License
 *
 * @link http://www.emersonmedia.com
 */
interface SoftDeletingModelInterface
{
    /**
     * Boot the soft deleting trait for a model.
     */
    public static function bootSoftDeletingTrait();

    /**
     * Force a hard delete on a soft deleted model.
     */
    public function forceDelete();

    /**
     * Restore a soft-deleted model instance.
     *
     * @return bool|null
     */
    public function restore();

    /**
     * Determine if the model instance has been soft-deleted.
     *
     * @return bool
     */
    public function trashed();

    /**
     * Get a new query builder that includes soft deletes.
     *
     * @return Illuminate\Database\Eloquent\Builder|static
     */
    public static function withTrashed();

    /**
     * Get a new query builder that only includes soft deletes.
     *
     * @return Illuminate\Database\Eloquent\Builder|static
     */
    public static function onlyTrashed();

    /**
     * Register a restoring model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function restoring($callback);

    /**
     * Register a restored model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function restored($callback);

    /**
     * Get the name of the "deleted at" column.
     *
     * @return string
     */
    public function getDeletedAtColumn();

    /**
     * Get the fully qualified "deleted at" column.
     *
     * @return string
     */
    public function getQualifiedDeletedAtColumn();
}
