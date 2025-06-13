<?php

namespace WPDocsify;

class Core
{
    /**
     * Initializes all instances of the project to prepare it for execution.
     */
    public function run(): void
    {
        $template = new Template();
        $template->run();
    }
}
