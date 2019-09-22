<?php

namespace Cerbero\QueryFilters;

use Illuminate\Database\Eloquent\Model;

/**
 * The dummy model.
 *
 */
class DummyModel extends Model
{
    use FiltersRecords;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dummy_table';
}
