<?php

namespace Kiwilan\Papers\Services;

use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

/**
 * Orchestrator to manage and run batches of jobs in sequence.
 *
 * ```php
 * $orchestrator
 *  ->add('setup', fn () => CustomService::setup())
 *  ->add('index.filenames', fn () => [new \App\Jobs\Index\FilenamesJob])
 *  ->add('scout', fn () => [new \App\Jobs\ScoutJob])
 *  ->dispatch();
 * ```
 */
class Orchestrator
{
    /**
     * The array of job batches.
     * @var array{name:string, jobs:array}
     */
    protected array $chain = [];

    /**
     * Add a batch of jobs to the chain.
     *
     * @param  array|callable  $jobs  An array of jobs or a callable (without capturing $this in a non-serializable way) that returns an array of jobs
     */
    public function add(string $name, array|callable $jobs): self
    {
        $this->chain[] = compact('name', 'jobs');

        return $this;
    }

    /**
     * Start the batch chain.
     */
    public function dispatch(): void
    {
        $this->dispatchNext();
    }

    /**
     * Recursive function to dispatch the next batch in the chain.
     */
    protected function dispatchNext(): void
    {
        if (empty($this->chain)) {
            return;
        }

        $batchData = array_shift($this->chain);
        $name = $batchData['name'];
        $jobs = $batchData['jobs'];

        if (is_callable($jobs)) {
            $jobs = $jobs();
        }

        if (empty($jobs)) {
            Log::warning("BATCH: {$name} skipped (no jobs).");

            $this->dispatchNext();

            return;
        }

        Log::info("BATCH: {$name} starting...");

        Bus::batch($jobs)
            ->name($name)
            ->allowFailures()
            ->then(function (Batch $batch) use ($name) {
                Log::info("BATCH: {$name} finished!");
                $this->dispatchNext();
            })
            ->catch(function (Batch $batch, \Throwable $e) use ($name) {
                Log::error("BATCH: {$name} failed: ".$e->getMessage());
            })
            ->dispatch();
    }
}
