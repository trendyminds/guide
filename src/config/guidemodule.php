<?php

namespace modules\guidemodule;

class Config {
    /**
     * Returns the name we should refer to the "Guide" as

     * @return string
     */
    public static function getName(): string
    {
        return "Guide";
    }

    /**
     * Returns the section handle that is used to power the Guide
     * This section must have a single "Body" field
     *
     * @return string
     */
    public static function getSection(): string
    {
        return "userManual";
    }
}
