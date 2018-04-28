<?php

namespace App\Observers;

use App\Models\Dataset;

Class DatasetObserver
{
    /**
     * Listen to the Dataset creating event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function creating(Dataset $dataset)
    {
    }

    /**
     * Listen to the Dataset saving event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function saving(Dataset $dataset)
    {

    }

    /**
     * Listen to the Dataset created event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function created(Dataset $dataset)
    {

    }

    /**
     * Listen to the Dataset saved event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function saved(Dataset $dataset)
    {
    }    

    /**
     * Listen to the Dataset deleting event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function deleting(Dataset $dataset)
    {
        //
    }
}