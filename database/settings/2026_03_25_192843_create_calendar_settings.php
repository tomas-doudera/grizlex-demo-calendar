<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('calendarSettings.step_width', 30);
        $this->migrator->add('calendarSettings.row_height', 75);
    }

    public function down(): void
    {
        $this->migrator->delete('calendarSettings.step_width');
        $this->migrator->delete('calendarSettings.row_height');
    }
};
