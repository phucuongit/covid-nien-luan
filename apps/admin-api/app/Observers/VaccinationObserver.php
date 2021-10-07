<?php

namespace App\Observers;

use App\Models\Vaccination;
use App\Models\Statistic;

class VaccinationObserver
{
    /**
     * Handle the Vaccination "created" event.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return void
     */
    public function created(Vaccination $vaccination)
    {
        $statistic = Statistic::first();
        $time = 
            $vaccination->time;
        if ($time == 1)
            $statistic->injected_first_time += 1;
        else if ($time == 2)
            $statistic->injected_second_time += 1;
        
        $statistic->injected_total_time += 1;
        // Save
        $statistic->save();
    }

    /**
     * Handle the Vaccination "updated" event.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return void
     */
    public function updated(Vaccination $vaccination)
    {
        //
    }

    /**
     * Handle the Vaccination "deleted" event.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return void
     */
    public function deleted(Vaccination $vaccination)
    {
        $statistic = Statistic::first();

        $time = 
            $vaccination->time;
        if ($time == 1) 
            $statistic->injected_first_time -= 1;
        else if ($time == 2)
            $statistic->injected_second_time -= 1;
        
        $statistic->injected_total_time -= 1;
        // Save
        $statistic->save();
    }

    /**
     * Handle the Vaccination "restored" event.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return void
     */
    public function restored(Vaccination $vaccination)
    {
        //
    }

    /**
     * Handle the Vaccination "force deleted" event.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return void
     */
    public function forceDeleted(Vaccination $vaccination)
    {
        //
    }
}
