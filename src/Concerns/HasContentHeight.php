<?php

namespace Elemind\FilamentECharts\Concerns;

trait HasContentHeight
{
    protected static int $contentHeight = 300;

    /**
     * Retrieves the height of the content.
     *
     * @return ?int The height of the content or null if it has not been set.
     */
    protected function getContentHeight(): ?int
    {
        return static::$contentHeight;
    }
}
